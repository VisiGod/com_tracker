<?php
/**
 * @version			2.5.13-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_tracker')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// import joomla controller library
jimport('joomla.application.component.controller');

// require helper file
JLoader::register('TrackerHelper', dirname(__FILE__) . '/helpers/tracker.php');

// Get an instance of the controller prefixed by Tracker
$controller	= JController::getInstance('Tracker');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
