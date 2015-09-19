<?php
/**
 * @version		3.3.2-dev
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho and Psylodesign. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$user_stats = ModXBTTrackerUserStats::getStats($params);

$class_sfx = htmlspecialchars($params->get('class_sfx'));
require(JModuleHelper::getLayoutPath('mod_xbt_tracker_user_stats', $params->get('layout', 'default')));
