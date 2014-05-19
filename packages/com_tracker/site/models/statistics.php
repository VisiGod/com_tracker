<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

/**
 * Methods supporting a list of Tracker records.
 */
class TrackerModelStatistics extends JModelItem {

	/**
	 * Method to get torrent details.
	 * @param	integer	The id of the torrent.
	 * @return	mixed	Menu item data object on success, false on failure.
	 */
	public function &getItem($pk = null) {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$app = JFactory::getApplication();
		$params = $app->getParams();

		//COALESCE(SUM(C.vote_value), 0)
		
		if ($params->get('number_torrents') || $params->get('number_files') || $params->get('total_seeders') || $params->get('total_leechers') || $params->get('total_completed') || $params->get('bytes_shared')) {
			$query->select('COUNT(fid) AS torrents');
			$query->select('COALESCE(SUM(leechers), 0) AS leechers');
			$query->select('COALESCE(SUM(seeders), 0) AS seeders');
			$query->select('COALESCE(SUM(completed), 0) AS completed');
			$query->select('COALESCE(SUM(size), 0) AS shared');
			$query->select('COALESCE(SUM(number_files), 0) AS files');
			$query->from('#__tracker_torrents');
			$query->where('flags <> 1');
			$db->setQuery($query);
			$total_torrents = $db->loadObject();

			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}

			if (empty($total_torrents)) {
				return JError::raiseError(404,JText::_('COM_TRACKER_STATISTICS_NO_TORRENTS'));
			}
		}

		if ($params->get('bytes_downloaded') || $params->get('bytes_uploaded')) {
			// Get the total downloaded and uploaded
			$query->clear();
			$query->select('SUM(downloaded) AS user_downloaded');
			$query->select('SUM(uploaded) AS user_uploaded');
			$query->from('#__tracker_users');
			$db->setQuery($query);
			$total_torrents->total_transferred = $db->loadObject();
		}
		
		// If we have peer speed we get some speed stats
		if ($params->get('peer_speed')) {
			// Get the total downloaded and uploaded
			$query->clear();
			$query->select('SUM(down_rate) AS download_rate');
			$query->select('SUM(up_rate) AS upload_rate');
			$query->from('#__tracker_files_users');
			$db->setQuery($query);
			$total_torrents->total_speed = $db->loadObject();
		}
		
		// User stats
		if ($params->get('top_downloaders') && $params->get('number_top_downloaders')) {
			// Get the top downloaders
			$query->clear();
			$query->select('u.id as uid, u.name, u.username, tu.downloaded, tu.uploaded');
			$query->select('tg.name as usergroup, c.image as countryImage, c.name as countryName');
			$query->from('#__tracker_users AS tu');
			$query->join('LEFT', '`#__users` AS u ON u.id = tu.id');
			$query->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID');
			$query->order('tu.downloaded DESC LIMIT 0,'.$params->get('number_top_downloaders', 5));
			$db->setQuery($query);
			$total_torrents->top_downloaders = $db->loadObjectList();
		} else $total_torrents->top_downloaders = 0;

		if ($params->get('top_uploaders') && $params->get('number_top_uploaders')) {
			// Get the top uploaders
			$query->clear();
			$query->select('u.id as uid, u.name, u.username, tu.downloaded, tu.uploaded');
			$query->select('tg.name as usergroup, c.image as countryImage, c.name as countryName');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id');
			$query->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID');
			$query->order('tu.uploaded DESC LIMIT 0,'.$params->get('number_top_uploaders', 5));
			$db->setQuery($query);
			$total_torrents->top_uploaders = $db->loadObjectList();    
		} else $total_torrents->top_uploaders = 0;

