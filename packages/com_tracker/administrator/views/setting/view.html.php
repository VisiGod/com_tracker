<?php
/**
 * @version			2.5.12-dev
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

		// Define static configuration items
		$item['log_announce'] 			= 1;

		// initialize values if they do not exist
		// --------- XBT CONFIGURATION --------- 
		if (!isset($item['announce_interval']))		$item['announce_interval'] = 1800;
		if (!isset($item['clean_up_interval']))		$item['clean_up_interval'] = 300;
		if (!isset($item['daemon']))				$item['daemon'] 		= 1;
		if (!isset($item['debug']))					$item['debug'] 		= 0;
		if (!isset($item['full_scrape']))			$item['full_scrape'] 		= 0;
		if (!isset($item['gzip_scrape']))			$item['gzip_scrape'] 		= 1;
		if (!isset($item['listen_ipa']))			$item['listen_ipa'] 		= '0.0.0.0';
		if (!isset($item['listen_port']))			$item['listen_port'] 		= 2710;
		if (!isset($item['log_access']))			$item['log_access'] 		= 0;
		if (!isset($item['log_scrape']))			$item['log_scrape'] 		= 0;
		if (!isset($item['offline_message']))		$item['offline_message'] 		= '';
		if (!isset($item['pid_file']))				$item['pid_file'] 		= 'xbt_tracker.pid';
		if (!isset($item['query_log']))				$item['query_log'] 		= 'xbt_tracker_query.log';;
		if (!isset($item['read_config_interval'])) 	$item['read_config_interval'] 		= 180;
		if (!isset($item['read_db_interval']))		$item['read_db_interval'] 		= 300;
		if (!isset($item['redirect_url']))			$item['redirect_url'] 		= $app->getCfg('livesite');
		if (!isset($item['scrape_interval']))		$item['scrape_interval'] 		= 1800;
		if (!isset($item['write_db_interval']))		$item['write_db_interval'] 		= 300;
		// --------- XBT CONFIGURATION --------- 
		$item['announce_interval'] 		= (int) $item['announce_interval'];
		$item['clean_up_interval'] 		= (int) $item['clean_up_interval'];
		$item['daemon'] 				= JHTML::_('select.booleanlist',  'daemon', 'class="inputbox" size="1"', $item['daemon'] );
		$item['debug'] 					= JHTML::_('select.booleanlist',  'debug', 'class="inputbox" size="1"', $item['debug'] );
		$item['full_scrape'] 			= JHTML::_('select.booleanlist',  'full_scrape', 'class="inputbox" size="1"', $item['full_scrape'] );
		$item['gzip_scrape'] 			= JHTML::_('select.booleanlist',  'gzip_scrape', 'class="inputbox" size="1"', $item['gzip_scrape'] );
		$item['listen_ipa'] 			= $item['listen_ipa'];
		$item['listen_port'] 			= (int) $item['listen_port'];
		$item['log_access'] 			= JHTML::_('select.booleanlist',  'log_access', 'class="inputbox" size="1"', $item['log_access'] );
		$item['log_scrape'] 			= JHTML::_('select.booleanlist',  'log_scrape', 'class="inputbox" size="1"', $item['log_scrape'] );
		$item['offline_message'] 		= $item['offline_message'];
		$item['pid_file'] 				= $item['pid_file'];
		$item['query_log'] 				= $item['query_log'];
		$item['read_config_interval'] 	= (int) $item['read_config_interval'];
		$item['read_db_interval'] 		= (int) $item['read_db_interval'];
		$item['redirect_url'] 			= $item['redirect_url'];
		$item['scrape_interval'] 		= (int) $item['scrape_interval'];
		$item['write_db_interval'] 		= (int) $item['write_db_interval'];
 
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
