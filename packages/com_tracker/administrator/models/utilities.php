<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class TrackerModelUtilities extends JModelList {

	public function clearannounce() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->clear();
		$query->select('id');
		$query->from('#__tracker_announce_log');
		$query->group('uid, ipa');
		$query->order('mtime DESC');
		$db->setQuery((string)$query);
		$row = $db->loadResultArray();

		$query->clear();
		$query->delete();
		$query->from('#__tracker_announce_log');
		$query->where('id NOT IN (\'' . implode('\',\'', $row) . '\')');
		$db->setQuery($query);
		if(!$db->query()) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES_ANNOUNCE_LOG_NOT_OPTIMIZED'));
			return false;
		} else return true;
	}

	function optimizetables() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->clear();
		$query = 'SHOW TABLES';
		$db->setQuery((string)$query);
		$tables = $db->loadResultArray();
		
		for($i = 0; $i < count($tables); $i++) {
			$query = "OPTIMIZE TABLE ".$tables[$i];
			$db->setQuery((string)$query);
		}

		if(!$db->query()) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES_TABLES_WERE_NOT_OPTIMIZED'));
			return false;
		} else return true;
	}

	function importgroups() {
		$params =& JComponentHelper::getParams( 'com_tracker' );
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		if ($params->get('forum_integration') <> 1 ) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES_GROUPS_NOT_IMPORTED'));
			return false;
		}

		$query = 'TRUNCATE #__tracker_groups';
		$db->setQuery((string)$query);
		
		if(!$db->query()) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_GROUPS_ERROR_TRUNCATE'));
			return false;
		}

		// Update the Users Level Table
		$query = $db->getQuery(true);
		$query->clear();
		$query->update('#__tracker_users');
		$query->set('can_leech = 0');
		$query->set('wait_time = 0');
		$query->set('peer_limit = 1');
		$query->set('torrent_limit = 1');
		$query->set('exemption_type = 2');
		$query->set('minimum_ratio = 1');
		$query->set('multiplier_type = 0');
		$query->set('download_multiplier = 1');
		$query->set('upload_multiplier = 1');
		$db->setQuery((string)$query);
		if(!$db->query()) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_GROUPS_ERROR_UPDATE_USERS'));
			return false;
		}

		$option = array(); //prevent problems
		$option['driver']		= 'mysql';														// Database driver name
		$option['user']			= $params->get('forum_db_user');			// User for database authentication
		$option['password']	= $params->get('forum_db_password');	// Password for database authentication
		$option['database']	= $params->get('forum_database');			// Database name
		$option['prefix']		= $params->get('forum_tableprefix');	// Database prefix (may be empty)

		if ($params->get('forum_db_port') <> '') {
			if ($params->get('forum_db_port') == '' ) $option['host'] = $params->get('forum_db_server').':'.$params->get('forum_db_port'); // Database host name with a different standard port
				else $option['host'] = $params->get('forum_db_server'); // Database host name without any port (uses the standard one)
		}
		$forumdb = JDatabase::getInstance( $option );

		if ($forumdb->getErrorNum() <> '') {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_GROUPS_BAD_DATABASE_VALUES'));
			return false;
		}

		// Get the new groups from the forum database
		$query = $forumdb->getQuery(true);
		$query->clear();
		$query->select($params->get('forum_group_id_field').' as id');
		$query->select($params->get('forum_group_name_field').' as title');
		$query->from($params->get('forum_tableprefix').$params->get('forum_group_tablename'));
		$query->order($params->get('forum_group_id_field'));
		$forumdb->setQuery((string)$query);
		if(!$forumdb->query()) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_GROUPS_ERROR_IMPORT_GROUPS'));
			return false;
		}
		$forum_groups = $forumdb->loadObjectList();

		for ($i=0; $i < count($forum_groups); $i++) {
			// Get the Joomla users
			$query = $db->getQuery(true);
			$query->clear();
			$query->select('username');
			$query->from('#__users');
			$query->where('block = 0');
			$db->setQuery((string)$query);
			$joomla_users= $db->loadResultArray();

			// Get the users that belong to that group
			$query = $db->getQuery(true);
			$query->clear();
			$query->select($params->get('forum_name_field').' as username');
			$query->from($params->get('forum_tableprefix').$params->get('forum_member_tablename'));
			$query->where($params->get('forum_group_field').' = '.$forum_groups[$i]->id);
			$query->where($params->get('forum_name_field').' IN (\''. implode("','", $joomla_users) .'\')');
			$db->setQuery((string)$query);
			$users_in_group = $db->loadResultArray();

			// Insert the new groups into the Joomla tracker table
			$query = $db->getQuery(true);
			$query->clear();
			$query->insert('#__tracker_groups');
			$query->set('id='.$forum_groups[$i]->id);
			$query->set('name=\''.$forum_groups[$i]->title.'\'');
			$query->set('view_torrents=0');
			$query->set('edit_torrents=0');
			$query->set('delete_torrents=0');
			$query->set('upload_torrents=0');
			$query->set('download_torrents=1');
			$query->set('can_leech=0');
			$query->set('wait_time=0');
			$query->set('peer_limit=1');
			$query->set('torrent_limit=1');
			$query->set('minimum_ratio=1');
			$query->set('download_multiplier=1');
			$query->set('upload_multiplier=1');
			$query->set('view_comments=1');
			$query->set('write_comments=0');
			$query->set('edit_comments=0');
			$query->set('delete_comments=0');
			$query->set('autopublish_comments=0');
			$query->set('ordering='.$forum_groups[$i]->id);
			$query->set('state=1');
			$db->setQuery((string)$query);
			if(!$db->query()) {
				$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_GROUPS_ERROR_INSERT_GROUPS'));
				return false;
			}

			if (count($users_in_group)) {
				// Update the Users Level Table
				$query = $db->getQuery(true);
				$query->clear();
				$query->update('#__tracker_users AS tu');
				$query->join('#__users AS u ON u.id = tu.id');
				$query->set('tu.can_leech=0');
				$query->set('tu.wait_time=0');
				$query->set('tu.peer_limit=1');
				$query->set('tu.torrent_limit=1');
				$query->set('tu.group='.$forum_groups[$i]->title);
				$query->where('u.username IN (\''. implode("','", $users_in_group) .'\')');
				$db->setQuery((string)$query);
				if(!$db->query()) {
					$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_GROUPS_ERROR_UPDATE_USERGROUP'));
					return false;
				}
			}
		}
		return true;
	}

	function enable_free_leech() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// Create the temporary table that will have the old torrent multiplier values
		$query  = 'CREATE TABLE IF NOT EXISTS #__tracker_torrents_freeleech (';
		$query .= 'fid INT(11) UNSIGNED NOT NULL,';
		$query .= 'download_multiplier FLOAT(11,2) NOT NULL,';
		$query .= 'PRIMARY KEY (fid) );';
		$db->setQuery((string)$query);
		// Check if we have an error and output it
		try {
			$db->query();
		} catch (Exception $e) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_COULDNT_CREATE_TEMP_TABLE'));
			return false;
		}
		
		// If table already exists (wonder why it should but we never know...) we'll truncate it
		$query = 'TRUNCATE TABLE #__tracker_torrents_freeleech;';
		$db->setQuery((string)$query);
		// Check if we have an error and output it
		try {
			$db->query();
		} catch (Exception $e) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_COULDNT_TRUNCATE_TEMP_TABLE'));
			return false;
		}
		
		// Now we fetch the torrent id, download and upload multiplier from the original torrents table
		$query = $db->getQuery(true);
		$query->select('fid AS fid, download_multiplier AS download_multiplier');
		$query->from('#__tracker_torrents');
		$db->setQuery((string)$query);
		// Check if we have an error and output it
		try {
			$old_values = $db->loadAssocList();
		} catch (Exception $e) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_COULDNT_GET_OLD_VALUES'));
			return false;
		}

		// Insert the old multipliers into the temporary table
		$query->clear();
		$query->insert($db->quoteName('#__tracker_torrents_freeleech'));
		foreach ($old_values as $old_value) {
			$query->values(implode(',', $old_value));
		}
		$db->setQuery((string)$query);
		// Check if we have an error and output it
		try {
			$db->query();
		} catch (Exception $e) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_COULDNT_ADD_VALUES_TEMP_TABLE'));
			return false;
		}
		
		// All is good for now. We have the old multipliers saved. Let's update the torrents with multipliers = 0
		$query->clear();
		$query->update('#__tracker_torrents');
		$query->set('download_multiplier = 0');
		$query->set('flags = 2');
		$db->setQuery((string)$query);
		// Check if we have an error and output it
		try {
			$db->query();
		} catch (Exception $e) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_COULDNT_ALTER_TORRENTS_TABLE'));
			return false;
		}
		
		// Update the component parameter to know that freeleech is on
		TrackerHelper::update_parameter('freeleech', '1');
		return true;
	}
	
	function disable_free_leech() {
		$db = JFactory::getDBO();

		$query  = $db->getQuery(true);
		$query  = "UPDATE #__tracker_torrents as tt ";
		$query .= "JOIN #__tracker_torrents_freeleech as fl on tt.fid = fl.fid ";
		$query .= "SET tt.download_multiplier = fl.download_multiplier, tt.flags = 2";
		$db->setQuery((string)$query);
		try {
			$db->query();
		} catch (Exception $e) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_COULDNT_SET_ORIGINAL_VALUES'));
			return false;
		}
		
		// Finally we delete the temporary table
		$query = 'DROP TABLE IF EXISTS #__tracker_torrents_freeleech';
		$db->setQuery((string)$query);
		// Check if we have an error and output it
		try {
			$db->query();
		} catch (Exception $e) {
			$this->setError(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_COULDNT_DELETE_TEMP_TABLE'));
			return false;
		}
		
		TrackerHelper::update_parameter('freeleech', '0');
		return true;
	}

}