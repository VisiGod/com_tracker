<?php
/**
 * @version		3.3.1-dev
 * @package		Joomla.Plugin
 * @subpackage	Search.tracker
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/

defined('_JEXEC') or die;

class PlgSearchTracker extends JPlugin {

	protected $autoloadLanguage = true;

	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	public function onContentSearchAreas() {
		static $areas = array(
			'Tracker' => 'Tracker'
		);
		return $areas;
	}
 
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null) {
		$db     = JFactory::getDBO();
		$user   = JFactory::getUser(); 
	 
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}
	 
		$limit = $this->params->def('search_limit', 50);
	 
		$text = trim($text);
	 
		if ($text == '') {
			return array();
		}
	 
		switch ($ordering) {
			case 'alpha': //alphabetic, ascending
				$order = 't.name ASC';
				break;
			case 'oldest': //oldest first
				$order = 't.created_time ASC';
				break;
			case 'popular': //popular first
				$order = 't.completed DESC';
				break;
			case 'newest': //newest first
				$order = 't.created_time DESC';
				break;
			default: //default setting: creation date, descending
				$order = 't.created_time DESC';
		}

		$text = $db->quote('%' . $db->escape($text, true) . '%', false);
		$section = JText::_('Tracker');

		//the database query
		$query	= $db->getQuery(true);
		$query->select(
			't.fid, t.name AS title, t.created_time as created, t.seeders, t.leechers, t.completed, '
			. $query->concatenate(array($db->quote($section), "b.title"), " / ") . ' AS section,'
			. '\'2\' AS browsernav')
			  ->from('#__tracker_torrents AS t')
			  ->join('INNER', '#__categories AS b ON b.extension = "com_tracker" AND b.id = t.categoryID')
			  ->where('t.name LIKE ' . $text . ' AND t.flags <> 1 ')
			  ->group('t.fid')
			  ->order($order);
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();

		foreach($rows as $key => $row) {
			$rows[$key]->href = 'index.php?option=com_tracker&view=torrent&id='.$row->fid;
			$rows[$key]->text = JText::_('PLG_SEARCH_TRACKER_SEEDERS').$row->seeders.' , '.
								JText::_('PLG_SEARCH_TRACKER_LEECHERS').$row->leechers.' , '.
								JText::_('PLG_SEARCH_TRACKER_COMPLETED').$row->completed;
		}
	return $rows;
	}
}