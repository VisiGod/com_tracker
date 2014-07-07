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

class TrackerViewComments extends JViewLegacy {

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
		parent::display();
	}
	
	protected function addToolbar() {
		$canDo = JHelperContent::getActions('com_tracker', 'category', $this->state->get('filter.category_id'));
		$user = JFactory::getUser();
	
		$bar = JToolBar::getInstance('toolbar');
	
		JToolBarHelper::title(JText::_('COM_TRACKER_COMMENTS'), 'comments');
	
		if (count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0) {
			JToolbarHelper::addNew('comment.add');
		}
	
		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('comment.edit');
		}
	
		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('comment.delete');
		}
	
		if ($user->authorise('core.admin', 'com_tracker')) {
			JToolbarHelper::preferences('com_tracker');
		}
	}
	
	protected function getSortFields() {
		return array(
				'a.id' => JText::_('JGLOBAL_FIELD_ID_LABEL'),
				'a.torrent' => JText::_('COM_TRACKER_COMMENT_TORRENTNAME'),
				'a.comment' => JText::_('COM_TRACKER_COMMENT_COMMENT'),
				'a.username' => JText::_('JGLOBAL_FIELD_CREATED_BY_LABEL'),
				'a.created_time' => JText::_('JGLOBAL_CREATED_DATE'),
				'a.state' => JText::_('JSTATUS'),
		);
	}
}
