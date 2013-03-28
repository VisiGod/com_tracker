<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class TrackerModelThankyous extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'id', 'a.id',
					'torrentID', 'a.torrentID',
					'uid', 'a.uid',
		    	    'created_time', 'a.created_time',
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

		$state = $this->getUserStateFromRequest($context.'.filter.state', 'filter_state', '');
		$this->setState('filter.state', $state);

		// List state information.
		parent::populateState('a.id', 'desc');
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
		$query->from('`#__tracker_torrent_thanks` AS a');

		// Join over the user who did the thanks
		$query->select('u.username as username');
		$query->join('LEFT', '`#__users` AS u ON u.id = a.uid');

		// Join over the torrent name
		$query->select('tt.name as torrent');
		$query->join('LEFT', '`#__tracker_torrents` AS tt ON tt.fid = a.torrentID');

		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
				$query->where('a.state = '.(int) $state);
		} else if ($state === '') {
				$query->where('(a.state IN (0, 1))');
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.id')).' '.$db->getEscaped($this->getState('list.direction', 'DESC')));

		return $query;
	}
}
