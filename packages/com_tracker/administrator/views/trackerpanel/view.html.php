<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class TrackerViewTrackerPanel extends JViewLegacy {

	public function display($cachable = false, $urlparams = false) {
		
		$component_xml	=	JApplicationHelper::parseXMLInstallFile( JPATH_ADMINISTRATOR .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_tracker' .DIRECTORY_SEPARATOR. 'tracker.xml' );
		JToolBarHelper::title(JText::_('COM_TRACKER_CONTROL_PANEL'), 'home-2');
		$this->assignRef('component_info', $component_xml);

		// Set the toolbar
		$this->addToolbar();

		// Display the template
		parent::display();

		// Set the document
		$this->setDocument();
	}

	protected function addToolbar() {
		$canDo = TrackerHelper::getActions();
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_tracker');
		}
	}

	protected function setDocument() {
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_TRACKER'));
	}
}