<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewSetting extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;
	
	public function display($tpl = null) {

		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_tracker' );
	
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
		JToolBarHelper::title(JText::_('COM_TRACKER_SETTINGS'), 'tools');
	
		JToolBarHelper::save('setting.save');
		JToolbarHelper::cancel('setting.cancel');
	}
}
