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

class TrackerViewUtilities extends JView {

	public function display($tpl = null) {

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolbar();

		// Display the template
		parent::display($tpl);
	}


	protected function addToolbar() {
		$canDo = TrackerHelper::getActions();
		JToolBarHelper::title(JText::_('COM_TRACKER_UTILITIES'), 'utilities');

		if ($canDo->get('core.admin')) {
	    JToolBarHelper::divider();
			JToolBarHelper::preferences('com_tracker');
		}
	}
}