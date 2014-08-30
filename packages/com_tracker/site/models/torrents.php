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

class TrackerModelTorrents extends JModelList {

	public function __construct($config = array())  {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'name', 't.name',
							'alias', 't.alias',
							'info_hash', 't.info_hash',
							'filename', 't.filename',
							'category', 'c.title',
							'license',
							'size', 't.size',
							'created_time', 't.created_time',
							'leechers', 't.leechers',
							'seeders', 't.seeders',
							'completed', 't.completed',
							'uploader_name', 'uploader_name',
							'uploader_username', 'uploader_username',
							'number_files', 't.number_files',
							'forum_post', 't.forum_post',
							'info_post', 't.info_post',
							'download_multiplier', 't.download_multiplier',
							'upload_multiplier', 't.upload_multiplier',
							'thanks',
			);
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = 'ordering', $direction = 'DESC') {
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		$this->setState('list.limit', $limit);
		
		$limitstart = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $limitstart);
		
		$orderCol = $app->input->get('filter_order', 't.ordering');
		
		if (!in_array($orderCol, $this->filter_fields)) {
			$orderCol = 't.ordering';
		}
		
		$this->setState('list.ordering', $orderCol);
		
		$listOrder = $app->input->get('filter_order_Dir', 'DESC');
		
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
			$listOrder = 'DESC';
		}
		
		$this->setState('list.direction', $listOrder);
		
		// Optional filter text
		$this->setState('list.filter', $app->input->getString('filter-search'));
		
		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		$TorrentStatus = $app->getUserStateFromRequest('com_tracker.filter.torrent_status', 'filter_torrent_status', 0, 'uint', false);
		$this->setState('filter.torrent_status', $TorrentStatus);

		$params = JComponentHelper::getParams('com_tracker');

		if ($params->get('enable_licenses')) {
			$LicenseId = $app->getUserStateFromRequest('com_tracker.filter.license_id', 'filter_license_id', 0, 'uint', false);
			$this->setState('filter.license_id', $LicenseId);
		}

		// Load the parameters.
		$this->setState('params', $params);
	}

	protected function getListQuery() {
		$user = JFactory::getUser();
		$params = JComponentHelper::getParams('com_tracker');

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
	
		// Select required fields
		$query->select(
				$this->getState('list.select', 't.*'))
				->from($db->quoteName('#__tracker_torrents') . ' AS t')
				->where('t.flags <> 1');

		// Join over the user who added the torrent
		$query->select('u.username AS uploader_username, u.name AS uploader_name')
				->join('LEFT', '#__users AS u ON u.id = t.uploader');
		
		// Join over the torrent category
		$query->select('c.title AS torrent_category, c.params as category_params')
			->join('LEFT', '#__categories AS c ON c.id = t.categoryID');

		// Check if we're using Thank yous
		if ($params->get('enable_thankyou')) {
			$subQuery = $db->getQuery(true);
			// Create the base subQuery select statement.
			$subQuery->select('COUNT(id)')
					 ->from($db->quoteName('#__tracker_torrent_thanks'))
					 ->where('torrentID = t.fid');
				
			// Create the base select statement.
			$query->select("(".$subQuery->__toString().") AS thanks");
		}

		// Check if we're using licenses
		if ($params->get('enable_licenses')) {
			$query->select('l.shortname as license')
			->join('LEFT', '#__tracker_licenses AS l ON l.id = t.licenseID');
		}
		// Filter by category
		$CategoryId = $this->getState('filter.category_id');
		if (is_numeric($CategoryId) && ($CategoryId != 0)) {
			$query->where('(c.id = '.(int) $CategoryId.' OR c.parent_id = '.(int) $CategoryId.')');
		}
		
		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('t.state = ' . (int) $state);
		}
		// do not show trashed links on the front-end
		$query->where('t.state != -2');


		// Filter by search in title
		$search = $this->getState('list.filter');
		if (!empty($search)) {
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('( t.name LIKE '.$search.'  OR  t.tags LIKE '.$search.' )');
		}

		// Filter by license
		if ($params->get('enable_licenses')) {
			$LicenseId = $this->getState('filter.license_id');
			if (is_numeric($LicenseId) && ($LicenseId != 0)) {
				$query->where('(l.id = '.(int) $LicenseId.')');
			}
		}

		// Filter by torrent status
		$TorrentStatus = $this->getState('filter.torrent_status');
		if (is_numeric($TorrentStatus) && ($TorrentStatus != 0)) {
			// Torrents with peers
			if ($TorrentStatus == 1) $query->where('((t.leechers + t.seeders) > 0 )');
			// Torrents with seeders
			if ($TorrentStatus == 2) $query->where('(t.seeders > 0 )');
			// Torrents needing seeds (with leechers and no seeders)
			if ($TorrentStatus == 3) $query->where('( t.leechers > 0 AND t.seeders = 0 )');
			// Dead torrents (no leechers and no seeders)
			if ($TorrentStatus == 3) $query->where('( t.leechers = 0 AND t.seeders = 0 )');
		}

		// Add the list ordering clause.
		$query->order($this->getState('list.ordering', 't.ordering') . ' ' . $this->getState('list.direction', 'DESC'));

		return $query;
	}

}