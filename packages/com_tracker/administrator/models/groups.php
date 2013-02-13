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

class TrackerModelGroups extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'id', 'a.id',
							'name', 'a.name',
							'view_torrents', 'a.view_torrents',
							'edit_torrents', 'a.edit_torrents',
							'delete_torrents', 'a.delete_torrents',
							'upload_torrents', 'a.upload_torrents',
							'download_torrents', 'a.download_torrents',
							'can_leech', 'a.can_leech',
							'wait_time', 'a.wait_time',
							'peer_limit', 'a.peer_limit',
							'torrent_limit', 'a.torrent_limit',
							'minimum_ratio', 'a.minimum_ratio',
							'download_multiplier', 'a.download_multiplier',
							'upload_multiplier', 'a.upload_multiplier',
							'view_comments', 'a.view_comments',
							'write_comments', 'a.write_comments',
							'edit_comments', 'a.edit_comments',
							'delete_comments', 'a.delete_comments',
							'autopublish_comments', 'a.autopublish_comments',
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
		$query->from('`#__tracker_groups` AS a');

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
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('a.name LIKE '.$search);
			}
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.id')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}
}
