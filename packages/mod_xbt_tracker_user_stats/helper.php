<?php
/**
 * @version		2.5.1
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_user_stats
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho and Psylodesign. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

JModel::addIncludePath(JPATH_SITE.'/components/com_tracker/models', 'TrackerModel');
require_once JPATH_ADMINISTRATOR.'/components/com_tracker/helpers/tracker.php';

// Load the component language file since we need some function from the helper file
$lang =& JFactory::getLanguage();
$extension = 'com_tracker';
$base_dir = JPATH_SITE;
$reload = true;
$lang->load($extension, $base_dir, $reload);

class modXBTTrackerUserStats {

	function getStats(&$params) {
		$db 		= JFactory::getDbo();
		$app 		= JFactory::getApplication();
		$appParams	= $app->getParams('com_tracker');
		$query		= $db->getQuery(true);
		$user		= JFactory::getUser();

		// Select the required fields from the table.
		if ($params->get('downloaded') || $params->get('ratio')) $query->select('tu.downloaded');
		if ($params->get('uploaded') || $params->get('ratio')) $query->select('tu.uploaded');
		if ($params->get('can_leech')) $query->select('tu.can_leech');
		if ($params->get('wait_time')) $query->select('tu.wait_time');
		if ($params->get('peer_limit')) $query->select('tu.peer_limit');
		if ($params->get('torrent_limit')) $query->select('tu.torrent_limit');
		if ($params->get('multiplier_type')) $query->select('tu.multiplier_type');
		if ($params->get('download_multiplier')) $query->select('tu.download_multiplier as user_dm, tg.download_multiplier as group_dm');
		if ($params->get('upload_multiplier')) $query->select('tu.upload_multiplier as user_um, tg.upload_multiplier as group_um');
		
		$query->from('`#__tracker_users` AS tu');

		// Join over the user
		if ($params->get('name')) $query->select('u.name');
		if ($params->get('registration')) $query->select('u.registerDate');
		$query->join('LEFT', '`#__users` AS u ON u.id = tu.id');
		
		// Join over the user group
		if ($params->get('group') || $params->get('ratio')) {
			if ($params->get('group')) $query->select('tg.name AS groupname');
			if ($params->get('ratio')) $query->select('tg.minimum_ratio AS minimum_ratio');
			$query->join('LEFT', '`#__tracker_groups` AS tg ON tg.id = tu.groupID');
		}
		
		// Join over the user country
		if ($params->get('country') && $appParams->get('enable_countries')) {
			$query->select('tc.name AS countryName');
			if ($params->get('country_flag')) $query->select('tc.image as countryImage');
			$query->join('LEFT', '`#__tracker_countries` AS tc ON tc.id = tu.countryID');
		}

		// Join over the donations if we're using them
		if ($appParams->get('enable_donations')) {
			if ($params->get('donations')) {
				$query->select('SUM(td.donated) as donated');
				$query->join('LEFT', '`#__tracker_donations` AS td ON td.uid = tu.id');
			}
			$query->select('SUM(ifnull((SELECT SUM(credited) FROM `#__tracker_donations` WHERE state = 1 AND uid = tu.id), 0)) as credited');
		}
		
		$query->where('tu.id = '.$user->id);
		$db->setQuery($query);
		
	
		return $db->loadNextObject();
	}

}
?>