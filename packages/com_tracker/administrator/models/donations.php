<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TrackerModelDonations extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'id', 'a.id',
							'uid', 'a.uid',
							'ratio', 'a.ratio',
							'donated', 'a.donated',
							'donation_date', 'a.donation_date',
							'credited', 'a.credited',
							'created_time', 'a.created_time',
							'created_user_id', 'a.created_user_id',
							'comments', 'a.comments',
							'donator', 'du.username',
							'username', 'u.username',
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

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	protected function getStoreId($id = '') {
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		return parent::getStoreId($id);
	}

	protected function getListQuery() {
		$db			= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__tracker_donations` AS a');

		// Join over the user who donated
		$query->select('du.username AS donator');
		$query->join('LEFT', '`#__users` AS du ON du.id = a.uid');

		// Join over the user who added the donation
		$query->select('u.username AS username');
		$query->join('LEFT', '`#__users` AS u ON u.id = a.created_user_id');

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
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.donator LIKE '.$search.' OR a.username LIKE '.$search.')');
			}
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
