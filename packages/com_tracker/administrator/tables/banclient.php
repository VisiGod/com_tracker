<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.database.table');

class TrackerTableBanClient extends JTable {

	public function __construct(&$db) {
		parent::__construct('#__tracker_deny_from_clients', 'id', $db);
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
		if (!JFactory::getUser()->authorise('core.admin', 'com_tracker.banclient.' . $array['id'])) {
			$actions = JFactory::getACL()->getActions('com_tracker', 'banclient');
			$default_actions = JFactory::getACL()->getAssetRules('com_tracker.banclient.' . $array['id'])->getData();
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
	
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		return 'com_tracker.banclient.' . (int) $this->$k;
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
