<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewTorrent extends JView {

	public function display($tpl = null) {

		JLoader::register('JTableUser', JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'user.php');

		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');

		// Check for errors
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form 	= $form;
		$this->item 	= $item;

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);

	}

	protected function addToolbar() {
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
    $checkedOut = false;
		$canDo		= TrackerHelper::getActions();

		JToolBarHelper::title(JText::_('COM_TRACKER_TORRENTS'), 'torrents');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create')))) {
			JToolBarHelper::apply('torrent.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('torrent.save', 'JTOOLBAR_SAVE');
		}

		if (empty($this->item->id)) {
			JToolBarHelper::cancel('torrent.cancel', 'JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('torrent.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}