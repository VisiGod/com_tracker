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

class TrackerViewTrackerPanel extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null) {

		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		
		$this->activeFilters = $this->get('ActiveFilters');
		
		$component_xml	=	JApplicationHelper::parseXMLInstallFile( JPATH_ADMINISTRATOR .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_tracker' .DIRECTORY_SEPARATOR. 'tracker.xml' );
		$this->assignRef('component_info', $component_xml);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolbar() {
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('COM_TRACKER_CONTROL_PANEL'), 'home-2');
		
		$canDo = TrackerHelper::getActions();
		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_tracker');
		}
	}
/*
	protected function setDocument() {
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_TRACKER'));
	}
	*/
}