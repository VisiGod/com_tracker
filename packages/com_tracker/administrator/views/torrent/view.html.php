<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage		com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewTorrent extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null) {

		JLoader::register('JTableUser', JPATH_LIBRARIES.DIRECTORY_SEPARATOR.'joomla'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'table'.DIRECTORY_SEPARATOR.'user.php');

		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display();
	}

	protected function addToolbar() {
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->fid == 0);
		
		$canDo		= JHelperContent::getActions('com_tracker', 'category', $this->item->categoryID);
		
		JToolBarHelper::title(JText::_('COM_TRACKER_TORRENTS'), 'torrents');

		// If not checked out, can save the item.
		if (($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_tracker', 'core.create')) > 0)) {
			JToolBarHelper::apply('torrent.apply');
			JToolBarHelper::save('torrent.save');
		}

		if (empty($this->item->fid)) {
			JToolbarHelper::cancel('torrent.cancel');
		}
		else {
			JToolbarHelper::cancel('torrent.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}