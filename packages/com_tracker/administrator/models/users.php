	<?php
/**
 * @version		3.3.2-dev
 * @package		Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TrackerModelUsers extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'id', 'a.id',
							'name', 'u.name',
							'username', 'u.username',
							'email', 'u.email',
							'block', 'u.block',
							'downloaded', 'a.downloaded',
							'uploaded', 'a.uploaded',
							'ratio', 'ratio',
							'donated', 'donated',
							'groupID', 'a.groupID',
							'countryID', 'a.countryID',
							'download_multiplier', 'a.download_multiplier',
							'upload_multiplier', 'a.upload_multiplier',
							'can_leech', 'a.can_leech',
							'block', 'u.block',
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
		
		/*
		//Filtering group
		$this->setState('filter.group', $app->getUserStateFromRequest($this->context.'.filter.group', 'filter_group', '', 'string'));
		
		//Filtering country
		$this->setState('filter.country', $app->getUserStateFromRequest($this->context.'.filter.country', 'filter_country', '', 'string'));
		*/
		// Load the parameters.
		$params = JComponentHelper::getParams('com_tracker');
		$this->setState('params', $params);
		
		// List state information.
		parent::populateState('a.id', 'asc');
	}

	protected function getStoreId($id = '') {
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
	
		return parent::getStoreId($id);
	}

	protected function getListQuery() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$params = JComponentHelper::getParams('com_tracker');

		// Check for new users and add them to the tracker user table
		TrackerHelper::get_new_users();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*, (a.downloaded/a.uploaded) as ratio '
			)
		);
		$query->from('`#__tracker_users` AS a');

		// Join the user table
		$query->select('u.name as name, u.username as username, u.email as email, u.block as block');
		$query->join('LEFT', '`#__users` AS u ON u.id = a.id');

		if ($params->get('enable_countries')) {
			// Join over the countries table
			$query->select(' c.image AS countryImage, c.name AS countryName');
			$query->join('LEFT', '`#__tracker_countries` AS c on c.id = a.countryID');
		}

		if ($params->get('enable_donations')) {
			// Join over the donations table
			$query->select(' (SELECT SUM(d.donated) FROM `#__tracker_donations` AS d WHERE d.uid = a.id) as donated ');
			$query->select(' (SELECT SUM(d.credited) FROM `#__tracker_donations` AS d WHERE d.uid = a.id) as credited ');
		}

		// Join over the groups table
		$query->select('g.name as group_name');
		$query->join('LEFT', '`#__tracker_groups` AS g on g.id = a.groupID');

		if ($params->get('enable_countries')) {
			// Filter by country
			$country = $this->getState('filter.country');
			if ($country) {
				$country = $db->Quote('%'.$db->getEscaped($country, true).'%');
				$query->where('c.id = '.(int)$country);
			}
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('u.name LIKE '.$search.' OR u.username LIKE '.$search);
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
