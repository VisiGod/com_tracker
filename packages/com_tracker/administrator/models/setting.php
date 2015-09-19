<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
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
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('name, value');
		$query->from('`xbt_config`');
		$db->setQuery($query);
		$items = $db->loadObjectList();

		// Need to convert the XBT Config database to a JObject
		$config = new JObject;
		foreach ($items as $item) {
			$config->set($item->name, $item->value);
		}
		
		return $config;
	}

	public function save($data) {
		$db = JFactory::getDBO();
		$data = JRequest::get();

		// clear old config values
		$query = $db->getQuery(true);
		$query->clear();
		$query = 'TRUNCATE xbt_config';
		$db->setQuery((string)$query);
		if(!$db->execute()) return false;


		// Insert the new config values
		$query = $db->getQuery(true);
		$query->clear();
		foreach($data['jform'] as $name => $value) {
			$query = "INSERT INTO xbt_config ( name, value ) VALUES ('" . $name . "', '" . $value . "' );";
			$db->setQuery($query);
			if(!$db->execute()) return false;
		}
		return true;
	}

}