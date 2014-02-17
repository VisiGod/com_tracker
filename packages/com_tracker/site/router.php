<?php
/**
 * @version			2.5.12-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.categories');

function TrackerBuildRoute(&$query) {
	$segments = array();

	if(isset($query['view'])) {
		$segments[] = $query['view'];
		unset( $query['view'] );
	}
	if (isset($query['task'])) {
		$segments[] = $query['task'];
		unset($query['task']);
	}
	if (isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	}

	return $segments;
}

function TrackerParseRoute($segments) {
	$vars = array();

	switch($segments[0]) 	{
		case 'edit':
			$vars['view'] = 'edit';
			$vars['id'] = (int) $id[0];
			break;
		case 'torrents-list':
			$vars['view'] = 'torrents-list';
			break;
		case 'statistics':
			$vars['view'] = 'statistics';
			break;
		case 'upload':
			$vars['view'] = 'upload';
			break;
		case 'userpanel':
			$vars['view'] = 'userpanel';
			$id = explode( ':', $segments[1] );
			$vars['id'] = (int) $id[0];
			break;
		case 'torrent':
			$vars['view'] = 'torrent';
			$id = explode( ':', $segments[1] );
			$vars['id'] = (int) $id[0];
			break;
	}
	/*
	$count = count($segments);
	if ($count) {
		$count--;
		$segment = array_shift($segments);
		if (is_numeric($segment)) $vars['id'] = $segment;
		else $vars['task'] = $segment;
	}

	if ($count) {
		$count--;
		$segment = array_shift($segments) ;
		if (is_numeric($segment)) $vars['id'] = $segment;
	}

	*/
	return $vars;
}
