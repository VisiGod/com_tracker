<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.helper');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';
require_once JPATH_COMPONENT.'/helpers/Torrent.php';

class TrackerModelTorrent extends JModelItem {

	protected $_context = 'com_tracker.torrent';

	protected function populateState() {
		$app = JFactory::getApplication();

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('torrent.id', $pk);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$user = JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_tracker')) &&  (!$user->authorise('core.edit', 'com_tracker'))){
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
	}

	public function getItem($pk = null) {
		// Initialise variables.
		$user		= JFactory::getUser();
		$params = JComponentHelper::getParams('com_tracker');
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('torrent.id');

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('t.fid, HEX(t.info_hash) as info_hash, t.leechers, t.seeders, t.completed, t.name, t.description, t.size, t.created_time, t.number_files, t.uploader');
		if ($params->get('allow_upload_anonymous')) {
			$query->select('t.uploader_anonymous');
		}
		if ($params->get('torrent_multiplier')) {
			$query->select('t.download_multiplier, t.upload_multiplier');
		}
		if ($params->get('use_image_file')) {
			$query->select('t.image_file');
		}
		if ($params->get('forum_post_id')) {
			$query->select('t.forum_post');
		}
		if ($params->get('torrent_information')) {
			$query->select('t.info_post');
		}
		if ($params->get('torrent_tags')) {
			$query->select('t.tags');
		}		
		$query->from('#__tracker_torrents AS t');

		// Join on category table.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c on c.id = t.categoryID');

		// Join on the tracker users table
		$query->select('tu.groupID, tu.exemption_type, tu.minimum_ratio as user_minimum_ratio');
		$query->join('LEFT', '#__tracker_users AS tu on tu.id = t.uploader');
		
		// Join on the tracker groups table
		$query->select('tg.minimum_ratio as group_minimum_ratio');
		$query->join('LEFT', '#__tracker_groups AS tg on tg.id = tu.groupID');		
		
		// Join on user table.
		$query->select('u.username as uname');
		$query->join('LEFT', '#__users AS u on u.id = t.uploader');

		if ($params->get('enable_licenses')) {
			// Join on the license.
			$query->select('l.shortname as license');
			$query->join('LEFT', '#__tracker_licenses AS l on l.id = t.licenseID');
		}

		// End the query with the torrent ID
		$query->where('t.fid = ' . (int) $pk);
		$db->setQuery($query);
		$data = $db->loadObject();

		if (empty($data)) {
			return JError::raiseError(404, JText::_('COM_TRACKER_NO_TORRENT'));
		}

		// Get the files of the torrent
		$query->clear()
			  ->select('tf.filename, tf.size')
			  ->from('#__tracker_files_in_torrents AS tf')
			  ->where('tf.torrentID = ' . (int) $pk);
		$db->setQuery($query);
		$data->torrent_files = $db->loadObjectList();

		// Get some tracker config times
		$query->clear()
			  ->select('name, value')
			  ->from('xbt_config');
		$db->setQuery($query);
		$data->xbt_config = $db->loadObjectList('name');
		$time_difference = (int)($data->xbt_config['announce_interval']->value + $data->xbt_config['read_db_interval']->value);
		

		// Get the torrent peers
		$query->clear()
			  ->select('u.id, u.name')
			  ->from('#__users AS u')
			  ->join('LEFT', '#__tracker_users AS tu on tu.id = u.id')
			  ->select('fu.active, fu.left, fu.downloaded, fu.uploaded, fu.mtime');
		// Peer speed mod
		if ($params->get('peer_speed') == 1) $query->select('fu.down_rate, fu.up_rate');
		$query->join('LEFT', '#__tracker_files_users AS fu on fu.uid = u.id')
			  ->select('tc.name as countryname, tc.image as countryimage')
			  ->join('LEFT', '#__tracker_countries AS tc ON tc.id = tu.countryID')
		// Get the number of times the peer is present
		// for when we have the same user seeding/leeching more than once and didnt sent the stop event
			  ->select('(SELECT count(distinct(peer_id)) FROM `#__tracker_announce_log` WHERE mtime >= ( UNIX_TIMESTAMP() - '.$time_difference.' ) AND event <> 3 AND uid = u.id) as num_times')
			  ->where('fu.fid = ' . (int) $pk)
			  ->where('fu.active = 1')
			  ->order('fu.left ASC');
		$db->setQuery($query);
		$data->peers = $db->loadObjectList();

		// If a peer has more than one connection, add it to a sublist objects
		foreach ($data->peers as $i => $peer) {
			if ($peer->num_times > 1) {
				$query->clear()
					  ->select('distinct(peer_id), downloaded, left0, uploaded')
					  ->from('#__tracker_announce_log')
					  ->where('mtime >= ( UNIX_TIMESTAMP() - '.$time_difference.' )')
					  ->where('uid = '.$peer->id)
					  ->where('info_hash = UNHEX("'.$data->info_hash.'")')
					  ->where('event <> 3');
				$db->setQuery($query,0,$peer->num_times);
				$data->peers[$i]->list = $db->loadObjectList();
			} 
		}

		// Get the users that snatched the torrent
		//TODO: Remove the user who uploaded the torrent as a snatcher
		$query->clear()
			  ->select('u.id, u.name')
			  ->from('#__users AS u')
			  ->join('LEFT', '#__tracker_users AS tu on tu.id = u.id')
			  ->select('fu.fid, fu.downloaded, fu.uploaded, fu.mtime')
			  ->join('LEFT', '#__tracker_files_users AS fu on fu.uid = u.id')
			  ->select('tc.name as countryname, tc.image as countryimage')
			  ->join('LEFT', '#__tracker_countries AS tc ON tc.id = tu.countryID')
			  ->where('fu.fid = ' . (int) $pk)
			  ->where('fu.left = 0')
			  ->order('fu.mtime ASC');
		$db->setQuery($query);
		$data->snatchers = $db->loadObjectList();

		// Get the Hit and Runners
		$query->clear()
			  ->select('u.id, u.name')
			  ->from('#__users AS u')
			  ->join('LEFT', '#__tracker_users AS tu on tu.id = u.id')
			  ->select('fu.active as active, fu.left, fu.downloaded as downloaded, fu.uploaded as uploaded, fu.mtime as mtime')
			  ->join('LEFT', '#__tracker_files_users AS fu on fu.uid = u.id')
			  ->select('tc.name as countryname, tc.image as countryimage')
			  ->join('LEFT', '#__tracker_countries AS tc ON tc.id = tu.countryID')
			  ->where('fu.fid = ' . (int) $pk)
			  ->where('fu.active = 0')
			  ->where('fu.uploaded = 0')
			  ->where('fu.downloaded > 0')
			  ->where('fu.left = 0')
			  ->order('fu.mtime DESC');
		$db->setQuery($query);
		$data->hitrunners = $db->loadObjectList();

		if ($params->get('enable_countries') == 1) {
			if (!$params->get('defaultcountry')) $params->set('defaultcountry', 170);
			// Get the default country and flagpic
			$query->clear()
				  ->select('tc.name as name, tc.image as image')
				  ->from('#__tracker_countries AS tc')
				  ->where('tc.id = '.$params->get('defaultcountry'));
			$db->setQuery($query);
			$data->default_country = $db->loadObjectList();
		}

		// Get the torrent thanks
		if ($params->get('enable_thankyou') == 1) {
			$query->clear()
				  ->select('u.username as thanker, ttt.uid as thankerid,  ttt.created_time as thankstime')
				  ->from('#__tracker_torrent_thanks AS ttt')
				  ->join('LEFT', '#__users AS u on u.id = ttt.uid')
				  ->join('LEFT', '#__tracker_torrents AS tt on tt.fid = ttt.torrentID')
				  ->where('ttt.torrentID = '.(int) $pk)
				  ->where('ttt.state = 1');
			$db->setQuery($query);
			$data->thankyous = $db->loadObjectList();
		}
		
		// Get the torrent reseed requests
		if ($params->get('enable_reseedrequest') == 1) {
			$query->clear()
				  ->select('u.username as requester,  trr.created_time as requested_time')
				  ->from('#__tracker_reseed_request AS trr')
				  ->join('LEFT', '#__users AS u on u.id = trr.requester')
				  ->join('LEFT', '#__tracker_torrents AS tt on tt.fid = trr.fid')
				  ->where('trr.fid = '.(int) $pk)
				  ->where('trr.state = 1');
			$db->setQuery($query);
			$data->reseeds = $db->loadObjectList();
		}

		// Get the torrent reports
		if ($params->get('enable_reporttorrent') == 1) {
			$query->clear()
				  ->select('trt.reporter as reporter, trt.created_time as requested_time')
				  ->from('#__tracker_reported_torrents AS trt')
				  ->join('LEFT', '#__users AS u on u.id = trt.reporter')
				  ->join('LEFT', '#__tracker_torrents AS tt on tt.fid = trt.fid')
				  ->where('trt.fid = '.(int) $pk)
				  ->where('trt.state = 1');
			$db->setQuery($query);
			$data->reports = $db->loadObjectList();
		}

		###############################################################################################################################
		##### Torrent Comments #####
		//TODO: Something to take care as soon as the base is working
		/*
		if ($params->get('use_comments') && TrackerHelper::user_permissions('view_comments', $user->get('id'), 1)) {
			// Get the torrent comments
			$query->clear();
			$query->select('tu.id as userid, tu.name as username, tcom.commentdate as commentdate, tcom.description as description');
			$query->from('#__tracker_comments AS tcom');
			$query->join('LEFT', '#__users AS tu ON tu.id = tcom.id');
			$query->where('tcom.torrentid = '.(int)$pk);
			$query->where('tcom.state = 1');
			$query->order('tcom.id DESC');
			$db->setQuery($query);
			$data->comments = $db->loadObjectList();

			if ($params->get('comment_only_leecher')) {
				$query->clear();
				$query->select('(downloaded + uploaded ) AS total');
				$query->from('#__tracker_files_users');
				$query->where('fid = '.(int)$pk);
				$query->where('uid = '.(int)$user->get('id'));
				$db->setQuery($query);
				$data->isleecher = $db->loadResult();
			} else $data->isleecher = 1;

		} else $data->comments = 0;
		*/

		###############################################################################################################################

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		$this->_item[$pk] = $data;

		return $this->_item[$pk];
	}

