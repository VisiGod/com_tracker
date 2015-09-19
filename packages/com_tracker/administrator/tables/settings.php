<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class TrackerTableSettings extends JTable {

	public function __construct(&$db) {
		parent::__construct('xbt_config', 'name', $db);
	}

	public function bind($array, $ignore = '') {
		return parent::bind($array, $ignore);
	}

}
