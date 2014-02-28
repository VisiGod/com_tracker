<?php
/**
 * @version			2.5.13-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
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
		$context	= $this->context;

		$search = $this->getUserStateFromRequest($context.'.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($context.'.filter.state', 'filter_state', '');
		$this->setState('filter.state', $state);

		// List state information.
		parent::populateState('a.donation_date', 'desc');
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
				$query->where('a.uid = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
                $query->where('( a.comments LIKE '.$search.' )');
			}
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.id')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}
}
