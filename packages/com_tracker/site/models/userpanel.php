<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

jimport('joomla.user.user');
JLoader::register('JTableUser', JPATH_PLATFORM.'/joomla/database/table/user.php');

class TrackerModelUserpanel extends JModelItem {

	protected $_context = 'com_tracker.userpanel';

	public function getItem($id = null) {
		$app 		= JFactory::getApplication();
		$session	= JFactory::getSession();
		$db			= JFactory::getDBO();
		$params 	= JComponentHelper::getParams('com_tracker');
		$user_profile = null;

		if (JRequest::getVar( 'id', '', 'get','int' )) $userID = JRequest::getVar( 'id', '', 'get','int' );
		else $userID = $session->get('user')->id;

		// In case the user is new, check the database and add it to the #__tracker_users
		TrackerHelper::get_new_users();
		
		// Load the user from the database (and check if the id exists)
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('id')
			  ->from('#__users')
			  ->where('id = ' . (int)$userID)
			  ->limit('0,1');
		$db->setQuery($query);
		$user_profileID = $db->loadResult();

		if ($user_profileID <> 0) {

			$user_profile	= JFactory::getUser($user_profileID); // load the logged in user

			// Get the user tracker information
			$query = $db->getQuery(true);
			$query->clear();
			$query->select('*')
				  ->from('#__tracker_users')
				  ->where('id = ' . (int)$user_profile->id)
				  ->limit('0,1');
			$db->setQuery($query);
			$user_profile->tracker_info = $db->loadNextObject();

			if ($params->get('enable_countries')) {
				// If the user doesn't have the country defined, get the default country for component parameters
				if ($user_profile->tracker_info->countryID == 0) $user_profile->tracker_info->countryID == $params->get('defaultcountry');
				
				$query = $db->getQuery(true);
				// Get the user country
				$query->select('name, image')
					  ->from('#__tracker_countries')
					  ->where('id = ' . (int)$user_profile->tracker_info->countryID)
					  ->limit('0,1');
				$db->setQuery($query);
				$user_profile->country_info = $db->loadNextObject();
			}

			if ($params->get('enable_thankyou')) {
				// Get the number of times the user was thanked
				$query->clear();
				$query->select('COUNT(u.id) as total_thanks')
					  ->from('`#__tracker_torrent_thanks` AS ttt')
					  ->join('LEFT', '`#__tracker_torrents` AS tt ON tt.fid = ttt.torrentID')
					  ->join('LEFT', '`#__users` AS u ON u.id = tt.uploader')
					  ->where('u.id = ' . (int)$user_profile->id);
				$db->setQuery($query);
				$user_profile->total_thanks = $db->loadResult();

				// Get the number of thanks the user gave
				$query->clear();
				$query->select('COUNT(uid) as thanker')
					  ->from('#__tracker_torrent_thanks')
					  ->where('uid = ' . (int)$user_profile->id);
				$db->setQuery($query);
				$user_profile->thanker = $db->loadResult();
				
			}
			
			// Get the user last IP and tracker activity
			$query->clear();
			$query->select('ipa, mtime')
				  ->from('#__tracker_announce_log')
				  ->where('uid = ' . (int)$user_profile->id)
				  ->order('id DESC')
				  ->limit('0,1');
			$db->setQuery($query);
			$user_profile->announce = $db->loadNextObject();
			
			if (!$user_profile->announce) {
				$user_profile->lastseen = JText::_( 'COM_TRACKER_LAST_TRACKER_ACTIVITY_NEVER' );
				$user_profile->announce = new stdClass();
				$user_profile->announce->ipa = JText::_( 'COM_TRACKER_NO_LAST_IP' );
			}
			else {
				$user_profile->lastseen = date('Y-m-d H:i:s',$user_profile->announce->mtime).'&nbsp;&nbsp;-&nbsp;&nbsp;'.TrackerHelper::last_activity(date('Y-m-d H:i:s',$user_profile->announce->mtime), 1, 1);
				$user_profile->announce->ipa = long2ip($user_profile->announce->ipa);
			}

			// Get the user group
			$query->clear();
			$query->select('*')
				  ->from('#__tracker_groups')
				  ->where('id = ' . (int)$user_profile->tracker_info->groupID)
				  ->order('id DESC')
				  ->limit('0,1');
			$db->setQuery($query);
			$user_profile->group_info = $db->loadNextObject();

			if ($params->get('enable_donations')) {
				// Get the user donations
				$query->clear();
				$query->select('sum(donated) as donated, sum(credited) as credited')
					  ->from('#__tracker_donations')
					  ->where('uid = ' . (int)$user_profile->id)
					  ->where('state = 1');
				$db->setQuery($query);
				$user_profile->user_donations = $db->loadNextObject();
			}
			
			// ---------------------------------------- Snatched Torrents
			// Get total number of snatches
			$query->clear();
			$query->select('count(fu.fid)')
				  ->from('#__tracker_files_users AS fu')
				  ->join('LEFT', '#__tracker_torrents as t on t.fid = fu.fid')
				  ->where('fu.uid = ' . (int)$user_profile->id)
				  ->where('fu.completed > 0')
				  ->where('t.uploader <> '.(int)$user_profile->id);
			$db->setQuery($query);

			if ($user_profile->total_snatch = $db->loadResult()) {
				// Get the user snatched torrents
				$query->clear();
				$query->select('DISTINCT(fu.fid), t.name, t.leechers, t.seeders, t.completed, fu.downloaded, fu.uploaded')
					  ->from('#__tracker_files_users AS fu')
					  ->join('LEFT', '#__tracker_torrents as t on t.fid = fu.fid')
					  ->where('fu.uid = ' . (int)$user_profile->id)
					  ->where('fu.completed > 0')
					  ->where('t.uploader <> '.(int)$user_profile->id)
					  ->order('fu.fid DESC');
				$db->setQuery($query);
				$user_profile->user_snatches = $db->loadObjectList();
			}

			// ---------------------------------------- Uploaded Torrents
			# Get total number of uploaded torrents
			$query->clear();
			$query->select('count(t.fid)')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '#__users as u on u.id = t.uploader')
				  ->where('t.uploader = ' . (int)$user_profile->id)
				  ->where('t.name <> \'\'')
				  ->order('t.fid DESC');
			$db->setQuery($query);

			if ($user_profile->total_uploads = $db->loadResult()) {
				# Get the user uploaded torrents
				$query->clear();
				$query->select('t.fid, t.name, t.leechers, t.seeders, t.completed')
					  ->from('#__tracker_torrents AS t')
					  ->join('LEFT', '#__users AS u ON u.id = t.uploader')
					  ->where('t.uploader = ' . (int)$user_profile->id)
					  ->where('t.name <> \'\'');
				// Show the anonymous uploaded torrent if the user is the owner. If it's another user it keeps the anonymous torrents hidden
				if ($user_profile->id <> $session->get('user')->id || TrackerHelper::user_permissions('edit_torrents', $user_profile->tracker_info->groupID) == 0) $query->where('t.uploader_anonymous = 0');
				$query->order('t.fid DESC');
				$db->setQuery($query);
				$user_profile->user_uploads = $db->loadObjectList();
			}

			// ---------------------------------------- Seeded Torrents
			# Get the user seeded torrents
			$query->clear();
			$query->select('count(fu.fid)')
				  ->from('#__tracker_files_users AS fu')
				  ->join('LEFT', '#__tracker_torrents as t on fu.fid = t.fid')
				  ->where('fu.uid = ' . (int)$user_profile->id)
				  ->where('fu.left = 0')
				  ->where('fu.active = 1')
				  ->where('t.name <> \'\'')
				  ->order('fu.fid DESC');
			$db->setQuery($query);

			if ($user_profile->total_seeds = $db->loadResult()) {
				# Get the user seeded torrents
				$query->clear();
				$query->select('DISTINCT(fu.fid), t.name, t.leechers, t.seeders, t.completed')
					  ->from('#__tracker_files_users AS fu')
					  ->join('LEFT', '#__tracker_torrents as t on fu.fid = t.fid')
					  ->where('fu.uid = ' . (int)$user_profile->id)
					  ->where('fu.left = 0')
					  ->where('fu.active = 1')
					  ->where('t.name <> \'\'')
					  ->order('fu.fid DESC');
				$db->setQuery($query);
				$user_profile->user_seeds = $db->loadObjectList();
			}

			// ---------------------------------------- Leeched and Ran
			# Get the leeched and run torrents
			$query->clear();
			$query->select('count(fu.fid)')
				  ->from('#__tracker_files_users AS fu')
				  ->join('LEFT', '#__tracker_torrents as t on fu.fid = t.fid')
				  ->where('fu.uid = ' . (int)$user_profile->id)
				  ->where('fu.left = 0')
				  ->where('fu.active = 0')
				  ->where('fu.uploaded = 0')
				  ->where('fu.downloaded > 0')
				  ->where('t.name <> \'\'')
				  ->order('fu.fid DESC');
			$db->setQuery($query);

			if ($user_profile->total_hitandran = $db->loadResult()) {
				# Get the leeched and run torrents
				$query->clear();
				$query->select('DISTINCT(fu.fid), t.name, t.leechers, t.seeders, t.completed, fu.downloaded, fu.uploaded')
					  ->from('#__tracker_files_users AS fu')
					  ->join('LEFT', '#__tracker_torrents as t on fu.fid = t.fid')
					  ->where('fu.uid = ' . (int)$user_profile->id)
					  ->where('fu.left = 0')
					  ->where('fu.active = 0')
					  ->where('fu.uploaded = 0')
					  ->where('fu.downloaded > 0')
					  ->where('t.name <> \'\'')
					  ->order('fu.fid DESC');

				$db->setQuery($query);
				$user_profile->user_hitruns = $db->loadObjectList();
			}
		} else {
			$user_profile->id = 0;
			return $user_profile;
		}

