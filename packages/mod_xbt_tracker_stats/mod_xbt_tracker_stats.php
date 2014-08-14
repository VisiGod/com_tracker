<?php
/**
 * @version		3.3.1-dev
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_stats
 * @copyright	Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$tracker_stats = ModXBTTrackerStatsHelper::getList($params);

$class_sfx = htmlspecialchars($params->get('class_sfx'));
require(JModuleHelper::getLayoutPath('mod_xbt_tracker_stats', $params->get('layout', 'default')));
