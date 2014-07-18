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

class TrackerViewTorrents extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null) {

		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		$this->filterForm    = $this->get('FilterForm');
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
	
		JToolbarHelper::title(JText::_('COM_TRACKER_TORRENTS'), 'attachment');
	
		if (count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0) {
			JToolbarHelper::addNew('torrent.add');
		}
	
		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('torrent.edit');
		}
	
		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('torrent.delete');
		}
	
		if ($user->authorise('core.admin', 'com_tracker')) {
			JToolbarHelper::preferences('com_tracker');
		}

		// Need to be built and available on next major version
		//$help_url  = 'http://www.visigod.com/{language}/help-server';
		//JToolBarHelper::help( 'COM_TRACKER_HELP_TORRENTS', false, $help_url );
	}
	
	protected function getSortFields() {
		return array(
				'a.fid' => JText::_('JGLOBAL_FIELD_ID_LABEL'),
				'a.name' => JText::_('COM_TRACKER_TORRENT_NAME'),
				'a.categoryid' => JText::_('JCATEGORY'),
				'a.size' => JText::_('COM_TRACKER_TORRENT_SIZE'),
				'a.created_time' => JText::_('COM_TRACKER_TORRENT_UPLOADED'),
				'a.leechers' => JText::_('COM_TRACKER_TORRENT_LEECHERS'),
				'a.seeders' => JText::_('COM_TRACKER_TORRENT_SEEDERS'),
				'a.completed' => JText::_('COM_TRACKER_TORRENT_COMPLETED'),
				'a.download_multiplier' => JText::_('COM_TRACKER_DOWNLOAD_MULTIPLIER'),
				'a.upload_multiplier' => JText::_('COM_TRACKER_UPLOAD_MULTIPLIER'),
				'a.uploader' => JText::_('COM_TRACKER_TORRENT_UPLOADER'),
				'a.state' => JText::_('JSTATUS'),
		);
	}
}