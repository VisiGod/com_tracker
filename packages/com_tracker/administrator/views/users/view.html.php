<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewUsers extends JView {

	public function display($tpl = null) {

		// Get data from the model
		$state			= $this->get('State');
		$items 			= $this->get('Items');
		$pagination 	= $this->get('Pagination');

		$user = JFactory::getUser();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Assign data to the view
		$this->state = $state;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->user = $user;

		// Set the toolbar
		$this->addToolbar();

		// Display the template
		parent::display($tpl);
	}

	protected function addToolbar() {
		$canDo = TrackerHelper::getActions();
		JToolBarHelper::title(JText::_('COM_TRACKER_USERS'), 'users');

		if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
			JToolBarHelper::editList('user.edit','JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::custom('users.unblock', 'publish.png', 'publish_f2.png','unblock', true);
			JToolBarHelper::custom('users.block', 'unpublish.png', 'unpublish_f2.png', 'block', true);
			JToolBarHelper::divider();
			JToolBarHelper::custom('users.leech', 'publish.png', 'publish_f2.png','leech', true);
			JToolBarHelper::custom('users.unleech', 'unpublish.png', 'unpublish_f2.png', 'unleech', true);
		}
        
		if ($canDo->get('core.admin')) {
	    JToolBarHelper::divider();
			JToolBarHelper::preferences('com_tracker');
		}
	}
}
