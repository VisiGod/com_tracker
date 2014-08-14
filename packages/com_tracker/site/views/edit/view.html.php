<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class TrackerViewEdit extends JViewLegacy {

	protected $form;
	protected $state;
	protected $item;

	public function display($tpl = null) {
		$app		= JFactory::getApplication();
		
		// Initialise variables
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->user		= JFactory::getUser();
		$this->params	= $app->getParams();
		$this->form		= $this->get('Form');

		$pathway 	= $app->getPathway();
		$pathway->addItem(str_replace("_", " ", $this->item->name));

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if($this->item === false) {
			return JError::raiseError(404, JText::_('COM_TRACKER_NO_TORRENT'));
		}

		if ($this->user->get('guest')) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		return parent::display($tpl);
	}

}
