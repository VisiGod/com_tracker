<?php
/**
 * @version		3.3.1-dev
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_online_staff
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho, Psylodesign and Patlol. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$online_staff = ModXBTTrackerOnlineStaffHelper::getOnlineStaff($params);

$class_sfx = htmlspecialchars($params->get('class_sfx'));
require(JModuleHelper::getLayoutPath('mod_xbt_tracker_online_staff', $params->get('layout', 'default')));