		if ($params->get('top_sharers') && $params->get('number_top_sharers')) {
			// Get the top best sharers
			$query->clear();
			$query->select('u.id as uid, u.name, u.username, tu.downloaded, tu.uploaded');
			$query->select('tg.name as usergroup, c.image as countryImage, c.name as countryName');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id');
			$query->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID');
			$query->having('tu.downloaded > 1073741824 AND tu.uploaded > 1073741824');
			$query->order('(tu.uploaded / tu.downloaded) DESC LIMIT 0,'.$params->get('number_top_sharers', 5));
			$db->setQuery($query);
			$total_torrents->top_sharers = $db->loadObjectList();
		} else $total_torrents->top_sharers = 0;

		if ($params->get('worst_sharers') && $params->get('number_worst_sharers')) {
			// Get the top worst sharers
			$query->clear();
			$query->select('u.id as uid, u.name, u.username, tu.downloaded, tu.uploaded');
			$query->select('tg.name as usergroup, c.image as countryImage, c.name as countryName');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id');
			$query->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID');
			$query->having('tu.downloaded > 1073741824 AND tu.uploaded > 1073741824');
			$query->order('(tu.uploaded / tu.downloaded) LIMIT 0,'.$params->get('number_worst_sharers', 5));
			$db->setQuery($query);
			$total_torrents->worst_sharers = $db->loadObjectList();
		} else $total_torrents->worst_sharers = 0;

		// ---------------------------------------------
		if ($params->get('top_thanked') && $params->get('number_top_thanked')) {
			// Get the top thanked users
			$query->clear();
			$query->select('u.id as uid, u.name, u.username, tg.name as usergroup, c.image as countryImage');
			$query->select('c.name as countryName, ttt.torrentID, COUNT(u.id) as total_thanks');
			$query->from('`#__tracker_torrent_thanks` AS ttt');
			$query->join('LEFT', '`#__tracker_torrents` AS tt ON tt.fid = ttt.torrentID');
			$query->join('LEFT', '`#__users` AS u ON u.id = tt.uploader');
			$query->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id');
			$query->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID');
			$query->group('u.id');
			$query->order('COUNT(u.id) DESC LIMIT 0,'.$params->get('number_top_thanked', 5));
			$db->setQuery($query);
			$total_torrents->top_thanked = $db->loadObjectList();
		} else $total_torrents->top_thanked = 0;

		if ($params->get('top_thanker') && $params->get('number_top_thanker')) {
			// Get the top thankers
			$query->clear();
			$query->select('u.id as uid, u.name, u.username, COUNT(tt.uid) as thanker');
			$query->select('tg.name as usergroup, c.image as countryImage, c.name as countryName');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id');
			$query->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID');
			$query->join('LEFT', '`#__tracker_torrent_thanks` AS tt ON tt.uid = u.id');
			$query->group('tt.uid');
			$query->having('COUNT(tt.uid) > 0');
			$query->order('COUNT(tt.uid) DESC LIMIT 0,'.$params->get('number_top_thanker', 5));
			$db->setQuery($query);
			$total_torrents->top_thanker = $db->loadObjectList();
		} else $total_torrents->top_thanker = 0;
		
