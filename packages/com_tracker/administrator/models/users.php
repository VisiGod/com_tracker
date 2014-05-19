<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
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
			);
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		$context	= $this->context;

		$search = $this->getUserStateFromRequest($context.'.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($context.'.filter.state', 'filter_state', '');
		$this->setState('filter.state', $state);

		$group = $this->getUserStateFromRequest($context.'.filter.group', 'filter_group');
		$this->setState('filter.group', $group);

		$country = $this->getUserStateFromRequest($context.'.filter.country', 'filter_country');
		$this->setState('filter.country', $country);

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	protected function getListQuery() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$params = JComponentHelper::getParams('com_tracker');

		// In case the user is new, check the database and add it to the #__tracker_users
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
		$query->select(' u.name as name, u.username as username, u.email as email, u.block as block');
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


		// Filter by group
		$group = $this->getState('filter.group');
		if ($group) {
			$query->where('a.groupID = '.(int)$group);
		}

		if ($params->get('enable_countries')) {
			// Filter by country
			$country = $this->getState('filter.country');
			if ($country) {
				$country = $db->Quote('%'.$db->getEscaped($country, true).'%');
				$query->where('c.id = '.(int)$country);
			}
		}

		// Filter by state (user blocked or not)
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
				$query->where('u.block = '.(int)$state);
		} else if ($state === '') {
				$query->where('(u.block IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('u.name LIKE '.$search.' OR u.username LIKE '.$search);
			}
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.id')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}

}
