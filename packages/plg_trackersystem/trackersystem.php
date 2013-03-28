<?php
/**
 * @version		2.5.0
 * @package		Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemTrackerSystem extends JPlugin {

/*
############################################
# exemption levels - Tracker Ratio related #
############################################

Follow group ratio rules = 0
Follow user ratio rules = 1
Follow no ratio rules = 2
*/
	function onAfterInitialise() {
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$query	= $db->getQuery(true);
		$component_params = &JComponentHelper::getParams( 'com_tracker' );
		$forum_integration = $component_params->get( 'forum_integration', 0 );

		$ratio_plugin = $this->params->get('ratio_plugin',0);
		$ratio_timeframe = $this->params->get('ratio_timeframe',3600);
		$ratio_last_update = $this->params->get('ratio_last_update',0);
		$global_mindownload	= $this->params->get('ratio_mindownload', 5);
		$global_ratio	= $this->params->get('ratio_global_ratio', 1);
		$announce_plugin = $this->params->get('announce_plugin', 0);
		$announce_timeframe = $this->params->get('announce_timeframe',3600);
		$announce_last_update = $this->params->get('announce_last_update',0);
		$forum_plugin = $this->params->get('forum_plugin',0);
		$forumgroup_timeframe = $this->params->get('forumgroup_timeframe',3600);
		$forumgroup_last_update = $this->params->get('forumgroup_last_update',0);

		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// Tracker Ratio - Start
		if ($this->params->get('ratio_plugin') && ( ($ratio_last_update + $ratio_timeframe) < time() ) ) {
			$mindownload	= (1073741824 * $global_mindownload);	// Convert the value to bytes
			// ----------------------------------------------------------------------
			// Remove the permission to leech based on ratio
			// ----------------------------------------------------------------------
			// Get all the users that have a low ratio but are exempt from the group ratio
			// (check the user ratio)
			$query->select('u.id');
			$query->from($db->quoteName('#__tracker_users').' AS u');
			$query->join('LEFT', $db->quoteName('#__tracker_donations').' AS d on u.id = d.uid');
			$query->where('((IFNULL(u.uploaded,0) + (IFNULL(d.credited,0) * 1073741824)) / IFNULL(u.downloaded,0)) < IFNULL(u.minimum_ratio,1)');
			$query->where('u.exemption_type = 1');
			$query->where('u.can_leech = 1');
			$query->where('u.downloaded >= '.(int)$mindownload);
			$db->setQuery($query);
			if ($row = $db->loadResultArray()) {
				// Deny download from the users that have a lower ratio than the allowed
				JArrayHelper::toInteger($row);
				$uids = implode( ',', $row );
				$query->clear();
				$query->update($db->quoteName('#__tracker_users'));
				$query->set('can_leech = 0');
				$query->where('id IN ( '.$uids.' )');
				$db->setquery( $query );
				$db->query( $query );
			}

			// Get all the users that have a low ratio and must follow from the group ratio
			// (check the group ratio)
			$query->clear();
			$query->select('u.id');
			$query->from($db->quoteName('#__tracker_users').' AS u');
			$query->join('LEFT', $db->quoteName('#__tracker_users_level').' AS ul on ul.id = u.groupID');
			$query->join('LEFT OUTER', $db->quoteName('#__tracker_donations').' AS d on u.id = d.uid');
			$query->where('((IFNULL(u.uploaded,0) + (IFNULL(d.credited,0) * 1073741824)) / IFNULL(u.downloaded,0)) < IFNULL(ul.minimum_ratio,1)');
			$query->where('u.exemption_type = 0');
			$query->where('u.can_leech = 1');
			$query->where('u.downloaded >= '.(int)$mindownload);
			$db->setQuery($query);
			if ($row = $db->loadResultArray()) {
				// Deny download from the users that have a lower ratio than the allowed
				JArrayHelper::toInteger($row);
				$uids = implode( ',', $row );
				$query->clear();
				$query->update($db->quoteName('#__tracker_users'));
				$query->set('can_leech = 0');
				$query->where('id IN ( '.$uids.' )');
				$db->setquery( $query );
				$db->query( $query );
			}

			// ----------------------------------------------------------------------
			// Add the permission to leech based on ratio
			// ----------------------------------------------------------------------
			// Get all the users that have a good ratio but are exempt from the group ratio
			// (check the user ratio)
			$query->clear();
			$query->select('u.id');
			$query->from($db->quoteName('#__tracker_users').' AS u');
			$query->join('LEFT OUTER', $db->quoteName('#__tracker_donations').' AS d on u.id = d.uid');
			$query->where('((IFNULL(u.uploaded,0) + (IFNULL(d.credited,0) * 1073741824)) / IFNULL(u.downloaded,0)) >= IFNULL(u.minimum_ratio,1)');
			$query->where('u.exemption_type = 1');
			$query->where('u.can_leech = 0');
			$query->where('u.downloaded >= '.(int)$mindownload);
			$db->setquery( $query );
			if ($row = $db->loadResultArray()) {
				// Allow download from the users that have a higher ratio than the required
				JArrayHelper::toInteger($row);
				$uids = implode( ',', $row );
				$query->clear();
				$query->update($db->quoteName('#__tracker_users'));
				$query->set('can_leech = 1');
				$query->where('id IN ( '.$uids.' )');
				$db->setquery( $query );
				$db->query( $query );
			}

			// Get all the users that have a good ratio but are exempt from the group ratio
			// (check the group ratio)
			$query->clear();
			$query->select('u.id');
			$query->from($db->quoteName('#__tracker_users').' AS u');
			$query->join('LEFT', $db->quoteName('#__tracker_users_level').' AS ul on ul.id = u.groupID');
			$query->join('LEFT OUTER', $db->quoteName('#__tracker_donations').' AS d on u.id = d.uid');
			$query->where('((IFNULL(u.uploaded,0) + (IFNULL(d.credited,0) * 1073741824)) / IFNULL(u.downloaded,0)) >= IFNULL(ul.minimum_ratio,0)');
			$query->where('u.exemption_type = 0');
			$query->where('u.can_leech = 0');
			$query->where('u.downloaded >= '.(int)$mindownload);
			$db->setquery( $query );
			if ($row = $db->loadResultArray()) {
				// Allow download from the users that have a higher ratio than the required
				JArrayHelper::toInteger($row);
				$uids = implode( ',', $row );
				$query->clear();
				$query->update($db->quoteName('#__tracker_users'));
				$query->set('can_leech = 1');
				$query->where('id IN ( '.$uids.' )');
				$db->setquery( $query );
				$db->query( $query );
			}

			// ----------------------------------------------------------------------
			// Add the permission to leech from users that dont follow ratio
			// ----------------------------------------------------------------------
			// Get all the users that dont follow ratio
			$query->clear();
			$query->select('id');
			$query->from($db->quoteName('#__tracker_users'));
			$query->where('exemption = 2');
			$db->setquery( $query );
			if ($row = $db->loadResultArray()) {
				// Allow download from the users that dont need to follow any ratio rules
				JArrayHelper::toInteger($row);
				$uids = implode( ',', $row );
				$query->clear();
				$query->update($db->quoteName('#__tracker_users'));
				$query->set('can_leech = 1');
				$query->where('id IN ( '.$uids.' )');
				$db->setquery( $query );
				$db->query( $query );
			}
		$ratio_last_update = time();
		}
		// Tracker Ratio - End
		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// Announce Cleanup - Start
		if ($this->params->get('announce_plugin') && ( ($announce_last_update + $announce_timeframe) < time() )) {
			// Get the latest rows from announce log where the IP and User ID is equal
			$query->clear();
			$query->select('id');
			$query->from($db->quoteName('#__tracker_announce_log'));
			$query->group('uid, ipa');
			$query->order('mtime DESC');
			$db->setquery( $query );
			if ($row = $db->loadResultArray()) {
				// Delete the rows from announce log that aren't the newest ones
				JArrayHelper::toInteger($row);
				$uids = implode( ',', $row );
				$query->clear();
				$query->delete($db->quoteName('#__tracker_announce_log'));
				$query->where('id NOT IN ( '.$uids.' )');
				$db->setquery( $query );
				$db->query( $query );
			}
			$announce_last_update = time();
		}
		// Announce Cleanup - END
		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// Forum Groups - START
		if ($this->params->get('forum_plugin') && (($forumgroup_last_update + $forumgroup_timeframe) < time() ) && $forum_integration == 1) {
			$joomla_config = new JConfig();
			$joomla_dbprefix 		= $joomla_config->dbprefix;
			$forum_db_server 		= $component_params->get( 'forum_db_server', 'localhost' );
			$forum_db_port 			= $component_params->get( 'forum_db_port', 3306 );
			$forum_database 		= $component_params->get( 'forum_database', '' );
			$forum_db_user 			= $component_params->get( 'forum_db_user', '' );
			$forum_db_password 		= $component_params->get( 'forum_db_password', '' );
			$forum_tableprefix 		= $component_params->get( 'forum_tableprefix', '' );
			$forum_member_tablename = $component_params->get( 'forum_member_tablename', '' );
			$forum_name_field 		= $component_params->get( 'forum_name_field', '' );
			$forum_id_field 		= $component_params->get( 'forum_id_field', '' );
			$forum_group_field 		= $component_params->get( 'forum_group_field', '' );

			$option = array(); 											//prevent problems
			$option['host'] 	= $forum_db_server.':'.$forum_db_port;	// Database host
			$option['user']		= $forum_db_user;						// User for database authentication
			$option['password'] = $forum_db_password;					// Password for database authentication
			$option['database'] = $forum_database;						// Database name
			$option['prefix']	= $forum_tableprefix;					// Database prefix (may be empty)

			$forumdb = JFactory::getDBO ();
			$forumdb = JDatabase::getInstance( $option );

			if ( JError::isError($forumdb) || ($forumdb->getErrorNum() > 0)) echo '<h1>'.JText::_( 'ERROR' ).'</h1>';
			else {
				$forum_member_table = $forum_database.".".$forum_tableprefix.$forum_member_tablename;
				$query->clear();
				$query->select($forum_name_field.' as name, '.$forum_group_field.' as ugroup');
				$query->from($forum_tableprefix.$forum_member_tablename);
				$query->where($forum_id_field.' IS NOT NULL');
				$forumdb->setQuery($query);
				$rows = $forumdb->loadObjectList();
				$query	= $db->getQuery(true);
				$query  = 'CREATE TABLE '.$db->quoteName('#__temp_users_check').' (name varchar(255) not null, ugroup smallint(3))';
				$db->setquery( $query );
				$db->query( $query );
				$query	= $db->getQuery(true);
				$query = 'TRUNCATE '.$db->quoteName('#__temp_users_check');
				$db->setquery( $query );
				$db->query( $query );
				$query	= $db->getQuery(true);
				foreach ($rows as $row) {
					$query->clear();
					$query->insert($db->quoteName('#__temp_users_check'));
					$query->set('`name` = "'.$row->name.'"');
					$query->set('`ugroup` = "'.$row->ugroup.'"');
					$db->setQuery((string) $query);
					$db->query( $query );
				}
				// Get all the users that have a different group in the site and in the forum
				$query->clear();
				$query->select('DISTINCT(u.id)');
				$query->from($db->quoteName('#__tracker_users').' AS u');
				$query->join('LEFT', $db->quoteName('#__temp_users_check').' AS fmt');
				$query->join('LEFT', $db->quoteName('#__users').' AS ju ON ju.username = fmt.name');
				$query->where('u.groupID <> fmt.ugroup');
				$query->where('u.exemption_type = 0');
				$db->setquery( $query );
				$changed_forum_users_id = $db->loadResultArray();

				if (count($changed_forum_users_id) > 0) {
					// Update the users with the current forum group and tracker permissions
					JArrayHelper::toInteger($changed_forum_users_id);
					$uids = implode( ',', $changed_forum_users_id );
					$query->clear();

					$query  = 'UPDATE '.$db->quoteName('#__tracker_users').' AS u ';
					$query .= 'LEFT JOIN #__temp_users_check AS fmt';
					$query .= 'LEFT JOIN #__tracker_groups AS ul ON ul.id = fmt.ugroup ';
					$query .= 'SET u.groupID = fmt.ugroup, ';
					$query .= 'u.wait_time = ul.wait_time, ';
					$query .= 'u.peer_limit = ul.peer_limit, ';
					$query .= 'u.torrent_limit = ul.torrent_limit, ';
					$query .= 'u.minimum_ratio = ul.minimum_ratio, ';
					$query .= 'u.can_leech = ul.can_leech ';
					$query .= 'WHERE u.id IN ( '.$uids.' )';
/*
// Doesnt work for now. Joomla doesnt use 'join' in 'update'
// It will be updated when the new framework is updated
//
					$query->update($db->quoteName('#__users') . ' AS u');
					$query->join('LEFT', $db->quoteName('#__temp_users_check') . ' AS fmt ON u.username = fmt.name');
					$query->join('LEFT', $db->quoteName('#__tracker_users_level') . ' AS ul ON ul.id = fmt.ugroup');
					$query->set('u.groupID = fmt.ugroup');
					$query->set('u.wait_time = ul.wait_time');
					$query->set('u.peer_limit = ul.peer_limit');
					$query->set('u.torrent_limit = ul.torrent_limit');
					$query->set('u.minimum_ratio = ul.minimum_ratio');
					$query->set('u.can_leech = ul.can_leech');
					$query->where('u.id IN ( '.$uids.' )');
*/
					$db->setquery( $query );
					$db->query( $query );
				}
				$query='DROP TABLE '.$db->quoteName('#__temp_users_check');
				$db->setquery( $query );
				$db->query( $query );
			}

			$forumgroup_last_update = time();
		}
		// Forum Groups - END
		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// Updates the users without groups - START
		$query	= $db->getQuery(true);
		$query->select('download_multiplier, upload_multiplier, can_leech, wait_time, peer_limit, torrent_limit, minimum_ratio');
		$query->from($db->quoteName('#__tracker_groups'));
		$query->where('id = '.$component_params->get('base_group'));
		$db->setQuery($query);
		
		if ($result = $db->loadObject()) {
			$query->clear();
			$query->update($db->quoteName('#__tracker_users'));
			$query->set('download_multiplier = '.$result->download_multiplier);
			$query->set('upload_multiplier = '.$result->upload_multiplier);
			$query->set('can_leech = '.$result->can_leech);
			$query->set('wait_time = '.$result->wait_time);
			$query->set('peer_limit = '.$result->peer_limit);
			$query->set('torrent_limit = '.$result->torrent_limit);
			$query->set('minimum_ratio = '.$result->minimum_ratio);
			$query->set('groupID = '.$component_params->get('base_group'));
			$query->where('groupID = 0');
			$query->where('block = 0');
			$db->setQuery($query);
			$db->query();
		}
		// Updates the users without groups - END
		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		// ----------------------------------------------------------------------
		// Update the plugin parameters
		// ----------------------------------------------------------------------
		$query	= $db->getQuery(true);
		$query->update($db->quoteName('#__extensions'));
		$defaults  = '{"ratio_plugin":"'.$ratio_plugin.'",';
		$defaults .= '"ratio_timeframe":"'.$ratio_timeframe.'",';
		$defaults .= '"ratio_mindownload":"'.$global_mindownload.'",';
		$defaults .= '"ratio_global_ratio":"'.$global_ratio.'",';
		$defaults .= '"ratio_last_update":"'.$ratio_last_update.'",';
		$defaults .= '"announce_plugin":"'.$announce_plugin.'",';
		$defaults .= '"announce_timeframe":"'.$announce_timeframe.'",';
		$defaults .= '"announce_last_update":"'.$announce_last_update.'",';
		$defaults .= '"forum_plugin":"'.$forum_plugin.'",';
		$defaults .= '"forumgroup_timeframe":"'.$forumgroup_timeframe.'",';
		$defaults .= '"forumgroup_last_update":"'.$forumgroup_last_update.'"}'; // JSON format for the parameters
		$query->set("params = '" . $defaults . "'");
		$query->where("element = 'trackersystem'");
		$db->setQuery($query);
		$db->query();
	}
}