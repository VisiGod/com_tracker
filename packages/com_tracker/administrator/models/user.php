<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @user			GNU General Public user version 2 or later; see USER.txt
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class TrackerModelUser extends JModelAdmin {

	protected function allowEdit($data = array(), $key = 'id') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_tracker.user.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	public function getTable($type = 'User', $prefix = 'TrackerTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_tracker.user', 'user', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;
		return $form;
	}

	public function getItem($pk = null) {
		// Initialise variables.
		$pk	= (!empty($pk)) ? (int) $pk : (int) $this->getState('user.id');
		$db			= $this->getDbo();
		$query	= $db->getQuery(true);
		$params = JComponentHelper::getParams('com_tracker');

		// Select the required fields from the table.
		$query->select('tu.*');
		$query->from('`#__tracker_users` AS tu');

		// Join the user information from the #__users table
		$query->select('u.name as name, u.username as username, u.email as email, u.block as block');
		$query->join('LEFT', '`#__users` AS u ON u.id = tu.id');

		// Get the user last IP
		$query->select('(SELECT al.ipa FROM `#__tracker_announce_log` AS al WHERE al.uid = tu.id ORDER BY al.mtime DESC LIMIT 0,1) as ipa');

		// Get the user donations and credits
		if ($params->get('enable_donations')) {
			$query->select(' (SELECT SUM(d.donated) FROM `#__tracker_donations` AS d WHERE d.uid = tu.id) as donated ');
			$query->select(' (SELECT SUM(d.credited) FROM `#__tracker_donations` AS d WHERE d.uid = tu.id) as credited ');
		}

		$query->where('tu.id = '.(int)$pk);

		$db->setQuery($query);
		$item = $db->loadObject();

		return $item;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.edit.user.data', array());
		if (empty($data)) $data = $this->getItem();
		return $data;
	}

	public function leech(&$uid, $value = 1) {
		// Initialise variables.
		$db		= $this->getDbo();
		$uid		= (array) $uid;

		if (count( $uid )) {
			JArrayHelper::toInteger($uid);
			$uids = implode( ',', $uid );

			$query	= $db->getQuery(true);
			// Let the user leech or not
			$query->update('#__tracker_users');
			$query->set('can_leech = '.(int)$value);
			$query->where('id IN ( '.$uids.' )');
			$db->setQuery($query);
			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

}