		return $user_profile;
	}

	public function resetpassversion() {
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$session= JFactory::getSession();

		$user_browsing = (int)$session->get('user')->id;
		$user_to_reset = (int)$this->getState('userpasskey.id');

		if (!$user_to_reset) {
			echo "<script> alert('".JText::_( 'COM_TRACKER_CHANGE_TORRENT_PASS_VERSION_INVALID_ID' )."'); window.history.go(-1);</script>\n";
		}
		
		if ($user_browsing <> $user_to_reset) {
			echo "<script> alert('".JText::_( 'COM_TRACKER_CHANGE_TORRENT_PASS_VERSION_OTHER_USER' )."'); window.history.go(-1);</script>\n";
		}

		$db 	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update('#__tracker_users')
			  ->set('torrent_pass_version = torrent_pass_version + 1')
			  ->where('id = ' . (int) $user_to_reset);

		$db->setQuery($query);
		if (!$db->execute()) {
			JError::raiseError(500, $db->getErrorMsg());
		}

		// Get SEF status
		$config = JFactory::getConfig();
		// If its on we dont add the view name, if its off we add it
		if ($config->get( 'sef' ) == 1) $app->redirect(JRoute::_('index.php?option=com_tracker'), JText::_('COM_TRACKER_CHANGE_TORRENT_PASS_VERSION_OK'), 'notice');
			else $app->redirect(JRoute::_('index.php?option=com_tracker&view=userpanel', false), JText::_('COM_TRACKER_CHANGE_TORRENT_PASS_VERSION_OK'), 'notice');

	}
}
