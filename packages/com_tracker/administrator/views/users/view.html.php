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

class TrackerViewUsers extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null) {
	
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
	
		$this->activeFilters = $this->get('ActiveFilters');
	
		$this->user = JFactory::getUser();
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
	
		$this->addToolbar();
	
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		$canDo = JHelperContent::getActions('com_tracker', 'category', $this->state->get('filter.category_id'));
		$user = JFactory::getUser();
	
		$bar = JToolBar::getInstance('toolbar');
	
		JToolbarHelper::title(JText::_('COM_TRACKER_USERS'), 'user');
	
		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('user.edit');
			JToolBarHelper::custom('users.leech', 'publish.png', 'publish_f2.png','leech', true);
			JToolBarHelper::custom('users.unleech', 'unpublish.png', 'unpublish_f2.png', 'unleech', true);
		}
	
		if ($user->authorise('core.admin', 'com_tracker')) {
			JToolbarHelper::preferences('com_tracker');
		}
	
		// Need to be built and available on next major version
		//$help_url  = 'http://www.visigod.com/{language}/help-server';
		//JToolBarHelper::help( 'COM_TRACKER_HELP_USERS', false, $help_url );
	}
	
	protected function getSortFields() {
		return array(
				'a.id' => JText::_('JGLOBAL_FIELD_ID_LABEL'),
				'u.name' => JText::_('COM_TRACKER_NAME'),
				'u.username' => JText::_('JGLOBAL_USERNAME'),
				'u.email' => JText::_('JGLOBAL_EMAIL'),
				'u.block' => JText::_('JENABLED'),
				'a.downloaded' => JText::_('COM_TRACKER_USER_DOWNLOADED'),
				'a.uploaded' => JText::_('COM_TRACKER_USER_UPLOADED'),
				'ratio' => JText::_('COM_TRACKER_USER_RATIO'),
				'a.donated' => JText::_('COM_TRACKER_USER_DONATED'),
				'a.groupID' => JText::_('COM_TRACKER_USER_GROUP'),
				'a.countryID' => JText::_('COM_TRACKER_USER_COUNTRY'),
				'a.download_multiplier' => JText::_('COM_TRACKER_DOWNLOAD_MULTIPLIER'),
				'a.upload_multiplier' => JText::_('COM_TRACKER_UPLOAD_MULTIPLIER'),
				'a.can_leech' => JText::_('COM_TRACKER_USER_CAN_LEECH'),
				'u.block' => JText::_('JSTATUS'),
		);
	}
}
