<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_stats
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once dirname(__FILE__).'/helper.php';

$appParams = $app->getParams('com_tracker');
$user	= JFactory::getUser();

// No guests allowed
if (!$params->get('allow_guest') && $user->get('guest')) return;

// Check if the user group is allowed to see the statistics
foreach ($params->get('usergroups') as $group) {
	if ($group == $user->get('id_level')) $noaccess = 0;
	else $noaccess = 1;
} 
if ($noaccess) return;

$list = modXbtTrackerStatsHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_xbt_tracker_stats', $params->get('layout', 'default'));
?>