<?php
/**
 * @version		2.5.11-dev
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_stats
 * @copyright	Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once dirname(__FILE__).'/helper.php';

$tracker_stats = modXbtTrackerStatsHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_xbt_tracker_stats', $params->get('layout', 'default'));
?>