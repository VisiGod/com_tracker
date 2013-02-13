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

class TrackerViewGroups extends JView {

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
		$params =& JComponentHelper::getParams( 'com_tracker' );
		JToolBarHelper::title(JText::_('COM_TRACKER_GROUPS'), 'groups');

		if ($canDo->get('core.create') && !$params->get('forum_integration')) {
			JToolBarHelper::addNew('group.add','JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
			JToolBarHelper::editList('group.edit','JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::custom('groups.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('groups.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
		
		if ($canDo->get('core.delete') && !$params->get('forum_integration')) {
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'groups.delete','JTOOLBAR_DELETE');
		}
				
		if ($canDo->get('core.admin')) {
	    JToolBarHelper::divider();
			JToolBarHelper::preferences('com_tracker');
		}

	}
}
