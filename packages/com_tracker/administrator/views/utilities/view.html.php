<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class TrackerViewUtilities extends JViewLegacy {

	public function display($tpl = null) {
	
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
	
		$this->addToolbar();
	
		$this->sidebar = JHtmlSidebar::render();
		parent::display();
	}
	
	protected function addToolbar() {
		$user = JFactory::getUser();
	
		$bar = JToolBar::getInstance('toolbar');
	
		JToolBarHelper::title(JText::_('COM_TRACKER_UTILITIES'), 'wrench');
	
		if ($user->authorise('core.admin', 'com_tracker')) {
			JToolbarHelper::preferences('com_tracker');
		}
	}
}