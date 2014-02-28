<?php
/**
 * @version			2.5.13-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class TrackerModelSetting extends JModelAdmin {

	protected function allowEdit($data = array(), $key = 'name') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_tracker.setting.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	
	public function getTable($type = 'Settings', $prefix = 'TrackerTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_tracker.setting', 'setting', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;
		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.edit.setting.data', array());
		if (empty($data)) $data = $this->getItem();
		return $data;
	}

	public function getItem($pk = null) {
		$db			= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('name, value');
		$query->from('`xbt_config`');
		$db->setQuery($query);
		$data = $db->loadObjectList();

		return $data;
	}

	public function save($data) {
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_tracker' );
		$db = JFactory::getDBO();
		$data = JRequest::get();

		$settings = array();
		$settings['announce_interval'] 				= (int)$data['announce_interval'];
		$settings['anonymous_announce'] 			= 0;
		$settings['anonymous_scrape'] 				= 0;
		$settings['auto_register'] 					= 0;
		$settings['clean_up_interval'] 				= (int)$data['clean_up_interval'];
		$settings['daemon'] 						= (int)$data['daemon'];
		$settings['debug'] 							= (int)$data['debug'];
		$settings['full_scrape'] 					= (int)$data['full_scrape'];
		$settings['gzip_scrape']					= (int)$data['gzip_scrape'];
		if (empty($data['listen_ipa'])) 			$settings['listen_ipa'] = '0.0.0.0';
			else 									$settings['listen_ipa'] = $data['listen_ipa'];
		$settings['listen_port'] 					= (int)$data['listen_port'];
		$settings['log_access'] 					= (int)$data['log_access'];
		$settings['log_announce'] 					= 1;
		$settings['log_scrape'] 					= (int)$data['log_scrape'];
		$settings['offline_message'] 				= addslashes($data['offline_message']);
		$settings['pid_file'] 						= $data['pid_file'];
		$settings['query_log'] 						= $data['query_log'];
		$settings['read_config_interval'] 			= (int)$data['read_config_interval'];
		$settings['read_db_interval'] 				= (int)$data['read_db_interval'];
		$settings['redirect_url'] 					= $data['redirect_url'];
		$settings['scrape_interval'] 				= (int)$data['scrape_interval'];
		$settings['write_db_interval'] 				= (int)$data['write_db_interval'];
		$settings['table_announce_log'] 			= $app->getCfg('dbprefix', 1).'tracker_announce_log';
		$settings['table_files'] 					= $app->getCfg('dbprefix', 1).'tracker_torrents';
		$settings['table_files_users'] 				= $app->getCfg('dbprefix', 1).'tracker_files_users';
		$settings['table_scrape_log'] 				= $app->getCfg('dbprefix', 1).'tracker_scrape_log';
		$settings['table_users'] 					= $app->getCfg('dbprefix', 1).'tracker_users';
		$settings['column_files_completed'] 		= 'completed';
		$settings['column_files_fid'] 				= 'fid';
		$settings['column_files_leechers'] 			= 'leechers';
		$settings['column_files_seeders'] 			= 'seeders';
		$settings['column_users_uid'] 				= 'id';
		$settings['torrent_pass_private_key'] 		= $data['torrent_pass_private_key'];
		if ($params->get('peer_banning')) {
			$settings['table_deny_from_clients'] 	= $app->getCfg('dbprefix', 1).'tracker_deny_from_clients';
		}
		if ($params->get('host_banning')) {
			$settings['table_deny_from_hosts'] 	= $app->getCfg('dbprefix', 1).'tracker_deny_from_hosts';
		}

		// clear old config values
		$query = $db->getQuery(true);
		$query->clear();
		$query = 'TRUNCATE xbt_config';
		$db->setQuery((string)$query);
		if(!$db->query()) return false;


		// Insert the new config values
		$query = $db->getQuery(true);
		$query->clear();
		foreach($settings as $name => $value) {
			$query = "INSERT INTO xbt_config ( name, value ) VALUES ('" . $name . "', '" . $value . "' );";
			$db->setQuery($query);
			if(!$db->query()) return false;
		}
		return true;
	}

}