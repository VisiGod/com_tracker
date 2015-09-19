<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class TrackerRouter extends JComponentRouterBase {

	public function build(&$query) {
		$segments = array();

		if (isset($query['task'])) {
			$segments[] = $query['task'];
			unset($query['task']);
		}

		if (isset($query['id'])) {
			$segments[] = $query['id'];
			unset($query['id']);
		}

		$total = count($segments);

		for ($i = 0; $i < $total; $i++) {
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments;
	}

	public function parse(&$segments) {
		$total = count($segments);
		$vars = array();

		for ($i = 0; $i < $total; $i++) {
			$segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
		}

		// View is always the first element of the array
		$count = count($segments);

		if ($count) {
			$count--;
			$segment = array_shift($segments);

			if (is_numeric($segment)) {
				$vars['id'] = $segment;
			} else {
				$vars['task'] = $segment;
			}
		}

		if ($count) {
			$segment = array_shift($segments);

			if (is_numeric($segment)) {
				$vars['id'] = $segment;
			}
		}

		return $vars;
	}
}

function TrackerBuildRoute(&$query) {
	$router = new TrackerRouter;

	return $router->build($query);
}

function TrackerParseRoute($segments) {
	$router = new TrackerRouter;

	return $router->parse($segments);
}
