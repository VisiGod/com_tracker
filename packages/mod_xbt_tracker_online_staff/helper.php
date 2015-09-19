<?php
/**
 * @version		3.3.2-dev
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_online_staff
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho, Psylodesign and Patlol. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class ModXBTTrackerOnlineStaffHelper {

	public static function getOnlineStaff(&$params) {
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		// Convert selected usergroups to integers just we case something goes wrong
		$group_list = $params->get('usergroups');

		JArrayHelper::toInteger($group_list);
		// Now we implode the selected groups
		$group_list = implode( ',', $group_list );

		// If no group was chosen, we use 0 to prevent errors in sql
		if (empty($group_list)) $group_list = 0;
		
		$query->select('u.name, tg.name as groupName, s.time')
			  ->from('#__users as u')
			  ->join('LEFT', '#__tracker_users AS tu ON tu.id = u.id')
			  ->join('LEFT', '#__session AS s ON tu.id = s.userid')
			  ->join('LEFT', '#__tracker_groups AS tg ON tu.groupID = tg.id')
			  ->where('tu.groupID IN ( '.$group_list.' )')
			  ->group('u.id');
		// Group ordering
		if ($params->get('group_order') == 'ordering') $query->order('tg.ordering ASC');
		elseif ($params->get('group_order') == 'name') $query->order('tg.name ASC');
		else $query->order('tg.id ASC');
		// User ordering
		if ($params->get('user_order') == 'ordering') $query->order('tu.ordering ASC');
		elseif ($params->get('user_order') == 'name') $query->order('u.name ASC');
		elseif ($params->get('user_order') == 'id') $query->order('u.id ASC');
		else $query->order('s.time DESC');
		// No more ordering...
		$db->setQuery($query);
		$items = $db->loadObjectList();

		return $items;
	}
}