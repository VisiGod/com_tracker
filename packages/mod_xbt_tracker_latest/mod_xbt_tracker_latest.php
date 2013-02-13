<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once dirname(__FILE__).'/helper.php';

$list = modXbtTrackerLatestHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_xbt_tracker_latest', $params->get('layout', 'default'));
?>