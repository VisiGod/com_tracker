<?php
/**
 * @version			2.5.13-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @user			GNU General Public user version 2 or later; see USER.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewUser extends JView {

	public function display($tpl = null) {

		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');

		// Check for errors
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form 	= $form;
		$this->item 	= $item;

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);

	}

	protected function addToolbar() {
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
    $checkedOut = false;
		$canDo		= TrackerHelper::getActions();

		JToolBarHelper::title(JText::_('COM_TRACKER_USERS'), 'users');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create')))) {
			JToolBarHelper::apply('user.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('user.save', 'JTOOLBAR_SAVE');
		}

		if (empty($this->item->id)) {
			JToolBarHelper::cancel('user.cancel', 'JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('user.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}