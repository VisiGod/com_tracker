<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_tracker')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

jimport('joomla.application.component.controller');

// require helper file
JLoader::register('TrackerHelper', dirname(__FILE__) . '/helpers/tracker.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Tracker');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();