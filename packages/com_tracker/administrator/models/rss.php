<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modeladmin');

class TrackerModelRSS extends JModelAdmin {

	protected function allowEdit($data = array(), $key = 'id') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_tracker.rss.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	public function getTable($type = 'RSS', $prefix = 'TrackerTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_tracker.rss', 'rss', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;
		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.edit.rss.data', array());
		if (empty($data)) $data = $this->getItem();

		// Get the values for the rss authentication
		if (is_array($data) && $data['rss_authentication'] == 2){
			$data['rss_authentication_group'] = explode(',',$data['rss_authentication_items']);
		} elseif (is_object($data) && $data->rss_authentication == 2) {
			$data->rss_authentication_group = explode(',',$data->rss_authentication_items);
		}

		// Get the values for the rss type (categories)
		if (is_array($data) && $data['rss_type'] == 1){
			$data['rss_type_category'] = explode(',',$data['rss_type_items']);
		} elseif (is_object($data) && $data->rss_type == 1) {
			$data->rss_type_category = explode(',',$data->rss_type_items);
		} elseif (is_array($data) && $data['rss_type'] == 2){
			$data['rss_type_license'] = explode(',',$data['rss_type_items']);
		} elseif (is_object($data) && $data->rss_type == 2) {
			$data->rss_type_license = explode(',',$data->rss_type_items);
		} 

		return $data;
	}

	public function save($data) {
		if ($data['rss_authentication'] == 2) $data['rss_authentication_items'] = implode(',', $data['rss_authentication_group']);
		if ($data['rss_type'] == 1) $data['rss_type_items'] = implode(',', $data['rss_type_category']);
		if ($data['rss_type'] == 2) $data['rss_type_items'] = implode(',', $data['rss_type_license']);
		return parent::save($data);
	}

}