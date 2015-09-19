<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR.'/components/com_tracker/helpers/tracker.php';

// Load the component language file since we need some function from the helper file
$lang = JFactory::getLanguage();
$extension = 'com_tracker';
$base_dir = JPATH_SITE;
$reload = true;
$lang->load($extension, $base_dir, $reload);

class ModXBTTrackerStatsHelper {

	public static function getList(&$params) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$app = JFactory::getApplication();

		if ($params->get('number_torrents') || $params->get('number_files') || $params->get('total_seeders') || $params->get('total_leechers') || $params->get('total_completed') || $params->get('bytes_shared')) {
			$query->select('COUNT(fid) AS torrents')
				  ->select('SUM(leechers) AS leechers')
				  ->select('SUM(seeders) AS seeders')
				  ->select('SUM(completed) AS completed')
				  ->select('SUM(size) AS shared')
				  ->select('SUM(number_files) AS files')
				  ->from('#__tracker_torrents')
				  ->where('flags <> 1');
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
			$query->clear()
				  ->select('SUM(downloaded) AS user_downloaded')
				  ->select('SUM(uploaded) AS user_uploaded')
				  ->from('#__tracker_users');
			$db->setQuery($query);
			$total_torrents->total_transferred = $db->loadObject();
		}
		
		// If we have peer speed we get some speed stats
		if ($params->get('download_speed') || $params->get('upload_speed')) {
			// Get the total downloaded and uploaded
			$query->clear()
				  ->select('SUM(down_rate) AS download_rate')
				  ->select('SUM(up_rate) AS upload_rate')
				  ->from('#__tracker_files_users');
			$db->setQuery($query);
			$total_torrents->total_speed = $db->loadObject();
		}
		
		// User stats
		if ($params->get('top_downloaders') && $params->get('number_top_downloaders')) {
			// Get the top downloaders
			$query->clear()
				  ->select('u.id as uid, u.name, tu.downloaded, tu.uploaded')
				  ->select('tg.name as usergroup, c.image as countryImage, c.name as countryName')
				  ->from('#__tracker_users AS tu')
				  ->join('LEFT', '`#__users` AS u ON u.id = tu.id')
				  ->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID')
				  ->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID')
				  ->order('tu.downloaded DESC LIMIT 0,'.$params->get('number_top_downloaders', 5));
			$db->setQuery($query);
			$total_torrents->top_downloaders = $db->loadObjectList();
		} else $total_torrents->top_downloaders = 0;

		if ($params->get('top_uploaders') && $params->get('number_top_uploaders')) {
			// Get the top uploaders
			$query->clear()
				  ->select('u.id as uid, u.name, tu.downloaded, tu.uploaded')
				  ->select('tg.name as usergroup, c.image as countryImage, c.name as countryName')
				  ->from('#__users AS u')
				  ->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id')
				  ->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID')
				  ->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID')
				  ->order('tu.uploaded DESC LIMIT 0,'.$params->get('number_top_uploaders', 5));
			$db->setQuery($query);
			$total_torrents->top_uploaders = $db->loadObjectList();    
		} else $total_torrents->top_uploaders = 0;

		if ($params->get('top_sharers') && $params->get('number_top_sharers')) {
			// Get the top best sharers
			$query->clear()
				  ->select('u.id as uid, u.name, tu.downloaded, tu.uploaded')
				  ->select('tg.name as usergroup, c.image as countryImage, c.name as countryName')
				  ->from('#__users AS u')
				  ->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id')
				  ->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID')
				  ->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID')
				  ->having('tu.downloaded > 1073741824 AND tu.uploaded > 1073741824')
				  ->order('(tu.uploaded / tu.downloaded) DESC LIMIT 0,'.$params->get('number_top_sharers', 5));
			$db->setQuery($query);
			$total_torrents->top_sharers = $db->loadObjectList();
		} else $total_torrents->top_sharers = 0;

		if ($params->get('worst_sharers') && $params->get('number_worst_sharers')) {
			// Get the top worst sharers
			$query->clear()
				  ->select('u.id as uid, u.name, tu.downloaded, tu.uploaded')
				  ->select('tg.name as usergroup, c.image as countryImage, c.name as countryName')
				  ->from('#__users AS u')
				  ->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id')
				  ->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID')
				  ->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID')
				  ->having('tu.downloaded > 1073741824 AND tu.uploaded > 1073741824')
				  ->order('(tu.uploaded / tu.downloaded) LIMIT 0,'.$params->get('number_worst_sharers', 5));
			$db->setQuery($query);
			$total_torrents->worst_sharers = $db->loadObjectList();
		} else $total_torrents->worst_sharers = 0;

		if ($params->get('top_thanked') && $params->get('number_top_thanked')) {
			// Get the top thanked users
			$query->clear()
				  ->select('u.id as uid, u.name, tg.name as usergroup, c.image as countryImage')
				  ->select('c.name as countryName, ttt.torrentID, COUNT(u.id) as total_thanks')
				  ->from('`#__tracker_torrent_thanks` AS ttt')
				  ->join('LEFT', '`#__tracker_torrents` AS tt ON tt.fid = ttt.torrentID')
				  ->join('LEFT', '`#__users` AS u ON u.id = tt.uploader')
				  ->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id')
				  ->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID')
				  ->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID')
				  ->group('u.id')
				  ->order('COUNT(u.id) DESC LIMIT 0,'.$params->get('number_top_thanked', 5));
			$db->setQuery($query);
			$total_torrents->top_thanked = $db->loadObjectList();
		} else $total_torrents->top_thanked = 0;

