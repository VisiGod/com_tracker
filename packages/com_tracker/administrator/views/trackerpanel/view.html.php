<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class TrackerViewTrackerPanel extends JView {

	public function display($tpl = null) {
		
		$component_xml	=	JApplicationHelper::parseXMLInstallFile( JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_tracker' .DS. 'tracker.xml' );
		JToolBarHelper::title(JText::_('COM_TRACKER_CONTROL_PANEL'), 'trackerpanel');
		$this->assignRef('component_info', $component_xml);

		// Set the toolbar
		$this->addToolbar();

		// Display the template
		parent::display($tpl);

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