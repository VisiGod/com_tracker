<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @group			GNU General Public GROUP version 2 or later; see GROUP.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewGroup extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null) {

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
		
		$canDo		= JHelperContent::getActions('com_tracker', 'group', $this->item->id);
		
		JToolBarHelper::title(JText::_('COM_TRACKER_GROUPS'), 'users');
		
		// If not checked out, can save the item.
		if (($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0)) {
			JToolBarHelper::apply('group.apply');
			JToolBarHelper::save('group.save');
		}

		if ($canDo->get('core.create')) {
			JToolBarHelper::custom('group.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('group.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id)) {
			JToolbarHelper::cancel('group.cancel');
		} else {
			JToolbarHelper::cancel('group.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
