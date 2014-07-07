<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');

class TrackerTableBanHost extends JTable {

	public function __construct(&$db) {
		parent::__construct('#__tracker_deny_from_hosts', 'id', $db);
	}

	public function store($updateNulls = false) {
		// Initialise variables.
		$date = JFactory::getDate()->toSql();
		$userId = JFactory::getUser()->get('id');

		$this->created_time = $date;
		$this->created_user_id = $userId;

		// Attempt to store the data.
		return parent::store($updateNulls);
	}

	public function bind($array, $ignore = '') {
		if (isset($array['params']) && is_array($array['params'])) {
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}
 
		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}
 
		return parent::bind($array, $ignore);
	}

/*	
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_tracker.banhost.'.(int) $this->$k;
	}
 
	protected function _getAssetTitle()
	{
		return $this->cname;
	}

	protected function _getAssetParentId()
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('com_tracker');
		return $asset->id;
	}
*/
}
