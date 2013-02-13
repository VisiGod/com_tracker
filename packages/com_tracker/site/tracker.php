<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Execute the task.
$controller	= JControllerLegacy::getInstance('Tracker');
$controller->execute(JRequest::getVar('task'));
$controller->redirect();
