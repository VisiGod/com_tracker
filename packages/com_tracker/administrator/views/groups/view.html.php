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

class TrackerViewGroups extends JViewLegacy {

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
		
		JToolbarHelper::title(JText::_('COM_TRACKER_GROUPS'), 'users');

		if (count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0) {
			JToolbarHelper::addNew('group.add');
		}
		
		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('group.edit');
		}
		
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::deleteList('', 'groups.deleteXXX','JTOOLBAR_DELETE');
		}
		
		if ($user->authorise('core.admin', 'com_tracker')) {
			JToolbarHelper::preferences('com_tracker');
		}
	}

	protected function getSortFields() {
		$component = JComponentHelper::getComponent('com_tracker');
		$params = $component->params;

		$sortfields = new ArrayObject(array(
				'a.fid' => JText::_('JGLOBAL_FIELD_ID_LABEL'),
				'a.name' => JText::_('COM_TRACKER_TORRENT_NAME'),
				'a.view_torrents' => JText::_('COM_TRACKER_GROUP_VIEW_TORRENTS'),
				'a.edit_torrents' => JText::_('COM_TRACKER_GROUP_EDIT_TORRENTS'),
				'a.delete_torrents' => JText::_('COM_TRACKER_GROUP_DELETE_TORRENTS'),
				'a.upload_torrents' => JText::_('COM_TRACKER_GROUP_UPLOAD_TORRENTS'),
				'a.download_torrents' => JText::_('COM_TRACKER_GROUP_DOWNLOAD_TORRENTS'),
				'a.can_leech' => JText::_('COM_TRACKER_GROUP_CAN_LEECH'))
		);

		if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') {
			$sortfields['a.view_comments'] = JText::_('COM_TRACKER_GROUP_VIEW_COMMENTS');
			$sortfields['a.write_comments'] = JText::_('COM_TRACKER_GROUP_WRITE_COMMENTS');
			$sortfields['a.edit_comments'] = JText::_('COM_TRACKER_GROUP_EDIT_COMMENTS');
			$sortfields['a.delete_comments'] = JText::_('COM_TRACKER_GROUP_DELETE_COMMENTS');
			$sortfields['a.autopublish_comments'] = JText::_('COM_TRACKER_GROUP_AUTOPUBLISH_COMMENTS');
		}

		if ($params->get('torrent_multiplier')) {
			$sortfields['a.download_multiplier'] = JText::_('COM_TRACKER_DOWNLOAD_MULTIPLIER');
			$sortfields['a.upload_multiplier'] = JText::_('COM_TRACKER_UPLOAD_MULTIPLIER');
		}

		$sortfields['a.state'] = JText::_('JSTATUS');
		
		return $sortfields;
	}
}
