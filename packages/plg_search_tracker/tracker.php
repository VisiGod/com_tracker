<?php
/**
 * @version		2.5.13-dev
 * @package		Joomla.Plugin
 * @subpackage	Search.tracker
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
class plgSearchTracker extends JPlugin {

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
 
	public function onContentSearch( $text, $phrase='', $ordering='', $areas=null ) {
		$db     = JFactory::getDBO();
		$user   = JFactory::getUser(); 
	 
		//If the array is not correct, return it:
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}
	 
		$limit = $this->params->def('search_limit',		50);
	 
		//Use the function trim to delete spaces in front of or at the back of the searching terms
		$text = trim( $text );
	 
		//Return Array when nothing was filled in.
		if ($text == '') {
			return array();
		}
	 
		$wheres = array();
		switch ($phrase) {
			case 'exact': //search exact
				$text           = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$wheres2        = array();
				$wheres2[]      = 'LOWER(t.name) LIKE '.$text;
				$where          = '(' . implode( ') OR (', $wheres2 ) . ')';
				break;
			case 'all': //search all
			case 'any': //search any
			default: //set default
				$words  = explode( ' ', $text );
				$wheres = array();
				foreach ($words as $word) {
					$word 		= $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$wheres2	= array();
					$wheres2[]	= 'LOWER(t.name) LIKE '.$word;
					$wheres[]	= implode( ' OR ', $wheres2 );
				}
				$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
				break;
		}

		switch ( $ordering ) { //ordering of the results
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
	 
		$searchTracker = JText::_( 'Tracker' );
	 
		//the database query
		$query  = 'SELECT t.fid, t.name AS title, t.created_time as created, t.seeders, t.leechers, t.completed,'
				. ' CONCAT_WS( " / ", '. $db->Quote($searchTracker) .', b.title ) AS section,'
				. ' "1" AS browsernav'
				. ' FROM #__tracker_torrents AS t'
				. ' INNER JOIN #__categories AS b ON b.extension = "com_tracker"'
				. ' WHERE ( '. $where .' )'
				. ' AND t.flags <> 1'
				. ' GROUP BY t.fid'
				. ' ORDER BY '. $order;
	 
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