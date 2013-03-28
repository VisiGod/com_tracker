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
require_once JPATH_ADMINISTRATOR.'/components/com_tracker/helpers/tracker.php';

abstract class modXbtTrackerLatestHelper {

	public static function getList(&$params) {
		// Get the dbo
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$appParams = $app->getParams('com_tracker');
		$query	= $db->getQuery(true);

		$categoryId = $params->get('catid', array());
		$count = $params->get('count', 5);
		$ordering = $params->get('ordering', 'created_time');
		$ordering_direction = $params->get('ordering_direction', 'desc');

		// Select the required fields from the table.
		$query->clear();
		$query->select('a.*');
		$query->from('`#__tracker_torrents` AS a');
		$query->where('a.flags <> 1');

		// Join over the user who added the torrent
		$query->select('u.username AS torrent_owner');
		$query->join('LEFT', '`#__users` AS u ON u.id = a.uploader');

		// Join over the torrent category
		$query->select('c.title AS torrent_category, c.params as category_params');
		$query->join('LEFT', '`#__categories` AS c ON c.id = a.categoryID');

		if ($appParams->get('enable_licenses')) {
			// Join over the torrent license
			$query->select('l.shortname AS torrent_license');
			$query->join('LEFT', '`#__tracker_licenses` AS l ON l.id = a.licenseID');
		}

		if ($categoryId[0]) {
			// Filter by a single or group of categories.
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.category IN ('.$categoryId.')');
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($ordering.' '.$ordering_direction));

		$db->setQuery( $query, 0, $params->get('count', 5) );

		$items = $db->loadObjectList();

		return $items;

	}

}
?>