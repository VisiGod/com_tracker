<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');

class TrackerTableComment extends JTable {

	public function __construct(&$db) {
		parent::__construct('#__tracker_comments', 'id', $db);
	}

	public function bind($array, $ignore = '') {
		$input = JFactory::getApplication()->input;
		$task = $input->getString('task', '');
		if(($task == 'save' || $task == 'apply') && (!JFactory::getUser()->authorise('core.edit.state','com_tracker') && $array['state'] == 1)){
			$array['state'] = 0;
		}
	
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}
	
		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
		if (!JFactory::getUser()->authorise('core.admin', 'com_tracker.comment.' . $array['id'])) {
			$actions = JFactory::getACL()->getActions('com_tracker', 'comment');
			$default_actions = JFactory::getACL()->getAssetRules('com_tracker.comment.' . $array['id'])->getData();
			$array_jaccess = array();
			foreach ($actions as $action) {
				$array_jaccess[$action->name] = $default_actions[$action->name];
			}
			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}
		//Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$this->setRules($array['rules']);
		}
	
		return parent::bind($array, $ignore);
	}
	
	private function JAccessRulestoArray($jaccessrules) {
		$rules = array();
		foreach ($jaccessrules as $action => $jaccess) {
			$actions = array();
			foreach ($jaccess->getData() as $group => $allow) {
				$actions[$group] = ((bool) $allow);
			}
			$rules[$action] = $actions;
		}
		return $rules;
	}
	
	public function check() {
		//If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0) {
			$this->ordering = self::getNextOrder();
		}
		return parent::check();
	}
	
	public function publish($pks = null, $state = 1, $userId = 0) {
		// Initialise variables.
		$k = $this->_tbl_key;
	
		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;
	
		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}
	
		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);
	
		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		} else {
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}
	
		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
				'UPDATE `' . $this->_tbl . '`' .
				' SET `state` = ' . (int) $state .
				' WHERE (' . $where . ')' .
				$checkin
		);
		$this->_db->query();
	
		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			// Checkin each row.
			foreach ($pks as $pk) {
				$this->checkin($pk);
			}
		}
		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}
		$this->setError('');
		return true;
	}
	
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		return 'com_tracker.comment.' . (int) $this->$k;
	}
	
	protected function _getAssetParentId(JTable $table = null, $id = null) {
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = JTable::getInstance('Asset');
		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();
		// The item has the component as asset-parent
		$assetParent->loadByName('com_tracker');
		// Return the found asset-parent-id
		if ($assetParent->id) {
			$assetParentId = $assetParent->id;
		}
		return $assetParentId;
	}
	
	public function delete($pk = null) {
		$this->load($pk);
		$result = parent::delete($pk);
		if ($result) {
			/* DO SOMETHING HERE */
		}
		return $result;
	}

	public function store($updateNulls = false) {
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		if (!$this->id) {
			if (!(int) $this->created_time) {
				$this->created_time = $date->toSql();
			}
			if (empty($this->created_user_id)) {
				$this->created_user_id = $user->get('id');
			}
		}
		return parent::store($updateNulls);
	}
}
