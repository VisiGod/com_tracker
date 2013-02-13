<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

JModel::addIncludePath(JPATH_SITE.'/components/com_tracker/models', 'TrackerModel');

abstract class modXbtTrackerStatsHelper {

	public static function getList(&$params) {
		// Get the dbo
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$query	= $db->getQuery(true);

		if ($params->get('number_torrents') || $params->get('number_files') || $params->get('total_seeders') || $params->get('total_leechers') || $params->get('total_completed') || $params->get('bytes_shared')) {
			if ($params->get('number_torrents')) $query->select('COUNT(fid) AS torrents');
			if ($params->get('number_files')) $query->select('SUM(numfiles) AS files');
			if ($params->get('total_seeders')) $query->select('SUM(seeders) AS seeders');
			if ($params->get('total_leechers')) $query->select('SUM(leechers) AS leechers');
			if ($params->get('total_completed')) $query->select('SUM(completed) AS completed');
			if ($params->get('bytes_shared')) $query->select('SUM(size) AS shared');
			$query->from('#__tracker_files');
			$query->where('flags <> 1');
			$db->setQuery($query);
			$total_torrents = $db->loadObject();

			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}

			if (empty($total_torrents)) {
				return JError::raiseError(404,JText::_('MOD_XBT_TRACKER_STATS_NO_TORRENTS'));
			}
		}

		if ($params->get('bytes_downloaded') || $params->get('bytes_uploaded')) {
			// Get the total downloaded and uploaded
			$query->clear();
			if ($params->get('bytes_downloaded')) $query->select('SUM(downloaded) AS user_downloaded');
			if ($params->get('bytes_uploaded')) $query->select('SUM(uploaded) AS user_uploaded');
			$query->from('#__users');
			$db->setQuery($query);
			$total_torrents->total_transferred = $db->loadObject();
		}
		
