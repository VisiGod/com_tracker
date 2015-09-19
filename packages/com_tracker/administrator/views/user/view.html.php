<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage		com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public user version 2 or later; see USER.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewUser extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null) {

		JLoader::register('JTableUser', JPATH_LIBRARIES.DIRECTORY_SEPARATOR.'joomla'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'table'.DIRECTORY_SEPARATOR.'user.php');

		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display();
	}

	protected function addToolbar() {
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);

		$canDo = JHelperContent::getActions('com_users');
		
		JToolBarHelper::title(JText::_('COM_TRACKER_USER'), 'user');
		
		// If not checked out, can save the item.
		if (($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0)) {
			JToolBarHelper::apply('user.apply');
			JToolBarHelper::save('user.save');
		}
		
		if (empty($this->item->id)) {
			JToolbarHelper::cancel('user.cancel');
		} else {
			JToolbarHelper::cancel('user.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}