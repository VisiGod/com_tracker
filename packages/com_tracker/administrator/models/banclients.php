<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class TrackerModelBanClients extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'id', 'a.id',
							'peer_id', 'a.peer_id',
							'peer_description', 'a.peer_description',
							'created_time', 'a.created_time',
							'created_user_id', 'a.created_user_id',
							'comment', 'a.comment',
		      	  'username', 'u.username',
							'ordering', 'a.ordering',
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
				'a.*'
			)
		);
		$query->from('`#__tracker_deny_from_clients` AS a');

		// Join over the users
		$query->select('u.username AS username');
		$query->join('LEFT', '`#__users` AS u ON u.id = a.created_user_id');

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.uid = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('( a.comment LIKE '.$search.' )');
			}
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.id')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}

}
