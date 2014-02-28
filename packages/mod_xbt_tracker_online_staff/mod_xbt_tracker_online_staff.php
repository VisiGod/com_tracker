<?php
/**
 * @version		2.5.13-dev
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_online_staff
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho, Psylodesign and Patlol. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once dirname(__FILE__).'/helper.php';

$online_staff = modXbtTrackerOnlineStaffHelper::getOnlineStaff($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_xbt_tracker_online_staff', $params->get('layout', 'default'));
?>