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

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.model');

class TrackerModelTorrents extends JModelList {

	public $_context = 'com_tracker.torrents';

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'fid', 't.fid',
							'name', 't.name',
							'category', 'c.title',
							'size', 't.size',
							'created_time', 't.created_time',
							'leechers', 't.leechers',
							'seeders', 't.seeders',
							'completed', 't.completed',
							'torrent_owner', 'torrent_owner',
							'image_file', 't.image_file',
							'license', 't.licenseID',
							'ordering', 't.ordering',
							'state', 't.state'
				);
			}
		parent::__construct($config);

	}

	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_tracker');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		//Omit double (white-)spaces and set state
		$this->setState('filter.search', preg_replace('/\s+/',' ', $search));

		// List state information
		$value = JRequest::getUInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);

		$orderCol	= JRequest::getCmd('filter_order', 't.ordering');
		if (!in_array($orderCol, $this->filter_fields)) {
			$orderCol = 't.ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder	=  JRequest::getCmd('filter_order_Dir', 'ASC');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		$filteredCategoryId = $this->getUserStateFromRequest('com_tracker.filter.category_id', 'filter_category_id', 0, 'uint', false);
		$this->setState('filter.category_id', $filteredCategoryId);

		// List state information.
		parent::populateState('t.fid', 'desc');
		
		$value = JRequest::getUInt('start');
		$this->setState('list.start', $value);
	}

	protected function getListQuery() {
		$params = JComponentHelper::getParams('com_tracker');
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		$query->select(
			$this->getState(
				'list.select',
				't.*'
				)
			);
		$query->from('`#__tracker_torrents` AS t');
		$query->where('t.flags <> 1');

		// Join over the user who added the torrent
		$query->select('u.username AS torrent_owner');
		$query->join('LEFT', '`#__users` AS u ON u.id = t.uploader');

		// Join over the torrent category
		$query->select('c.title AS torrent_category, c.params as category_params');
		$query->join('LEFT', '`#__categories` AS c ON c.id = t.categoryID');

		if ($params->get('enable_licenses')) {
			// Join the torrent license
			$query->select('l.shortname AS torrent_license');
			$query->join('LEFT', '`#__tracker_licenses` AS l ON l.id = t.licenseID');
		}
		
		// experiment for Psylo to have number of thanks in torrent listing
		if ($params->get('enable_thankyou')) {
			$query->select('(select count(id) FROM `#__tracker_torrent_thanks` where torrentID = t.fid) as thanks ');
		}

		//**********************************************************************************************************
		// Filter by a single category
		$filteredCategoryId = $this->getState('filter.category_id');
		if (is_numeric($filteredCategoryId) && ($filteredCategoryId != 0)) {
			$query->where('(c.id = '.(int) $filteredCategoryId.' OR c.parent_id = '.(int) $filteredCategoryId.')');
		}

		//**********************************************************************************************************
		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('t.fid = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('( t.name LIKE '.$search.' )');
			}
		}

		$query->order($this->getState('list.ordering', 't.ordering').' '.$this->getState('list.direction', 'ASC'));

		return $query;
	}


	
}