	public function download() {
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_tracker' );
		$torrent_id = $this->getState('torrent.id');

		$db = $this->getDbo();
		$user = JFactory::getUser();
		$config = new JConfig();

		if (($user->get('guest') && $params->get('allow_guest') == 0) || !TrackerHelper::user_permissions('download_torrents', $user->id)) {
			echo "<script> alert(\"".JText::_( "COM_TRACKER_USER_CANNOT_DOWNLOAD_TORRENT" )."\"); window.history.go(-1);</script>\n";
			return;
	 	}

		# Get the total number of records
		$query = $db->getQuery(true);
		$query->select('count(*)')
			  ->from('#__tracker_torrents')
			  ->where('fid = ' . (int) $torrent_id);
		$db->setQuery($query);
		$total = $db->loadResult();

		if (!$total) {
	 	 	echo "<script> alert('".JText::_( 'COM_TRACKER_INVALID_TORRENT' )."'); window.history.go(-1);</script>\n";
			return;
	 	}

		// All OK so far, let's continue
		# Get the torrent
		$query->clear()
			  ->select('*')
			  ->from('#__tracker_torrents')
			  ->where('fid = ' . (int) $torrent_id);
		$db->setQuery($query);
		$row = $db->loadObjectList();
		$row = $row[0];

		$torrentfile = $row->fid."_".$row->filename;

		if (!is_file(JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrentfile)) {
			echo "<script> alert(\"".JText::_( 'COM_TRACKER_FILE_DOESNT_EXIST' )."\"); window.history.go(-1);</script>\n";
			exit;
		}
		clearstatcache();

		if (!is_readable(JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrentfile)) {
			echo "<script> alert(\"".JText::_( 'COM_TRACKER_FILE_ISNT_READABLE' )."\"); window.history.go(-1);</script>\n";
			exit;
		}
		clearstatcache();

		# Get the xbt tracker config
		$query->clear()
			  ->select('name, value')
			  ->from('xbt_config');
		$db->setQuery($query);
		$tracker = $db->loadObjectList('name');

		// ###############################################################################################################################
		// New Torrent pass version use
		// ###############################################################################################################################
		$uid = $user->id;
		$torrent_pass_private_key = $tracker['torrent_pass_private_key']->value;
		
		# Get the user torrent pass version
		$query->clear()
			  ->select('torrent_pass_version')
			  ->from('#__tracker_users')
			  ->where('id = '.$user->id);
		$db->setQuery($query);
		$torrent_pass_version = $db->loadResult();

		$torrent = new Torrent( JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrentfile );
		// ###############################################################################################################################
		// reset announce trackers
		$torrent->announce(false);

		// Private Torrents
		if (($params->get('make_private') == 1) && !$torrent->is_private()) $torrent->is_private(true);

		// Generate the new torrent passkey from the newly modified torrent
		$torrent_pass = sprintf('%08x%s', $user->id, substr(sha1(sprintf('%s %d %d %s', $torrent_pass_private_key, $torrent_pass_version, $user->id, pack('H*', $torrent->hash_info()))), 0, 24));

		// Check if we have several trackers
		if ($params->get('trackers_address') == '') {
			$announceurl = 'http://'.$_SERVER['SERVER_NAME'].':'.$tracker['listen_port']->value.'/'.$torrent_pass.'/announce';
			// adds the default "site" tracker
			$torrent->announce($announceurl);
		} else {
			$trackers_address = explode(",", $params->get('trackers_address'));
			$trackers_address = str_replace(" ","",$trackers_address);
			for($i = 0; $i < count($trackers_address); $i++){
				$other_trackers = 'http://'.$trackers_address[$i].':'.$tracker['listen_port']->value.'/'.$torrent_pass.'/announce';
				$torrent->announce($other_trackers);
			}
		}
		
		// Put some comment in the torrent
		$torrent->comment(JText::_( 'COM_TRACKER_TORRENT_DOWNLOADED_FROM' ).' '.$config->sitename);

		// If we have tags enabled, put the site name in a Tag and send the torrent filename
		if ($params->get('tag_in_torrent') == 1) $torrent->send('['.$config->sitename.']'.$row->name.'.torrent');
		// Or we send the original torrent file without any tag
		else $torrent->send();
	}

