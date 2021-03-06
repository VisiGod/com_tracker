<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage		com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class TrackerViewUserpanel extends JViewLegacy {

	protected $state = null;
	protected $item = null;

	public function display($tpl = null) {
		$app			= JFactory::getApplication();

		// Initialise variables
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->user		= JFactory::getUser();
		$this->params	= $app->getParams();
		$this->session	= JFactory::getSession();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		if ($this->user->get('guest')) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		if ($this->item->id == 0) {
			$app->redirect('index.php', JText::_('COM_TRACKER_USER_INVALID'), 'error');
		}
		
		return parent::display($tpl);
	}
}
