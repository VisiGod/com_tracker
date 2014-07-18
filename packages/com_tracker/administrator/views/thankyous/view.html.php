<?php
/**
 * @version		3.3.1-dev
 * @package		Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class TrackerViewThankyous extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null) {

		$params = JComponentHelper::getParams( 'com_tracker' );
		if ($params->get('enable_thankyou') == 0) {
			$app		= JFactory::getApplication();
			$app->redirect('index.php?option=com_tracker', JText::_('COM_TRACKER_THANKYOUS_NOT_ENABLE'), 'error');
			return false;
		}

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
		parent::display();
	}
	
	protected function addToolbar() {
		$canDo = JHelperContent::getActions('com_tracker', 'category', $this->state->get('filter.category_id'));
		$user = JFactory::getUser();
	
		$bar = JToolBar::getInstance('toolbar');
	
		JToolBarHelper::title(JText::_('COM_TRACKER_THANKYOUS'), 'thumbs-up');
	
		if (count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0) {
			JToolbarHelper::addNew('thankyou.add');
		}
	
		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('thankyou.edit');
		}

		if ($canDo->get('core.edit.state')) {
			if ($this->state->get('filter.state') != 2) {
				JToolbarHelper::publish('thankyous.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('thankyous.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}
		
			if ($this->state->get('filter.state') != -1) {
				if ($this->state->get('filter.state') != 2) {
					JToolbarHelper::archiveList('thankyous.archive');
				} elseif ($this->state->get('filter.state') == 2) {
					JToolbarHelper::unarchiveList('thankyous.publish');
				}
			}
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolbarHelper::deleteList('', 'thankyous.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('thankyous.trash');
		}

		if ($user->authorise('core.admin', 'com_tracker')) {
			JToolbarHelper::preferences('com_tracker');
		}
	}
	
	protected function getSortFields() {
		return array(
				'a.id' => JText::_('JGLOBAL_FIELD_ID_LABEL'),
				'tt.name' => JText::_('COM_TRACKER_TORRENT_NAME'),
				'du.username' => JText::_('JGLOBAL_USERNAME'),
				'a.created_time' => JText::_('JGLOBAL_CREATED_DATE'),
				'a.state' => JText::_('JSTATUS'),
		);
	}
}