		if ($params->get('top_downloaders') && $params->get('number_top_downloaders')) {
			// Get the top downloaders
			$query->clear();
			$query->select('u.id as uid, u.name, u.downloaded, u.uploaded');
			$query->select('l.level, c.flagpic as country, c.id, c.name as countryname');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users_level` AS l ON l.id = u.id_level');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = u.country');
			$query->order('u.downloaded DESC LIMIT 0,'.$params->get('number_top_downloaders', 5));
			$db->setQuery($query);
			$total_torrents->top_downloaders = $db->loadObjectList();
		} else $total_torrents->top_downloaders = 0;

		if ($params->get('top_uploaders') && $params->get('number_top_uploaders')) {
			// Get the top uploaders
			$query->clear();
			$query->select('u.id as uid, u.name, u.downloaded, u.uploaded');
			$query->select('l.level, c.flagpic as country, c.id, c.name as countryname');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users_level` AS l ON l.id = u.id_level');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = u.country');
			$query->order('u.uploaded DESC LIMIT 0,'.$params->get('number_top_uploaders', 5));
			$db->setQuery( $query );
			$total_torrents->top_uploaders = $db->loadObjectList();    
		} else $total_torrents->top_uploaders = 0;

		if ($params->get('top_sharers') && $params->get('number_top_sharers')) {
			// Get the top best sharers
			$query->clear();
			$query->select('u.id as uid, u.name, u.downloaded, u.uploaded, (u.uploaded / u.downloaded) as ratio');
			$query->select('l.level, c.flagpic as country, c.id, c.name as countryname');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users_level` AS l ON l.id = u.id_level');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = u.country');
			$query->having('u.downloaded > 1073741824 AND u.uploaded > 1073741824');
			$query->order('ratio DESC LIMIT 0,'.$params->get('number_top_sharers', 5));
			$db->setQuery( $query );
			$total_torrents->top_sharers = $db->loadObjectList();
		} else $total_torrents->top_sharers = 0;

		if ($params->get('worst_sharers') && $params->get('number_worst_sharers')) {
			// Get the top worst sharers
			$query->clear();
			$query->select('u.id as uid, u.name, u.downloaded, u.uploaded, (u.uploaded / u.downloaded) as ratio');
			$query->select('l.level, c.flagpic as country, c.id, c.name as countryname');
			$query->from('#__users AS u');
			$query->join('LEFT', '`#__tracker_users_level` AS l ON l.id = u.id_level');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = u.country');
			$query->having('u.downloaded > 1073741824 AND u.uploaded > 1073741824');
			$query->order('ratio LIMIT 0,'.$params->get('number_worst_sharers', 5));
			$db->setQuery( $query );
			$total_torrents->worst_sharers = $db->loadObjectList();
		} else $total_torrents->worst_sharers = 0;

		if ($params->get('most_active_torrents') && $params->get('number_most_active_torrents')) {
			// Get the top active torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, count(fu.active) as active');
			$query->select('torr.leechers, torr.seeders, torr.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files_users AS fu');
			$query->join('LEFT', '`#__tracker_files` AS torr ON torr.fid = fu.fid');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('fu.active = 1');
			$query->where('torr.flags <> 1');
			$query->group('torr.fid');
			$query->order('count(fu.active) DESC LIMIT 0,'.$params->get('number_most_active_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->most_active_torrents = $db->loadObjectList();
		} else $total_torrents->most_active_torrents = 0;

		if ($params->get('most_seeded_torrents') && $params->get('number_most_seeded_torrents')) {
			// Get the top seeded torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, torr.leechers');
			$query->select('torr.seeders, torr.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files AS torr');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('torr.flags <> 1');
			$query->order('torr.seeders DESC LIMIT 0,'.$params->get('number_most_seeded_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->most_seeded_torrents = $db->loadObjectList();
		} else $total_torrents->most_seeded_torrents = 0;

		if ($params->get('most_leeched_torrents') && $params->get('number_most_leeched_torrents')) {
			// Get the top leeched torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, torr.leechers');
			$query->select('torr.seeders, torr.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files AS torr');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('torr.flags <> 1');
			$query->order('torr.leechers DESC LIMIT 0,'.$params->get('number_most_leeched_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->most_leeched_torrents = $db->loadObjectList();
		} else $total_torrents->most_leeched_torrents = 0;

		if ($params->get('most_completed_torrents') && $params->get('number_most_completed_torrents')) {
			// Get the top completed torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, torr.leechers');
			$query->select('torr.seeders, torr.completed, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files AS torr');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('torr.flags <> 1');
			$query->order('torr.completed DESC LIMIT 0,'.$params->get('number_most_completed_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->most_completed_torrents = $db->loadObjectList();
		} else $total_torrents->most_completed_torrents = 0;

		if ($params->get('worst_active_torrents') && $params->get('number_worst_active_torrents')) {
			// Get the worst active torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, torr.leechers, torr.seeders, torr.completed');
			$query->select('fu.active, fu.mtime, c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files_users AS fu');
			$query->join('LEFT', '`#__tracker_files` AS torr ON torr.fid = fu.fid');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('torr.flags <> 1');
			$query->group('torr.fid');
			$query->having('fu.active = 0');
			$query->order('fu.mtime ASC LIMIT 0,'.$params->get('number_worst_active_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->worst_active_torrents = $db->loadObjectList();
		} else $total_torrents->worst_active_torrents = 0;

		if ($params->get('worst_seeded_torrents') && $params->get('number_worst_seeded_torrents')) {
			// Get the worst seeded torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, torr.leechers, torr.seeders, torr.completed');
			$query->select('c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files AS torr');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('torr.leechers > 0');
			$query->where('torr.flags <> 1');
			$query->order('torr.leechers DESC, torr.seeders LIMIT 0,'.$params->get('number_worst_seeded_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->worst_seeded_torrents = $db->loadObjectList();
		} else $total_torrents->worst_seeded_torrents = 0;

		if ($params->get('worst_leeched_torrents') && $params->get('number_worst_leeched_torrents')) {
			// Get the worst leeched torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, torr.leechers, torr.seeders, torr.completed');
			$query->select('c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files AS torr');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('torr.seeders > 0 AND torr.leechers > 0');
			$query->where('torr.flags <> 1');
			$query->order('torr.seeders DESC, torr.leechers LIMIT 0,'.$params->get('number_worst_leeched_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->worst_leeched_torrents = $db->loadObjectList();
		} else $total_torrents->worst_leeched_torrents = 0;

		if ($params->get('worst_completed_torrents') && $params->get('number_worst_completed_torrents')) {
			// Get the worst completed torrent
			$query->clear();
			$query->select('torr.fid, torr.name, torr.size, torr.added, torr.leechers, torr.seeders, torr.completed');
			$query->select('c.params as cat_params, c.title as cat_title');
			$query->from('#__tracker_files AS torr');
			$query->join('LEFT', '`#__categories` AS c ON c.id = torr.category');
			$query->where('torr.flags <> 1');
			$query->order('torr.completed LIMIT 0,'.$params->get('number_worst_completed_torrents', 5));
			$db->setQuery( $query );
			$total_torrents->worst_completed_torrents = $db->loadObjectList();
		} else $total_torrents->worst_completed_torrents = 0;

		$items = $total_torrents;

		return $items;
	}

}

?>