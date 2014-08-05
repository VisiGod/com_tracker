<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

// Execute the task.
$controller	= JControllerLegacy::getInstance('Tracker');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
