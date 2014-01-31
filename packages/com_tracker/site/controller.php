<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class TrackerController extends JControllerLegacy {

	public function display($cachable = false, $urlparams = false) {
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/tracker.php';

		$safeurlparams = array(
			'fid'=>'INT',
			'limit'=>'INT',
			'limitstart'=>'INT',
			'return'=>'BASE64',
			'filter'=>'STRING',
			'filter_order'=>'CMD',
			'filter_order_Dir'=>'CMD',
			'filter-search'=>'STRING',
			'print'=>'BOOLEAN',
			'lang'=>'CMD'
		);

		// call parent behavior
		return parent::display($cachable, $safeurlparams);
	}
	
}
