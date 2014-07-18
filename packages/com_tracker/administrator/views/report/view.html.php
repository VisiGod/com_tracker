<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class TrackerViewReport extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null) {

		$params = JComponentHelper::getParams( 'com_tracker' );
		if ($params->get('enable_reporttorrent') == 0) {
			$app		= JFactory::getApplication();
			$app->redirect('index.php?option=com_tracker', JText::_('COM_TRACKER_REPORTS_NOT_ENABLE'), 'error');
			return false;
		}

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
		
		$canDo		= JHelperContent::getActions('com_tracker', 'report', $this->item->id);
		
		JToolBarHelper::title(JText::_('COM_TRACKER_REPORTS'), 'warning-2');
		
		// If not checked out, can save the item.
		if (($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0)) {
			JToolBarHelper::apply('report.apply');
			JToolBarHelper::save('report.save');
		}
		
		if ($canDo->get('core.create')) {
			JToolBarHelper::custom('report.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('report.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		
		if (empty($this->item->id)) {
			JToolbarHelper::cancel('report.cancel');
		} else {
			JToolbarHelper::cancel('report.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
