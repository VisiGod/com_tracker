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

class TrackerController extends JControllerLegacy {

	public function display($cachable = false, $urlparams = false) {
		require_once JPATH_COMPONENT.'/helpers/tracker.php';

		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'trackerpanel'));

		// Load the submenu.
		TrackerHelper::addSubmenu(JRequest::getCmd('view', 'trackerpanel'));
		parent::display();
		
		return $this;
	}
}
