<?php
/**
 * @version			3.3.1-dev
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

	public function optimizetables() {
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

	public function importgroups() {
		$params = JComponentHelper::getParams( 'com_tracker' );
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

	public function enable_free_leech() {
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
		
		// Clean the cache.
		$this->cleanCache('_system', 0);
		$this->cleanCache('_system', 1);
		return true;
	}
	
	public function disable_free_leech() {
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

		// Clean the cache.
		$this->cleanCache('_system', 0);
		$this->cleanCache('_system', 1);
		return true;
	}

	public function bulk_import() {
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';
		require_once JPATH_COMPONENT_SITE.'/helpers/Torrent.php';
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams( 'com_tracker' );
		$app	= JFactory::getApplication();
		$user 	= JFactory::getUser();


		$filename = $params->get('import_filename');
		$source_folder = $params->get('import_source_folder');

		if (empty($filename) || $filename == '-1') {
			$this->setError(JText::_('COM_TRACKER_UTILITY_IMPORT_INVALID_FILE'));
			return false;
		}

		$filename = JUri::root().DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$filename;
		@$handle = fopen($filename, "r");
		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE) {
			while (($row = fgetcsv($handle, 1000, $params->get('field_separator'))) !== FALSE) {
				if(!$header) $header = $row;
				else $data[] = array_combine($header, $row);
			}
			fclose($handle);
		}
	
		foreach ($data as &$imported_torrent) {
			// Let's start to play with it
			$temp_torrent['name']		= $imported_torrent['Name'];
			$temp_torrent['categoryID']	= $imported_torrent['CategoryID'];
			$temp_torrent['description']= $imported_torrent['Description'];
			
	
			if (empty($imported_torrent['Uploader'])) $temp_torrent['uploader'] = $user->id;
			else $temp_torrent['uploader'] = $imported_torrent['Uploader'];
	
			if ($params->get('enable_licenses') == 1) $temp_torrent['licenseID'] = $imported_torrent['LicenseID'];
			else $temp_torrent['licenseID'] = 0;
			if ($params->get('forum_post_id') == 1) $temp_torrent['forum_post'] = $imported_torrent['ForumPost'];
			else $temp_torrent['forum_post'] = 0;
			if ($params->get('torrent_information') == 1) $temp_torrent['info_post'] = $imported_torrent['InfoPost'];
			else $temp_torrent['info_post'] = 0;
			if ($params->get('allow_upload_anonymous') == 1) $temp_torrent['uploader_anonymous'] = $imported_torrent['UploaderAnonymous'];
			else $temp_torrent['uploader_anonymous'] = 0;
			if ($params->get('torrent_tags') == 1) $temp_torrent['tags'] = $imported_torrent['Tags'];
			else $temp_torrent['tags'] = '';
	
			if ($params->get('freeleech') == 1) $temp_torrent['download_multiplier'] = 0;
			else $temp_torrent['download_multiplier'] = 1;
	
			// ------------------------------------------------------------------------------------------------------------------------
			// Let's take care of the .torrent file first
			$torrent_file = JPATH_ROOT.DIRECTORY_SEPARATOR.$source_folder.DIRECTORY_SEPARATOR.$imported_torrent['Filename'];
			$temp_torrent['filename'] = $imported_torrent['Filename'];
	
			// If we try to use an empty file
			if (@filesize($torrent_file) == 0) {
				$this->setError(JText::_('COM_TRACKER_UTILITY_IMPORT_TORRENT').' - '.JText::_('COM_TRACKER_UTILITY_IMPORT_EMPTY_FILE').' ( '.$imported_torrent['Filename'].' )');
				return false;
			}
	
			// Check if the torrent file is really a valid torrent file
			if (!Torrent::is_torrent($torrent_file)) {
				$this->setError(JText::_('COM_TRACKER_UTILITY_IMPORT_TORRENT').' - '.JText::_('COM_TRACKER_UTILITY_IMPORT_NOT_BENCODED_FILE').' ( '.$imported_torrent['Filename'].' )');
				return false;
			}
	
			// Let's create our new torrent object
			$torrent = new Torrent($torrent_file);
			// And check for errors. Need to find a way to test them all :)
			if ( $errors = $torrent->errors() ) var_dump( $errors );
	
			// Private Torrents
			if (($params->get('make_private') == 1) && !$torrent->is_private()) $torrent->is_private(true);
	
			// If the user didnt wrote a name for the torrent, we get it from the filename
			if (empty($temp_torrent['name'])) {
				$filename = pathinfo($torrent_file);
				$torrent->name($filename['filename']);
			}
	
			$query = $db->getQuery(true);
			$query->select('count(fid)');
			$query->from('#__tracker_torrents');
			$query->where('info_hash = UNHEX("'.$torrent->hash_info().'")');
			$db->setQuery($query);
			if ($db->loadResult() > 0) {
				$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_ALREADY_EXISTS').' - '.$imported_torrent['Filename']);
				return false;
			}
	
			// ------------------------------------------------------------------------------------------------------------------------
			// The .torrent file is valid, let's continue to our image file (if we choose to use it)
			$image_type = $imported_torrent['ImageType'];
			if ($params->get('use_image_file')) {
				// When image_type is 'don't use image'
				if ($image_type == 0) {
					$image_file_query_value = "";
				}
	
				// When image file is 'uploaded file'
				if ($image_type == 1) {
					$image_file = JPATH_ROOT.DIRECTORY_SEPARATOR.$source_folder.DIRECTORY_SEPARATOR.$imported_torrent['Image'];
					if (!is_file($image_file)) {
						$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_OPS_SOMETHING_HAPPENED_IMAGE').' - '.$imported_torrent['Image']);
						return false;
					}
	
					if (!filesize($image_file)) {
						$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_EMPTY_FILE_IMAGE').' - '.$imported_torrent['Image']);
						return false;
					}
	
					if (!TrackerHelper::is_image($image_file)) {
						$this->setError(JText::_( 'COM_TRACKER_UTILITY_IMPORT_NOT_AN_IMAGE_FILE').' - '.$imported_torrent['Image']);
						return false;
					}
	
					$image_file_extension = end(explode(".", $image_file));
					$image_file_query_value = $torrent->hash_info().'.'.$image_file_extension;
					$image_file_file = $image_file;
				}
	
				// When image file is an external link
				if ($image_type == 2) {
						
					// If the remote file is unavailable
					if(@!file_get_contents($imported_torrent['Image'],0,NULL,0,1)) {
						echo JText::_('COM_TRACKER_UTILITY_IMPORT_REMOTE_IMAGE_INVALID_FILE');
						continue 3;
					}
						
					// check if the remote file is not an image
					if (!is_array(@getimagesize($imported_torrent['Image']))) {
						echo JText::_('COM_TRACKER_UTILITY_IMPORT_REMOTE_IMAGE_NOT_IMAGE');
						continue 3;
					}
						
					$image_file_query_value = $imported_torrent['Image'];
				}
			} else {
				$image_file_query_value = "";
			}
	
			// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			// All is good, let's insert the record in the database
			// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Insert the torrent into the table
			$query->clear();
			$query = $db->getQuery(true);
			$query->insert('#__tracker_torrents');
			$query->set('info_hash = UNHEX("'.$torrent->hash_info().'")');
			$query->set('ctime = unix_timestamp()');
			$query->set('name = '.$db->quote($temp_torrent['name']));
			$query->set('alias = '.$db->quote($temp_torrent['name']));
			$query->set('filename = '.$db->quote($temp_torrent['filename']));
			$query->set('description = '.$db->quote($temp_torrent['description']));
			$query->set('categoryID = '.$db->quote($temp_torrent['categoryID']));
			$query->set('size = '.$db->quote($torrent->size()));
			$query->set('created_time = '.$db->quote(date("Y-m-d H:i:s")));
			$query->set('uploader = '.$db->quote($user->id));
			$query->set('number_files = '.$db->quote(count($torrent->content())));
			$query->set('uploader_anonymous = '.$db->quote($temp_torrent['uploader_anonymous']));
			$query->set('forum_post = '.$db->quote($temp_torrent['forum_post']));
			$query->set('info_post = '.$db->quote($temp_torrent['info_post']));
			$query->set('licenseID = '.$db->quote($temp_torrent['licenseID']));
			$query->set('upload_multiplier = 1');
			$query->set('download_multiplier = '.$db->quote($temp_torrent['download_multiplier']));
			$query->set('image_file = '.$db->quote($image_file_query_value));
			$query->set('tags = '.$db->quote($temp_torrent['tags']));
			$query->set('state = 1');
			$db->setQuery($query);
			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
			}
	
			// Get the torrent ID that we've just inserted in the database
			$torrent_id = $db->insertid();
	
			// Insert the list of files of the torrent in the database
			foreach ($torrent->content() as $filename => $filesize) {
				$query->clear();
				$query = $db->getQuery(true);
				$query->insert('#__tracker_files_in_torrents');
				$query->set('torrentID = '.$db->quote($torrent_id));
				$query->set('filename = '.$db->quote($filename));
				$query->set('size = '.$db->quote($filesize));
				$db->setQuery($query);
				if (!$db->query()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
	
			// If we're in freeleech we need to add the record of the new torrent to the freeleech table
			if ($params->get('freeleech') == 1) {
				$query->clear();
				$query = $db->getQuery(true);
				$query->insert('#__tracker_torrents_freeleech');
				$query->set('fid = '.$db->quote($torrent_id));
				$query->set('download_multiplier = 1');
				$db->setQuery($query);
				if (!$db->query()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
	
			$upload_error = 0;
			// Lets try to save the torrent before we continue
			if (!copy($torrent_file, JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrent_id."_".$temp_torrent['filename'])) {
				$upload_error = 1;
				echo JText::_('COM_TRACKER_UTILITY_IMPORT_COULDNT_COPY_TORRENT');
				continue;
			} else unlink($torrent_file);
	
			// And we should also move the image file if we're using it with the option of uploading an image file
			if ($params->get('use_image_file') && $imported_torrent['ImageType'] == 1) {
				if (!copy($image_file, JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$image_file_query_value)) {
					$upload_error = 1;
					echo JText::_('COM_TRACKER_UTILITY_IMPORT_COULDNT_COPY_TORRENT_IMAGE');
					continue 2;
				} else unlink($image_file);
			}
	
			if ($upload_error == 1) {
				$query->clear();
				$query = $db->getQuery(true);
				$query->delete('#__tracker_files_in_torrents');
				$query->where('torrent='.$db->quote($torrent_id));
				$db->setQuery($query);
				$db->query();
				if ($error = $db->getErrorMsg()) {
					$this->setError($error);
					return false;
				}
				$query->clear();
				$query = $db->getQuery(true);
				$query->delete('#__tracker_torrents');
				$query->where('fid='.$db->quote($torrent_id));
				$db->setQuery($query);
				$db->query();
				@unlink(JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrent_id."_".$temp_torrent['filename']);
				if ($image_type == 1) @unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$image_file_query_value);
				continue;
			}
			JFactory::getApplication()->enqueueMessage(JText::_('COM_TRACKER_UTILITY_IMPORT_TORRENT').' \''.$temp_torrent['filename'].'\' '.JText::_('COM_TRACKER_UTILITY_IMPORT_TORRENT_SUCCESS').'<br>');
		}
		return true;
	}
	
}