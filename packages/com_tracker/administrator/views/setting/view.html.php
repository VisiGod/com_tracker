<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewSetting extends JView {

	public function display($tpl = null) {

		$params = JComponentHelper::getParams( 'com_tracker' );
		$app = JFactory::getApplication();

		// get the Data
		$items = $this->get('Item');
		$item = array();
		foreach ($items as $names) {
   		$item[$names->name] = $names->value;
		}

		// Check for errors
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// initialize values if they do not exist
		// --------- XBT CONFIGURATION --------- 
		if (!isset($item['announce_interval']))				$item['announce_interval'] = 1800;
		if (!isset($item['anonymous_announce']))				$item['anonymous_announce'] = 0;
		if (!isset($item['anonymous_scrape']))					$item['anonymous_scrape'] = 0;
		if (!isset($item['auto_register']))						$item['auto_register'] = 0;
		if (!isset($item['clean_up_interval']))				$item['clean_up_interval'] = 300;
		if (!isset($item['daemon']))										$item['daemon'] 		= 1;
		if (!isset($item['debug']))										$item['debug'] 		= 0;
		if (!isset($item['full_scrape']))							$item['full_scrape'] 		= 0;
		if (!isset($item['gzip_scrape']))							$item['gzip_scrape'] 		= 1;
		if (!isset($item['listen_ipa']))								$item['listen_ipa'] 		= '0.0.0.0';
		if (!isset($item['listen_port']))							$item['listen_port'] 		= 2710;
		if (!isset($item['log_access']))								$item['log_access'] 		= 0;
		if (!isset($item['log_announce']))							$item['log_announce'] 		= 1;
		if (!isset($item['log_scrape']))								$item['log_scrape'] 		= 0;
		if (!isset($item['offline_message']))					$item['offline_message'] 		= '';
		if (!isset($item['pid_file']))									$item['pid_file'] 		= 'xbt_tracker.pid';
		if (!isset($item['query_log']))								$item['query_log'] 		= 'xbt_tracker_query.log';;
		if (!isset($item['read_config_interval'])) 		$item['read_config_interval'] 		= 180;
		if (!isset($item['read_db_interval']))					$item['read_db_interval'] 		= 300;

		if (!isset($item['redirect_url']))							$item['redirect_url'] 		= $app->getCfg('livesite');

		if (!isset($item['scrape_interval']))					$item['scrape_interval'] 		= 1800;
		if (!isset($item['write_db_interval']))				$item['write_db_interval'] 		= 300;
		// --------- TABLE CONFIGURATION --------- 
		if (!isset($item['table_announce_log']))				$item['table_announce_log'] 		= $app->getCfg('dbprefix', 1).'tracker_announce_log';
		if ($params->get('host_banning')) {
			if (!isset($item['table_deny_from_hosts'])) 		$item['table_deny_from_hosts'] 		= $app->getCfg('dbprefix', 1).'tracker_deny_from_hosts';
		}
		if ($params->get('peer_banning')) {
			if (!isset($item['table_deny_from_clients'])) 	$item['table_deny_from_clients'] 		= $app->getCfg('dbprefix', 1).'tracker_deny_from_clients';
		}
		if (!isset($item['table_files']))							$item['table_files'] 		= $app->getCfg('dbprefix', 1).'tracker_files';
		if (!isset($item['table_files_users']))				$item['table_files_users'] 		= $app->getCfg('dbprefix', 1).'tracker_files_users';
		if (!isset($item['table_scrape_log']))					$item['table_scrape_log'] 		= $app->getCfg('dbprefix', 1).'tracker_scrape_log';
		if (!isset($item['table_users']))							$item['table_users'] 		= $app->getCfg('dbprefix', 1).'tracker_users';
		if (!isset($item['column_files_completed']))		$item['column_files_completed'] 		= 'completed';
		if (!isset($item['column_files_fid']))					$item['column_files_fid'] 		= 'fid';
		if (!isset($item['column_files_leechers'])) 		$item['column_files_leechers'] 		= 'leechers';
		if (!isset($item['column_files_seeders'])) 		$item['column_files_seeders'] 		= 'seeders';
		if (!isset($item['column_users_uid']))					$item['column_users_uid'] 		= 'id';
		if (!isset($item['torrent_pass_private_key']))	$item['torrent_pass_private_key'] 		= substr(md5(uniqid(rand(), true)), 0, 27);
		// --------- XBT CONFIGURATION --------- 
		$item['announce_interval'] 				= (int) $item['announce_interval'];
		$item['anonymous_announce'] 			= JHTML::_('select.booleanlist',  'anonymous_announce', 'class="inputbox" size="1"', $item['anonymous_announce'] );
		$item['anonymous_scrape'] 				= JHTML::_('select.booleanlist',  'anonymous_scrape', 'class="inputbox" size="1"', $item['anonymous_scrape'] );
		$item['auto_register'] 						= JHTML::_('select.booleanlist',  'auto_register', 'class="inputbox" size="1"', $item['auto_register'] );
		$item['clean_up_interval'] 				= (int) $item['clean_up_interval'];
		$item['daemon'] 									= JHTML::_('select.booleanlist',  'daemon', 'class="inputbox" size="1"', $item['daemon'] );
		$item['debug'] 										= JHTML::_('select.booleanlist',  'debug', 'class="inputbox" size="1"', $item['debug'] );
		$item['full_scrape'] 							= JHTML::_('select.booleanlist',  'full_scrape', 'class="inputbox" size="1"', $item['full_scrape'] );
		$item['gzip_scrape'] 							= JHTML::_('select.booleanlist',  'gzip_scrape', 'class="inputbox" size="1"', $item['gzip_scrape'] );
		$item['listen_ipa'] 							= $item['listen_ipa'];
		$item['listen_port'] 							= (int) $item['listen_port'];
		$item['log_access'] 							= JHTML::_('select.booleanlist',  'log_access', 'class="inputbox" size="1"', $item['log_access'] );
		$item['log_scrape'] 							= JHTML::_('select.booleanlist',  'log_scrape', 'class="inputbox" size="1"', $item['log_scrape'] );
		$item['offline_message'] 					= $item['offline_message'];
	  $item['pid_file'] 								= $item['pid_file'];
		$item['query_log'] 								= $item['query_log'];
	  $item['read_config_interval'] 		= (int) $item['read_config_interval'];
	  $item['read_db_interval'] 				= (int) $item['read_db_interval'];
	  $item['redirect_url'] 						= $item['redirect_url'];
	  $item['scrape_interval'] 					= (int) $item['scrape_interval'];
		$item['write_db_interval'] 				= (int) $item['write_db_interval'];
		// --------- TABLE CONFIGURATION --------- 
		$item['table_announce_log'] 			= $item['table_announce_log'];
		if ($params->get('host_banning')) {
			$item['table_deny_from_hosts'] 		= $item['table_deny_from_hosts'];
		}
		if ($params->get('peer_banning')) {
			$item['table_deny_from_clients'] 	= $item['table_deny_from_clients'];
		}
		$item['table_files'] 							= $item['table_files'];
		$item['table_files_users'] 				= $item['table_files_users'];
		$item['table_scrape_log'] 				= $item['table_scrape_log'];
		$item['table_users'] 							= $item['table_users'];
		$item['column_files_completed'] 	= $item['column_files_completed'];
		$item['column_files_fid'] 				= $item['column_files_fid'];
		$item['column_files_leechers'] 		= $item['column_files_leechers'];
		$item['column_files_seeders'] 		= $item['column_files_seeders'];
		$item['column_users_uid'] 				= $item['column_users_uid'];
		$item['torrent_pass_private_key'] = $item['torrent_pass_private_key'];
 
 		// Assign the Data
		$this->item 	= $item;
		$this->params = $params;

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
	}

	protected function addToolbar() {

		JRequest::setVar('hidemainmenu', true);
		//$user = JFactory::getUser();
		//$userId = $user->id;
		
		JToolBarHelper::title(JText::_('COM_TRACKER_SETTINGS'), 'settings.png');
		JToolBarHelper::save('setting.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::cancel('setting.cancel', 'JTOOLBAR_CANCEL');
	}
}
