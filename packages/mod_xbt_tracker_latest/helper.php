<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR.'/components/com_tracker/helpers/tracker.php';

class ModXBTTrackerLatestHelper {

	public static function getList(&$params) {
		$app = JFactory::getApplication();
		$db 		= JFactory::getDbo();
		$appParams	= $app->getParams('com_tracker');
		$categoryId = $params->get('catid', array());
		$count 		= $params->get('count', 5);

		// Select the required fields from the table.
		$query = $db->getQuery(true);
		$query->clear()
			  ->select('a.*')
			  ->from('`#__tracker_torrents` AS a')
			  ->where('a.flags <> 1')
		// Join over the user who added the torrent
			  ->select('u.username AS torrent_owner')
			  ->join('LEFT', '`#__users` AS u ON u.id = a.uploader')
		// Join over the torrent category
			  ->select('c.title AS torrent_category, c.params as category_params')
			  ->join('LEFT', '`#__categories` AS c ON c.id = a.categoryID');

		if ($appParams->get('enable_licenses')) {
			// Join over the torrent license
			$query->select('l.shortname AS torrent_license')
				  ->join('LEFT', '`#__tracker_licenses` AS l ON l.id = a.licenseID');
		}

		if ($categoryId[0]) {
			// Filter by a single or group of categories.
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('c.id IN ('.$categoryId.')');
		}

		// Add the list ordering clause.
		$query->order($params->get('ordering', 'created_time').' '.$params->get('ordering_direction', 'desc'));

		$db->setQuery( $query, 0, $params->get('count', 5) );

		$items = $db->loadObjectList();

		return $items;
	}
}