	public function thanks() {
		$app = JFactory::getApplication();
		$torrent_id = $this->getState('torrent.id');
		
		$db = $this->getDbo();
		$user = JFactory::getUser();
		$config = new JConfig();
		
		// Insert the thank you into the table
		$query = $db->getQuery(true);
		$query->insert('#__tracker_torrent_thanks')
			  ->set('torrentID = '.$db->quote($torrent_id))
			  ->set('uid = '.$db->quote($user->id))
			  ->set('created_time = '.$db->quote(date("Y-m-d H:i:s")))
			  ->set('ordering = '.TrackerHelper::getLastOrder('tracker_torrent_thanks'))
			  ->set('state = 1');
		$db->setQuery($query);
		if (!$db->execute()) $app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_id), JText::_('COM_TRACKER_THANKS_NOK'), 'error');
		else $app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_id), JText::_('COM_TRACKER_THANKS_OK'), 'message');
	}

	public function reseed() {
		$app = JFactory::getApplication();
		$torrent_id = $this->getState('torrent.id');

		$db = $this->getDbo();
		$user = JFactory::getUser();
		$config	= JFactory::getConfig();
	
		// Insert the thank you into the table
		$query = $db->getQuery(true);
		$query->insert('#__tracker_reseed_request')
			  ->set('fid = '.$db->quote($torrent_id))
			  ->set('requester = '.$db->quote($user->id))
			  ->set('created_time = '.$db->quote(date("Y-m-d H:i:s")))
			  ->set('ordering = '.TrackerHelper::getLastOrder('tracker_reseed_request'))
			  ->set('state = 1');
		$db->setQuery($query);
		if (!$db->execute()) $app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_id), JText::_('COM_TRACKER_RESEED_REQUEST_NOK'), 'error');

		// all is good. we've inserted the request on the db. let's spam...errr... mail the uploader or uploaders
		$query->clear()
			  ->select('tt.fid, tt.name, u.name as uploader, u.email as uploader_email')
			  ->from('#__tracker_torrents as tt')
			  ->join('LEFT', '#__users as u on u.id = tt.uploader')
			  ->where('tt.fid = ' . (int) $torrent_id);
		$db->setQuery($query);
		$torrent = $db->loadObject();
		
		$emailSubject	= JText::sprintf(
				'COM_TRACKER_RESEED_REQUEST_EMAIL_SUBJECT',
				$torrent->name
		);
		
		$emailBody	= JText::sprintf(
				'COM_TRACKER_RESEED_REQUEST_EMAIL_BODY',
				$torrent->uploader,
				$user->name, 
				$torrent->name,
				JUri::base().'index.php?option=com_tracker&task=torrent.download&id='.$torrent->fid,
				$config->get('fromname'),
				$config->get('sitename')
		);
		
		JFactory::getMailer()->sendMail($config->get('mailfrom'), $config->get('fromname'), $torrent->uploader_email, $emailSubject, $emailBody);
		$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_id), JText::_('COM_TRACKER_RESEED_REQUEST_OK'), 'message');
	}

	public function reported() {
		$app 	= JFactory::getApplication();
		$db 	= $this->getDbo();
		$user 	= JFactory::getUser();
		$config	= JFactory::getConfig();
		
		$report['comments']		= $_POST['jform']['comments'];
		$report['reporter']		= $_POST['jform']['reporter'];
		$report['reporter_name']= $_POST['jform']['reporter_name'];
		$report['report_type']	= $_POST['jform']['report_type'];
		$report['fid']			= $_POST['jform']['fid'];

		// Insert the thank you into the table
		$query = $db->getQuery(true);
		$query->insert('#__tracker_reported_torrents')
			  ->set('fid = '.$db->quote((int)$report['fid']))
			  ->set('reporter = '.$db->quote((int)$report['reporter']))
			  ->set('report_type = '.$db->quote($report['report_type']))
			  ->set('comments = '.$db->quote($report['comments']))
			  ->set('created_time = '.$db->quote(date("Y-m-d H:i:s")))
			  ->set('ordering = '.TrackerHelper::getLastOrder('tracker_reported_torrents'))
			  ->set('state = 1');
		$db->setQuery($query);
		if (!$db->execute()) $app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_id), JText::_('COM_TRACKER_REPORT_TORRENT_NOK'), 'error');

		// all is good. we've inserted the report on the db. let's send an email to the administrator
		$query->clear()
			  ->select('tt.fid, tt.name, u.name as uploader')
			  ->from('#__tracker_torrents as tt')
			  ->join('LEFT', '#__users as u on u.id = tt.uploader')
			  ->where('tt.fid = ' . (int)$_POST['jform']['fid']);
		$db->setQuery($query);
		$torrent = $db->loadObject();
		
		$emailSubject	= JText::sprintf(
				'COM_TRACKER_REPORT_TORRENT_EMAIL_SUBJECT',
				$torrent->name,
				$report['report_type']
		);
		
		// get all admin users
		$query->clear()
			  ->select('name, email, sendEmail, id')
			  ->from('#__users')
			  ->where('sendEmail = 1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		foreach( $rows as $row ) {
			$emailBody	= JText::sprintf(
				'COM_TRACKER_REPORT_TORRENT_EMAIL_BODY',
				$row->name,
				$report['reporter_name'],
				$torrent->name,
				$report['report_type'],
				$report['comments'],
				JUri::base().'index.php?option=com_tracker&view=torrent&id='.$report['fid'],
				$config->get('fromname'),
				$config->get('sitename')
			);
			$email = JFactory::getMailer()->sendMail($config->get('mailfrom'), $config->get('fromname'), $row->email, $emailSubject, $emailBody);
			
			// Check for an error.
			if ($email !== true) {
				$this->setError(JText::_('COM_TRACKER_TORRENT_REPORT_NOTIFY_SEND_MAIL_FAILED'));
				return false;
			}
		}
		$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$report['fid']), JText::_('COM_TRACKER_TORRENT_REPORT_OK'), 'message');
	}
	
	public function uploaded() {
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$params = JComponentHelper::getParams( 'com_tracker' );
		$app	= JFactory::getApplication();

		// Let's start to play with it
		$temp_torrent['name']		= $_POST['jform']['name'];
		$temp_torrent['categoryID']	= $_POST['jform']['categoryID'];
		$temp_torrent['description']= $_POST['jform']['description'];
		if ($params->get('torrent_tags') == 1) $temp_torrent['tags']= $_POST['jform']['tags'];
		else  $temp_torrent['tags'] = '';
		if ($params->get('enable_licenses') == 1) $licenseID = $_POST['jform']['licenseID'];
		else $licenseID = 0;
		if ($params->get('forum_post_id') == 1) $forum_post = $_POST['jform']['forum_post'];
		else $forum_post = 0;
		if ($params->get('torrent_information') == 1) $info_post = $_POST['jform']['info_post'];
		else $info_post = 0;
		if ($params->get('allow_upload_anonymous') == 1) $uploader_anonymous = $_POST['jform']['uploader_anonymous'];
		else $uploader_anonymous = 0;

		if ($params->get('freeleech') == 1) $download_multiplier = 0;
		else $download_multiplier = 1;

		// ------------------------------------------------------------------------------------------------------------------------
		// Let's take care of the .torrent file first
		$temp_torrent['filename'] = $_FILES['jform']['name']['filename'];
		$temp_torrent['temp_file'] = $_FILES['jform']['tmp_name']['filename'];

		// Sanitize the filename
		$temp_torrent['filename'] = TrackerHelper::sanitize_filename($temp_torrent['filename']);

		// If something wrong happened during the file upload, we bail out
		if (!is_uploaded_file($_FILES['jform']['tmp_name']['filename'])) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED'), 'error');
		}

		// If we try to upload an empty file (0 bytes size)
		if ($_FILES['jform']['size']['filename'] == 0) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE'), 'error');
		}

		// Check if the torrent file is really a valid torrent file
		if (!Torrent::is_torrent($_FILES['jform']['tmp_name']['filename'])) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_NOT_BENCODED_FILE'), 'error');
		}

		// Let's create our new torrent object
		$torrent = new Torrent( $_FILES['jform']['tmp_name']['filename'] );

		// And check for errors. Need to find a way to test them all :)
		if ( $errors = $torrent->errors() ) var_dump( $errors );

		// Private Torrents
		if (($params->get('make_private') == 1) && !$torrent->is_private()) $torrent->is_private(true);

		// If the user didnt wrote a name for the torrent, we get it from the filename
		if (empty($_POST['jform']['name'])) {
			$filename = pathinfo($_FILES['jform']['name']['filename']);
		} else {
			$torrent->name($_POST['jform']['name']);
		}

		$query = $db->getQuery(true);
		$query->select('count(fid)')
			  ->from('#__tracker_torrents')
			  ->where('info_hash = UNHEX("'.$torrent->hash_info().'")');
		$db->setQuery($query);
		if ($db->loadResult() > 0) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_ALREADY_EXISTS'), 'error');
		}		

		// ------------------------------------------------------------------------------------------------------------------------
		// The .torrent file is valid, let's continue to our image file (if we choose to use it)
		if ($params->get('use_image_file') && isset($_POST['jform']['image_type'])) {

			// When image_type is don't use image
			if ($_POST['jform']['image_type'] == 0) {
				$image_file_query_value = "";
			}
			
			// When image file is an uploaded file
			if ($_POST['jform']['image_type'] == 1) {
				if (!is_uploaded_file($_FILES['jform']['tmp_name']['image_file'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED_IMAGE'), 'error');
				}

				if (!filesize($_FILES['jform']['tmp_name']['image_file']) || $_FILES['jform']['size']['image_file'] == 0) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE_IMAGE'), 'error');
				}

				if (!TrackerHelper::is_image($_FILES['jform']['tmp_name']['image_file'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_NOT_AN_IMAGE_FILE'), 'error');
				}

				$image_file_extension = end(explode(".", $_FILES['jform']['name']['image_file'])); 
				$image_file_query_value = $torrent->hash_info().'.'.$image_file_extension;
				$image_file_file = $_FILES['jform']['tmp_name']['image_file'];
			}
			
			// When image file is an external link
			if ($_POST['jform']['image_type'] == 2) {
				// If the remote file is unavailable
				if(@!file_get_contents($_POST['jform']['image_link'],0,NULL,0,1)) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_INVALID_FILE'), 'error');
				}
				
				// check if the remote file is not an image
				if (!is_array(@getimagesize($_POST['jform']['image_link']))) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_NOT_IMAGE'), 'error');
				}
				
				$image_file_query_value = $_POST['jform']['image_link'];
			}
		} else {
			$image_file_query_value = "";
		}

		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// All is good, let's insert the record in the database
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		//Insert the torrent into the table
		$query->clear();
		$query = $db->getQuery(true);
		$query->insert('#__tracker_torrents')
			  ->set('info_hash = UNHEX("'.$torrent->hash_info().'")')
			  ->set('ctime = unix_timestamp()')
			  ->set('name = '.$db->quote($torrent->name()))
			  ->set('alias = '.$db->quote($torrent->name()))
			  ->set('filename = '.$db->quote($_FILES['jform']['name']['filename']))
			  ->set('description = '.$db->quote($_POST['jform']['description']))
			  ->set('categoryID = '.$db->quote($_POST['jform']['categoryID']))
			  ->set('size = '.$db->quote($torrent->size()))
			  ->set('created_time = '.$db->quote(date("Y-m-d H:i:s")))
			  ->set('uploader = '.$db->quote($user->id))
			  ->set('number_files = '.$db->quote(count($torrent->content())))
			  ->set('uploader_anonymous = '.$db->quote($uploader_anonymous))
			  ->set('forum_post = '.$db->quote($forum_post))
			  ->set('info_post = '.$db->quote($info_post))
			  ->set('licenseID = '.$db->quote($licenseID))
			  ->set('upload_multiplier = 1')
			  ->set('download_multiplier = '.$db->quote($download_multiplier))
			  ->set('image_file = '.$db->quote($image_file_query_value))
			  ->set('tags = '.$db->quote($temp_torrent['tags']))
			  ->set('ordering = '.TrackerHelper::getLastOrder('tracker_torrents'))
			  ->set('state = 1');
		$db->setQuery($query);
		if (!$db->execute()) {
			JError::raiseError(500, $db->getErrorMsg());
		}

		// Get the torrent ID that we've just inserted in the database
		$torrent_id = $db->insertid();

	/* Need to check this.
	Wrong info for single file torrent
	Wrong filenames for multi file torrent
	*/

		// Insert the list of files of the torrent in the database
		foreach ($torrent->content() as $filename => $filesize) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->insert('#__tracker_files_in_torrents')
				  ->set('torrentID = '.$db->quote($torrent_id))
				  ->set('filename = '.$db->quote($filename))
				  ->set('size = '.$db->quote($filesize));
			$db->setQuery($query);
			if (!$db->execute()) {
				JError::raiseError(500, $db->getErrorMsg());
			}
		}
		
		// If we're in freeleech we need to add the record of the new torrent to the freeleech table
		if ($params->get('freeleech') == 1) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->insert('#__tracker_torrents_freeleech')
				  ->set('fid = '.$db->quote($torrent_id))
				  ->set('download_multiplier = 1');
			$db->setQuery($query);
			if (!$db->execute()) {
				JError::raiseError(500, $db->getErrorMsg());
			}
		}

		$upload_error = 0;
		// Lets try to save the torrent before we continue
		if (!move_uploaded_file($_FILES['jform']['tmp_name']['filename'], JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrent_id."_".$_FILES['jform']['name']['filename'])) $upload_error = 1;

		// And we should also move the image file if we're using it with the option of uploading an image file
		if ($params->get('use_image_file') && $_POST['jform']['image_type'] == 1) {
			if (!move_uploaded_file($_FILES['jform']['tmp_name']['image_file'], JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$image_file_query_value)) $upload_error = 1;
		}

		if ($upload_error == 0) {
			JFactory::getApplication()->setUserState('com_tracker.uploaded.torrent.data', 0);
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_id), JText::_('COM_TRACKER_UPLOAD_OK'), 'message');
		} else {
			$query->clear();
			$query = $db->getQuery(true);
			$query->delete('#__tracker_files_in_torrents')
				  ->where('torrentID='.$db->quote($torrent_id));
			$db->setQuery($query);
			$db->execute();
			if ($error = $db->getErrorMsg()) {
				$this->setError($error);
				return false;
			}
			$query->clear();
			$query = $db->getQuery(true);
			$query->delete('#__tracker_torrents')
				  ->where('fid='.$db->quote($torrent_id));
			$db->setQuery($query);
			$db->execute();
			unlink (JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrent_id."_*");
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_PROBLEM_MOVING_FILE'), 'error');
		}
	}

	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_tracker.torrent', 'torrent', array('control' => 'jform', 'load_data' => true));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData() {
		return $this->edit($torrentID);
	}

}