		if ($params->get('top_thanker') && $params->get('number_top_thanker')) {
			// Get the top thankers
			$query->clear()
				  ->select('u.id as uid, u.name, COUNT(tt.uid) as thanker')
				  ->select('tg.name as usergroup, c.image as countryImage, c.name as countryName')
				  ->from('#__users AS u')
				  ->join('LEFT', '`#__tracker_users` AS tu ON tu.id = u.id')
				  ->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID')
				  ->join('LEFT', '`#__tracker_countries` AS c on c.id = tu.countryID')
				  ->join('LEFT', '`#__tracker_torrent_thanks` AS tt ON tt.uid = u.id')
				  ->group('tt.uid')
				  ->having('COUNT(tt.uid) > 0')
				  ->order('COUNT(tt.uid) DESC LIMIT 0,'.$params->get('number_top_thanker', 5));
			$db->setQuery($query);
			$total_torrents->top_thanker = $db->loadObjectList();
		} else $total_torrents->top_thanker = 0;
		
		// Torrents stats
		if ($params->get('most_active_torrents') && $params->get('number_most_active_torrents')) {
			// Get the top active torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, count(fu.active) as active')
				  ->select('t.leechers, t.seeders, t.completed, c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_files_users AS fu')
				  ->join('LEFT', '`#__tracker_torrents` AS t ON t.fid = fu.fid')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('fu.active = 1')
				  ->where('t.flags <> 1')
				  ->group('t.fid')
				  ->order('count(fu.active) DESC LIMIT 0,'.$params->get('number_most_active_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_active_torrents = $db->loadObjectList();
		} else $total_torrents->most_active_torrents = 0;

		if ($params->get('most_seeded_torrents') && $params->get('number_most_seeded_torrents')) {
			// Get the top seeded torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers')
				  ->select('t.seeders, t.completed, c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('t.flags <> 1')
				  ->order('t.seeders DESC LIMIT 0,'.$params->get('number_most_seeded_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_seeded_torrents = $db->loadObjectList();
		} else $total_torrents->most_seeded_torrents = 0;

		if ($params->get('most_leeched_torrents') && $params->get('number_most_leeched_torrents')) {
			// Get the top leeched torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers')
				  ->select('t.seeders, t.completed, c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('t.flags <> 1')
				  ->order('t.leechers DESC LIMIT 0,'.$params->get('number_most_leeched_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_leeched_torrents = $db->loadObjectList();
		} else $total_torrents->most_leeched_torrents = 0;

		if ($params->get('most_completed_torrents') && $params->get('number_most_completed_torrents')) {
			// Get the top completed torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers')
				  ->select('t.seeders, t.completed, c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('t.flags <> 1')
				  ->order('t.completed DESC LIMIT 0,'.$params->get('number_most_completed_torrents', 5));
			$db->setQuery($query);
			$total_torrents->most_completed_torrents = $db->loadObjectList();
		} else $total_torrents->most_completed_torrents = 0;

		if ($params->get('most_thanked_torrents') && $params->get('number_most_thanked_torrents')) {
			// Get the top thanked torrents
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed')
				  ->select('c.params as cat_params, c.title as cat_title, COUNT(tt.torrentID) as total_thanks')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '`#__tracker_torrent_thanks` AS tt ON tt.torrentID = t.fid')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->group('tt.torrentID')
				  ->having('COUNT(tt.torrentID) > 0')
				  ->order('COUNT(tt.torrentID) DESC LIMIT 0,'.$params->get('number_most_thanked_torrents', 5));
			$db->setQuery($query);
			$total_torrents->top_thanked_torrents = $db->loadObjectList();
		} else $total_torrents->top_thanked_torrents = 0;

		if ($params->get('worst_active_torrents') && $params->get('number_worst_active_torrents')) {
			// Get the worst active torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed')
				  ->select('fu.active, fu.mtime, c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_files_users AS fu')
				  ->join('LEFT', '`#__tracker_torrents` AS t ON t.fid = fu.fid')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('t.flags <> 1')
				  ->group('t.fid')
				  ->having('fu.active = 0')
				  ->order('fu.mtime ASC LIMIT 0,'.$params->get('number_worst_active_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_active_torrents = $db->loadObjectList();
		} else $total_torrents->worst_active_torrents = 0;

		if ($params->get('worst_seeded_torrents') && $params->get('number_worst_seeded_torrents')) {
			// Get the worst seeded torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed')
				  ->select('c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('t.leechers > 0')
				  ->where('t.flags <> 1')
				  ->order('t.leechers DESC, t.seeders LIMIT 0,'.$params->get('number_worst_seeded_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_seeded_torrents = $db->loadObjectList();
		} else $total_torrents->worst_seeded_torrents = 0;

		if ($params->get('worst_leeched_torrents') && $params->get('number_worst_leeched_torrents')) {
			// Get the worst leeched torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed')
				  ->select('c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('t.seeders > 0 AND t.leechers > 0')
				  ->where('t.flags <> 1')
				  ->order('t.seeders DESC, t.leechers LIMIT 0,'.$params->get('number_worst_leeched_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_leeched_torrents = $db->loadObjectList();
		} else $total_torrents->worst_leeched_torrents = 0;

		if ($params->get('worst_completed_torrents') && $params->get('number_worst_completed_torrents')) {
			// Get the worst completed torrent
			$query->clear()
				  ->select('t.fid, t.name, t.size, t.created_time, t.leechers, t.seeders, t.completed')
				  ->select('c.params as cat_params, c.title as cat_title')
				  ->from('#__tracker_torrents AS t')
				  ->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID')
				  ->where('t.flags <> 1')
				  ->order('t.completed LIMIT 0,'.$params->get('number_worst_completed_torrents', 5));
			$db->setQuery($query);
			$total_torrents->worst_completed_torrents = $db->loadObjectList();
		} else $total_torrents->worst_completed_torrents = 0;

		$items = $total_torrents;

		return $items;
	}
}