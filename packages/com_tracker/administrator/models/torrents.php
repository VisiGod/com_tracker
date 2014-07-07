<?php
/**
 * @version		3.3.1-dev
 * @package		Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TrackerModelTorrents extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'fid', 'a.fid',
							'name', 'a.name',
							'category', 'a.categoryID',
							'size', 'a.size',
							'created_time', 'a.created_time',
							'leechers', 'a.leechers',
							'seeders', 'a.seeders',
							'completed', 'a.completed',
							'download_multiplier', 'a.download_multiplier',
							'upload_multiplier', 'a.upload_multiplier',
							'uploader', 'a.uploader',
							'ordering', 'a.ordering',
							'state', 'a.state',
				);
			}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
	
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
	
		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		//Filtering fid
		$this->setState('filter.fid', $app->getUserStateFromRequest($this->context.'.filter.fid', 'filter_fid', '', 'string'));
	
		//Filtering info_hash
		$this->setState('filter.info_hash', $app->getUserStateFromRequest($this->context.'.filter.info_hash', 'filter_info_hash', '', 'string'));
	
		//Filtering leechers
		$this->setState('filter.leechers', $app->getUserStateFromRequest($this->context.'.filter.leechers', 'filter_leechers', '', 'string'));
	
		//Filtering categoryid
		$this->setState('filter.categoryid', $app->getUserStateFromRequest($this->context.'.filter.categoryid', 'filter_categoryid', '', 'string'));
	
		// Load the parameters.
		$params = JComponentHelper::getParams('com_tracker');
		$this->setState('params', $params);
	
		// List state information.
		parent::populateState('a.fid', 'desc');
	}

	protected function getStoreId($id = '') {
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		return parent::getStoreId($id);
	}

	protected function getListQuery() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$params = JComponentHelper::getParams('com_tracker');

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*, a.uploader as uploaderID, c.params as category_params '
			)
		);
		$query->from('`#__tracker_torrents` AS a');
		$query->where('a.flags <> 1');

		// Join the user who added the torrent
		$query->select('u.username AS uploader');
		$query->join('LEFT', '`#__users` AS u ON u.id = a.uploader');

		// Join the torrent category
		$query->select('c.title AS category');
		$query->join('LEFT', '`#__categories` AS c ON c.id = a.categoryID');

		if ($params->get('enable_licenses')) {
			// Join the torrent license
			$query->select('l.shortname AS torrent_license');
			$query->join('LEFT', '`#__tracker_licenses` AS l ON l.id = a.licenseID');
		}

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.fid = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.name LIKE '.$search.' )');
			}
		}

		//Filtering categoryid
		$filter_categoryid = $this->state->get("filter.categoryid");
		if ($filter_categoryid) {
			$query->where("a.categoryID = '".$db->escape($filter_categoryid)."'");
		}
		
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn) {
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	public function getItems() {
		$items = parent::getItems();
		return $items;
	}
}