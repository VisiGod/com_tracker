<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class TrackerModelFiletypes extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'id', 'a.id',
							'name', 'a.name',
							'image', 'a.image',
							'ordering', 'a.ordering',
							'state', 'a.state',
				);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null) 	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		$context	= $this->context;

		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($context.'.filter.state', 'filter_state', '');
		$this->setState('filter.state', $state);

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	protected function getListQuery() {
		$db			= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.* '
			)
		);
		$query->from('`#__tracker_filetypes` AS a');

		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
				$query->where('a.state = '.(int) $state);
		} else if ($state === '') {
				$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.name LIKE '.$search.' OR a.image LIKE '.$search.' )');
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'ASC');
		
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		
		return $query;
	}
}
