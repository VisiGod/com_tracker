<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class TrackerModelGroup extends JModelAdmin {

	protected function allowEdit($data = array(), $key = 'id') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_tracker.group.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	public function getTable($type = 'Group', $prefix = 'TrackerTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_tracker.group', 'group', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;
		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.edit.group.data', array());
		if (empty($data)) $data = $this->getItem();
		return $data;
	}

	public function changeValue($gid, $task, $value) {
		// Initialise variables.
		$db		= $this->getDbo();
		$gid	= (array) $gid;

		if (count( $gid )) {
			JArrayHelper::toInteger($gid);
			$gids = implode( ',', $gid );

			$query	= $db->getQuery(true);
			// Let the user leech or not
			$query->update('#__tracker_groups');
			$query->set($task.' = '.(int)$value);
			$query->where('id IN ( '.$gids.' )');
			$db->setQuery($query);
			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}
		}
		return true;

	}

}