		// Torrents stats
		if ($params->get('most_active_torrents') && $params->get('number_most_active_torrents')) {
			// Get the top active torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, count(fu.active) as active');
			$query->select('t.leechers, t.seeders, t.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files_users AS fu');
			$query->join('LEFT', '`#__tracker_torrents` AS t ON t.fid = fu.fid');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('fu.active = 1');
			$query->where('t.flags <> 1');
			$query->group('t.fid');
			$query->order('count(fu.active) DESC LIMIT 0,'.$params->get('number_most_active_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_active_torrents = $db->loadObjectList();
		} else $total_torrents->most_active_torrents = 0;

		if ($params->get('most_seeded_torrents') && $params->get('number_most_seeded_torrents')) {
			// Get the top seeded torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers');
			$query->select('t.seeders, t.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_torrents AS t');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('t.flags <> 1');
			$query->order('t.seeders DESC LIMIT 0,'.$params->get('number_most_seeded_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_seeded_torrents = $db->loadObjectList();
		} else $total_torrents->most_seeded_torrents = 0;

		if ($params->get('most_leeched_torrents') && $params->get('number_most_leeched_torrents')) {
			// Get the top leeched torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers');
			$query->select('t.seeders, t.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_torrents AS t');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('t.flags <> 1');
			$query->order('t.leechers DESC LIMIT 0,'.$params->get('number_most_leeched_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_leeched_torrents = $db->loadObjectList();
		} else $total_torrents->most_leeched_torrents = 0;

		if ($params->get('most_completed_torrents') && $params->get('number_most_completed_torrents')) {
			// Get the top completed torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers');
			$query->select('t.seeders, t.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_torrents AS t');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('t.flags <> 1');
			$query->order('t.completed DESC LIMIT 0,'.$params->get('number_most_completed_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_completed_torrents = $db->loadObjectList();
		} else $total_torrents->most_completed_torrents = 0;

		if ($params->get('most_thanked_torrents') && $params->get('number_most_thanked_torrents')) {
			// Get the top thanked torrents
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed');
			$query->select('c.params as cat_params, c.title as cat_title, COUNT(tt.torrentID) as total_thanks');
			$query->from('#__tracker_torrents AS t');
			$query->join('LEFT', '`#__tracker_torrent_thanks` AS tt ON tt.torrentID = t.fid');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->group('tt.torrentID');
			$query->having('COUNT(tt.torrentID) > 0');
			$query->order('COUNT(tt.torrentID) DESC LIMIT 0,'.$params->get('number_most_thanked_torrents', 5));
			$db->setQuery($query);
			$total_torrents->top_thanked_torrents = $db->loadObjectList();
		} else $total_torrents->top_thanked_torrents = 0;

		if ($params->get('worst_active_torrents') && $params->get('number_worst_active_torrents')) {
			// Get the worst active torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed');
			$query->select('fu.active, fu.mtime, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files_users AS fu');
			$query->join('LEFT', '`#__tracker_torrents` AS t ON t.fid = fu.fid');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('t.flags <> 1');
			$query->group('t.fid');
			$query->having('fu.active = 0');
			$query->order('fu.mtime ASC LIMIT 0,'.$params->get('number_worst_active_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_active_torrents = $db->loadObjectList();
		} else $total_torrents->worst_active_torrents = 0;

		if ($params->get('worst_seeded_torrents') && $params->get('number_worst_seeded_torrents')) {
			// Get the worst seeded torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed');
			$query->select('c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_torrents AS t');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('t.leechers > 0');
			$query->where('t.flags <> 1');
			$query->order('t.leechers DESC, t.seeders LIMIT 0,'.$params->get('number_worst_seeded_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_seeded_torrents = $db->loadObjectList();
		} else $total_torrents->worst_seeded_torrents = 0;

		if ($params->get('worst_leeched_torrents') && $params->get('number_worst_leeched_torrents')) {
			// Get the worst leeched torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed');
			$query->select('c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_torrents AS t');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('t.seeders > 0 AND t.leechers > 0');
			$query->where('t.flags <> 1');
			$query->order('t.seeders DESC, t.leechers LIMIT 0,'.$params->get('number_worst_leeched_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_leeched_torrents = $db->loadObjectList();
		} else $total_torrents->worst_leeched_torrents = 0;

		if ($params->get('worst_completed_torrents') && $params->get('number_worst_completed_torrents')) {
			// Get the worst completed torrent
			$query->clear();
			$query->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed');
			$query->select('c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_torrents AS t');
			$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');
			$query->where('t.flags <> 1');
			$query->order('t.completed LIMIT 0,'.$params->get('number_worst_completed_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_completed_torrents = $db->loadObjectList();
		} else $total_torrents->worst_completed_torrents = 0;

		$this->_item[$pk] = $total_torrents;

		return $this->_item[$pk];
	}

}
