<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class TrackerViewComment extends JView {

	public function display($tpl = null) {

		$params = JComponentHelper::getParams( 'com_tracker' );
		if ($params->get('enable_comments') == 0) {
			$app		= JFactory::getApplication();
			$app->redirect('index.php?option=com_tracker', JText::_('COM_TRACKER_COMMENT_NOT_ENABLE'), 'error');
			return false;
		}

		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');

		// Check for errors
		if (count($errors = $this->get('Errors'))) 
		{
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
		$isNew		= ($this->item->id == 0);
    $checkedOut = false;
		$canDo		= TrackerHelper::getActions();

		JToolBarHelper::title(JText::_('COM_TRACKER_COMMENTS'), 'comments');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create')))) {
			JToolBarHelper::apply('comment.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('comment.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('comment.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('comment.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id)) {
			JToolBarHelper::cancel('comment.cancel', 'JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('comment.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
