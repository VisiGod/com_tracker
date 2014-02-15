<?php
/**
 * @version			2.5.12-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// import Joomla controller library
jimport('joomla.application.component.controller');

class TrackerController extends JController {

	public function display($cachable = false, $urlparams = false) {
		require_once JPATH_COMPONENT.'/helpers/tracker.php';

		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'trackerpanel'));

		// Load the submenu.
		TrackerHelper::addSubmenu(JRequest::getCmd('view', 'trackerpanel'));

		// call parent behavior
		return parent::display($cachable);
	}
}
