<?php
/**
 * @version		2.5.0
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho and Psylodesign. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once dirname(__FILE__).'/helper.php';

$user_stats = modXBTTrackerUserStats::getStats($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_xbt_tracker_user_stats', $params->get('layout', 'default'));
?>