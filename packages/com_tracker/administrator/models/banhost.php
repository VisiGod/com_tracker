<?php
/**
 * @version		2.5.12-dev
 * @package		Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class TrackerModelbanhost extends JModelAdmin {

	protected function allowEdit($data = array(), $key = 'id') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_tracker.banhost.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	
	public function getTable($type = 'BanHost', $prefix = 'TrackerTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_tracker.banhost', 'banhost', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;
		return $form;
	}
	
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.edit.banhost.data', array());
		if (empty($data)) $data = $this->getItem();
		return $data;
	}

	public function save($data) {

		//$data['begin'] = sprintf("%u", ip2long(long2ip(ip2long($data['begin']))));
		//$data['end'] = sprintf("%u", ip2long(long2ip(ip2long($data['end']))));
		
		$data['begin'] = sprintf("%010u", ip2long($data['begin']));
		$data['end'] = sprintf("%010u", ip2long($data['end']));


		parent::save($data);
	}

}