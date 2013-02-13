<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewSettings extends JViewLegacy {

	public function display($tpl = null) {

		// Get data from the model
		$data		= $this->get('Items');

		// Workaround to remove the objects from the array
		foreach ($data as $names) {
			$items[$names->name] = $names->value;
		}

		$user = JFactory::getUser();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Assign data to the view
		$this->items = $items;
		$this->user = $user;
		
		// Set the toolbar
		$this->addToolbar();

		// Display the template
		parent::display($tpl);
	}

	protected function addToolbar() {
		$canDo = TrackerHelper::getActions();
		JToolBarHelper::title(JText::_('COM_TRACKER_SETTINGS'), 'settings.png');

		if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
			JToolBarHelper::editList('setting.edit','JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.admin')) {
	    JToolBarHelper::divider();
			JToolBarHelper::preferences('com_tracker');
		}
	}
}