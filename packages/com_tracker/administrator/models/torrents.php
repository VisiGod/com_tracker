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

class TrackerModelTorrents extends JModelList {

	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
							'fid', 'a.fid',
							'name', 'a.name',
							'category', 'c.id',
							'size', 'a.size',
							'created_time', 'a.created_time',
							'leechers', 'a.leechers',
							'seeders', 'a.seeders',
							'completed', 'a.completed',
							'download_multiplier', 'a.download_multiplier',
							'upload_multiplier', 'a.upload_multiplier',
							'licenseID', 'a.licenseID',
							'image_file', 'a.image_file',
							'forum_post', 'a.forum_post',
							'info_post', 'a.info_post',
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

		// List state information
		$value = JRequest::getUInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		$value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);

		$search = $this->getUserStateFromRequest($context.'.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($context.'.filter.state', 'filter_state', '');
		$this->setState('filter.state', $state);

		$category = $this->getUserStateFromRequest($context.'.filter.category', 'filter_category');
		$this->setState('filter.category', $category);

		// List state information.
		parent::populateState('a.fid', 'desc');
	}

	protected function getListQuery() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$params = JComponentHelper::getParams('com_tracker');

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*, c.params as category_params '
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
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
				$query->where('a.state = '.(int) $state);
		} else if ($state === '') {
				$query->where('(a.state IN (0, 1))');
		}

		// Filter by a single or group of categories.
		$category = $this->getState('filter.category');
		if (is_numeric($category)) {
			$query->where('a.categoryID = '.(int) $category);
		}
		else if (is_array($category)) {
			JArrayHelper::toInteger($category);
			$category = implode(',', $category);
			$query->where('a.categoryID IN ('.$category.')');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.fid = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('a.name LIKE '.$search.'OR a.tags LIKE '.$search);
			}
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.fid')).' '.$db->getEscaped($this->getState('list.direction', 'DESC')));

		return $query;
	}
}