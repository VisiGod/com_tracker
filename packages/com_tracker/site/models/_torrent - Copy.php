<?php
/**
 * @version			2.5.0
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

		// TODO: Tune these values based on other permissions.
		$user		= JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_tracker')) &&  (!$user->authorise('core.edit', 'com_tracker'))){
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
	}

	public function &getItem($pk = null) {
		// Initialise variables.
		$user		= JFactory::getUser();
		$params = JComponentHelper::getParams('com_tracker');
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('torrent.id');

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('t.fid, t.info_hash, t.leechers, t.seeders, t.completed, t.name, t.description, t.size, t.created_time, t.number_files, t.uploader');
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
		$query->from('#__tracker_torrents AS t');

		// Join on category table.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c on c.id = t.categoryID');

		// Join on the tracker users table
		$query->select('tu.groupID');
		$query->join('LEFT', '#__tracker_users AS tu on tu.id = t.uploader');
		
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
		$query->clear();
		$query->select('tf.filename, tf.size');
		$query->from('#__tracker_files_in_torrents AS tf');
		$query->where('tf.torrentID = ' . (int) $pk);

		$db->setQuery($query);
		$data->torrent_files = $db->loadObjectList();

		// Get the torrent peers
		$query->clear();
		$query->select('u.id, u.name');
		$query->from('#__users AS u');

		$query->join('LEFT', '#__tracker_users AS tu on tu.id = u.id');

		$query->select('fu.active, fu.left, fu.downloaded, fu.uploaded, fu.mtime');
		// Peer speed mod
		if ($params->get('peer_speed') == 1) $query->select('fu.down_rate, fu.up_rate');
		$query->join('LEFT', '#__tracker_files_users AS fu on fu.uid = u.id');
		
		$query->select('tc.name as countryname, tc.image as countryimage');
		$query->join('LEFT', '#__tracker_countries AS tc ON tc.id = tu.countryID');

		$query->where('fu.fid = ' . (int) $pk);
		$query->where('fu.active = 1');
		$query->order('fu.left ASC');

		$db->setQuery($query);
		$data->peers = $db->loadObjectList();

		// Get the users that snatched the torrent
		$query->clear();
		$query->select('u.id, u.name');
		$query->from('#__users AS u');

		$query->join('LEFT', '#__tracker_users AS tu on tu.id = u.id');
		
		$query->select('fu.fid, fu.downloaded, fu.uploaded, fu.mtime');
		$query->join('LEFT', '#__tracker_files_users AS fu on fu.uid = u.id');

		$query->select('tc.name as countryname, tc.image as countryimage');
		$query->join('LEFT', '#__tracker_countries AS tc ON tc.id = tu.countryID');

		$query->where('fu.fid = ' . (int) $pk);
		$query->where('fu.left = 0');
		$query->order('fu.mtime ASC');

		$db->setQuery( $query );
		$data->snatchers = $db->loadObjectList();

		// Get the Hit and Runners
		$query->clear();
		$query->select('u.id, u.name');
		$query->from('#__users AS u');

		$query->join('LEFT', '#__tracker_users AS tu on tu.id = u.id');
		
		$query->select('fu.active as active, fu.left, fu.downloaded as downloaded, fu.uploaded as uploaded, fu.mtime as mtime');
		$query->join('LEFT', '#__tracker_files_users AS fu on fu.uid = u.id');

		$query->select('tc.name as countryname, tc.image as countryimage');
		$query->join('LEFT', '#__tracker_countries AS tc ON tc.id = tu.countryID');

		$query->where('fu.fid = ' . (int) $pk);
		$query->where('fu.active = 0');
		$query->where('fu.uploaded = 0');
		$query->where('fu.downloaded > 0');
		$query->where('fu.left = 0');
		$query->order('fu.mtime DESC');

		$db->setQuery( $query );
		$data->hitrunners = $db->loadObjectList();

		// Get the default country and flagpic
		$query->clear();
		$query->select('tc.name as default_country_name, tc.image as default_country_image');
		$query->from('#__tracker_countries AS tc');
		$query->where('tc.id = '.$params->get('defaultcountry'));
		$db->setQuery( $query );
		$data->default_country = $db->loadObjectList();

		###############################################################################################################################
		##### Torrent Comments #####
/* Something to take care as soon as the base is working
		if ($params->get('use_comments') && TrackerHelper::user_permissions('view_comments', $user->get('id'), 1)) {
			// Get the torrent comments
			$query->clear();
			$query->select('tu.id as userid, tu.name as username, tcom.commentdate as commentdate, tcom.description as description');
			$query->from('#__tracker_comments AS tcom');
			$query->join('LEFT', '#__users AS tu ON tu.id = tcom.id');
			$query->where('tcom.torrentid = '.(int)$pk);
			$query->where('tcom.state = 1');
			$query->order('tcom.id DESC');
			$db->setQuery( $query );
			$data->comments = $db->loadObjectList();

			if ($params->get('comment_only_leecher')) {
				$query->clear();
				$query->select('(downloaded + uploaded ) AS total');
				$query->from('#__tracker_files_users');
				$query->where('fid = '.(int)$pk);
				$query->where('uid = '.(int)$user->get('id'));
				$db->setQuery( $query );
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

	function download() {
		$app = JFactory::getApplication();
		$params =& JComponentHelper::getParams( 'com_tracker' );
		$torrent_id = $this->getState('torrent.id');

		$db = $this->getDbo();
		$user =& JFactory::getUser();
		$config = new JConfig();

		if (($user->get('guest') && $this->get('allow_guest') == 0) || !TrackerHelper::user_permissions('download_torrents', $user->id)) {
			echo "<script> alert(\"".JText::_( "COM_TRACKER_USER_CANNOT_DOWNLOAD_TORRENT" )."\"); window.history.go(-1);</script>\n";
			return;
	 	}

		# Get the total number of records
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from('#__tracker_torrents');
		$query->where('fid = ' . (int) $torrent_id);
		$db->setQuery($query);
		$total = $db->loadResult();

		if (!$total) {
	 	 	echo "<script> alert('".JText::_( 'COM_TRACKER_INVALID_TORRENT' )."'); window.history.go(-1);</script>\n";
			return;
	 	}

		// All OK so far, let's continue
		# Get the torrent
		$query->clear();
		$query->select('*');
		$query->from('#__tracker_torrents');
		$query->where('fid = ' . (int) $torrent_id);
		$db->setQuery($query);
		$row = $db->loadObjectList();
		$row = $row[0];

		$torrentfile = $row->fid."_".str_replace(' ', '_', $row->filename);

		if (!is_file(JPATH_SITE.DS.$params->get('torrent_dir').$torrentfile)) {
			echo "<script> alert(\"".JText::_( 'COM_TRACKER_FILE_DOESNT_EXIST' )."\"); window.history.go(-1);</script>\n";
			exit;
		}
		clearstatcache();

		if (!is_readable(JPATH_SITE.DS.$params->get('torrent_dir').$torrentfile)) {
			echo "<script> alert(\"".JText::_( 'COM_TRACKER_FILE_ISNT_READABLE' )."\"); window.history.go(-1);</script>\n";
			exit;
		}
		clearstatcache();

		# Get the xbt tracker config
		$query->clear();
		$query->select('name, value');
		$query->from('xbt_config');
		$db->setQuery($query);
		$tracker = $db->loadObjectList('name');

		// ###############################################################################################################################
		// New Torrent pass version use
		// ###############################################################################################################################
		$uid = $user->id;
		$torrent_pass_private_key = $tracker['torrent_pass_private_key']->value;
		
		# Get the user torrent pass version
		$query->clear();
		$query->select('torrent_pass_version');
		$query->from('#__tracker_users');
		$query->where('id = '.$user->id);
		$db->setQuery($query);
		$torrent_pass_version = $db->loadResult();

		$torrent = new Torrent( JPATH_SITE.DS.$params->get('torrent_dir').$torrentfile );
		$torrent_pass = sprintf('%08x%s', $user->id, substr(sha1(sprintf('%s %d %d %s', $torrent_pass_private_key, $torrent_pass_version, $user->id, pack('H*', $torrent->hash_info()))), 0, 24));
		// ###############################################################################################################################

		// reset announce trackers
		$torrent->announce(false);

		// Check if we have several trackers
		if ($params->get('trackers_address') == '') {
			$announceurl = 'http://'.$_SERVER['SERVER_NAME'].':'.$tracker['listen_port']->value.'/'.$torrent_pass.'/announce';
			// add a tracker
			$torrent->announce($announceurl);
		} else {
			$trackers_address = explode(",", $params->get('trackers_address'));
			$trackers_address = str_replace(" ","",$trackers_address);
			for($i = 0; $i < count($trackers_address); $i++){
				$other_trackers = 'http://'.$trackers_address[$i].':'.$tracker['listen_port']->value.'/'.$torrent_pass.'/announce';
				$torrent->announce($other_trackers);
			}
		}
		
		// Private Torrents
		if ($params->get('make_private') == 1) $torrent->is_private(true);
			else $torrent->is_private(false);
		
		// Put some comment in the torrent
		$torrent->comment('Torrent downloaded from '.$config->sitename);

		// Put the site name in a Tag before the torrent filename
		if ($params->get('tag_in_torrent') == 1) $torrent->name('['.$config->sitename.']'.$row->name);
		else $torrent->name($row->name);

		// And we send the torrent to the user...
		$torrent->send();
	}


	function uploaded() {

		$db 		=& JFactory::getDBO();
		$user 	=& JFactory::getUser();
		$params =& JComponentHelper::getParams( 'com_tracker' );
		$app		= JFactory::getApplication();


// Let's start to play with it
		$torrent_name 				= $_POST['jform']['name'];
		$torrent_category 		= $_POST['jform']['categoryID'];
		$torrent_description 	= $_POST['jform']['description'];
		if ($params->get('use_licenses') == 1) $torrent_license = $_POST['jform']['licenseID'];
			else $torrent_license = 0;
		if ($params->get('forum_post_id') == 1) $torrent_forum_post = $_POST['jform']['forum_post'];
			else $torrent_forum_post = 0;
		if ($params->get('torrent_information') == 1) $torrent_info_post = $_POST['jform']['info_post'];
			else $torrent_info_post = 0;
		if ($params->get('allow_upload_anonymous') == 1) $owner_anonymous = $_POST['jform']['uploader_anonymous'];
			else $owner_anonymous = 0;

		$upload_fields = array( 'name' => $torrent_name, 
														'category' => $torrent_category, 
														'description' => $torrent_description, 
														'forum_post' => $torrent_forum_post, 
														'info_post' => $torrent_info_post, 
														'owner_anonymous' => $owner_anonymous
													 );

		$query = $db->getQuery(true);
		$query->select('name, value');
		$query->from('xbt_config');
		$db->setQuery($query);
		$tracker = $db->loadObjectList('name');

		// ------------------------------------------------------------------------------------------------------------------------
		// Let's take care of the .torrent file first
		$fname = $_FILES['jform']['name']['filename'];

		// Check if the filename is invalid
		if (!preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $fname)) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_INVALID_FILENAME'), 'error');
		}

		// The file sent is not a torrent
		if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches)) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_FILE_IS_NOT_A_TORRENT'), 'error');
		}

		$shortfname = $torrent = $matches[1];
		if (!empty($torrent_name)) {
			$fname = preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).".torrent";
		} else {
			$torrent_name = basename($fname, ".torrent");
		}			

		$tmpname = $_FILES['jform']['tmp_name']['filename'];

		if (!is_uploaded_file($tmpname)) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED'), 'error');
		}

		if (!filesize($tmpname)) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE'), 'error');
		}

		$dict = TrackerHelper::bdec_file($tmpname, $params->get('max_torrent_size'));
		if (!isset($dict)) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_NOT_BENCODED_FILE'), 'error');
		}

		// Lets put the torrent as private. We don't want to let DHT eat our peers...^
		if ($params->get('make_private') == 1) {
			$dict['value']['info']['value']['private']['type'] = 'integer';
			$dict['value']['info']['value']['private']['value'] = 1;
			$dict['value']['info']['value']['private']['strlen'] = 3;
			$dict['value']['info']['value']['private']['string'] = 'i1e';
			TrackerHelper::benc_file($tmpname, $dict);
			$dict = TrackerHelper::bdec_file($tmpname, $params->get('max_torrent_size'));
		}

		list($info) = TrackerHelper::dict_check($dict, "info");
		list($dname, $plen, $pieces) = TrackerHelper::dict_check($info, "name(string):piece length(integer):pieces(string)");

		if (strlen($pieces) % 20 != 0) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_INVALID_PIECES'), 'error');
		}

		$filelist = array();
		$totallen = TrackerHelper::dict_get($info, "length", "integer");

		if (isset($totallen)) {
			$filelist[] = array($dname, $totallen);
			$type = "single";
		} else {
			$flist = TrackerHelper::dict_get($info, "files", "list");
			if (!isset($flist)) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_MISSING_LENGHT_FILES'), 'error');
			}
			if (!count($flist)) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_NO_FILES'), 'error');
			}
			$totallen = 0;
			foreach ($flist as $fn) {
				list($ll, $ff) = TrackerHelper::dict_check($fn, "length(integer):path(list)");
				$totallen += $ll;
				$ffa = array();
				foreach ($ff as $ffe) {
					if ($ffe["type"] != "string") {
						$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_FILENAME_ERROR'), 'error');
					}
					$ffa[] = $ffe["value"];
				}
				if (!count($ffa)) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_FILENAME_ERROR'), 'error');
				}
				$ffe = implode("/", $ffa);
				$filelist[] = array($ffe, $ll);
			}
			$type = "multi";
		}

		$info_hash = pack("H*", sha1($info["string"]));
		$info_hash = addslashes($info_hash);

		$query->clear();
		$query = $db->getQuery(true);
		$query->select('count(fid)');
		$query->from('#__tracker_files');
		$query->where('info_hash = '.$db->quote($info_hash));
		$db->setQuery( $query );
		if ($db->loadResult() > 0) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_ALREADY_EXISTS'), 'error');
		}		

		// ------------------------------------------------------------------------------------------------------------------------
		// The .torrent file is valid, let's continue to our image file (if we choose to use it)
		if ($params->get('use_image_file') == 1 && $params->get('image_width') > 0) {
			$image = $_FILES["image_file"];
			$tmpimagename = $image["tmp_name"];

			if (!is_uploaded_file($tmpimagename)) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED_IMAGE'), 'error');
			}

			if (!filesize($tmpimagename)) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE_IMAGE'), 'error');
			}

			$image_file_query_field_name = ", image_file";
			$image_file_extension = end(explode(".", $_FILES["image_file"]["name"])); 
			$image_file_query_value = preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).'.'.$image_file_extension;
			$image_file_file = $image["tmp_name"];

		} else {
			$image_file_query_field_name = ", image_file";
			$image_file_query_value = "";
		}

		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// All is good, let's insert the record in the database
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		//Insert the torrent into the table
		$query->clear();
		$query = $db->getQuery(true);
		$query->insert('#__tracker_files');
		$query->set('filename = '.$db->quote($fname));
		$query->set('owner = '.$db->quote($user->get('id')));
		$query->set('owner_anonymous = '.$db->quote($owner_anonymous));
		$query->set('visible = 1');
		$query->set('info_hash = '.$db->quote($info_hash));
		$query->set('name = '.$db->quote($torrent_name));
		$query->set('size = '.$db->quote($totallen));
		$query->set('numfiles = '.$db->quote(count($filelist)));
		$query->set('type = '.$db->quote($type));
		$query->set('description = '.$db->quote($torrent_description));
		$query->set('category = '.$db->quote($torrent_category));
		$query->set('license = '.$db->quote($torrent_license));
		$query->set('save_as = '.$db->quote(preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name)));
		$query->set('forum_post = '.$db->quote($torrent_forum_post));
		$query->set('info_post = '.$db->quote($torrent_info_post));
		$query->set('added = '.$db->quote(date("Y-m-d H:i:s")));
		$query->set('last_action = '.$db->quote(date("Y-m-d H:i:s")));
		$query->set('ctime = unix_timestamp()');
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
		}

		$torrent_id = $db->insertid();

		//insert the files of the torrent into the torrent files table
		foreach ($filelist as $file) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->insert('#__tracker_files_in_torrents');
			$query->set('torrent = '.$db->quote($torrent_id));
			$query->set('filename = '.$db->quote($file[0]));
			$query->set('size = '.$db->quote($file[1]));
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
			}
		}

		$upload_error = 0;
		if (!move_uploaded_file($tmpname, JPATH_SITE.DS.$params->get('torrent_dir').$torrent_id."_".$fname)) $upload_error = 1;

		if ($upload_error == 0) {
			JFactory::getApplication()->setUserState('com_tracker.uploaded.torrent.data', 0);
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_id), JText::_('COM_TRACKER_UPLOAD_OK'), 'message');
		} else {
			$query->clear();
			$query = $db->getQuery(true);
			$query->delete('#__tracker_files_in_torrents');
			$query->where('torrent='.$db->quote($torrent_id));
			$db->setQuery($query);
			$db->query();
			if ($error = $db->getErrorMsg()) {
				$this->setError($error);
				return false;
			}
			$query->clear();
			$query = $db->getQuery(true);
			$query->delete('#__tracker_files');
			$query->where('fid='.$db->quote($torrent_id));
			$db->setQuery($query);
			$db->query();
			unlink (JPATH_SITE.DS.$params->get('torrent_dir').$torrent_id."_".preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).".".$image_file_extension);
			unlink (JPATH_SITE.DS.$params->get('torrent_dir').'thumb_'.$torrent_id."_".preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).".".$image_file_extension);
			unlink (JPATH_SITE.DS.$params->get('torrent_dir').$torrent_id."_".preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).".torrent");
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_PROBLEM_MOVING_FILE'), 'error');
		}
	}

/*
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
*/

}
