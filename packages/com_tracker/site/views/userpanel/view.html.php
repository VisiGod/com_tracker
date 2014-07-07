<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage		com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TrackerViewUserpanel extends JViewLegacy {
	protected $state = null;
	protected $item = null;

	public function display($cachable = false, $urlparams = false) {
		$state	= $this->get('State');
		$item		= $this->get('Item');
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$session = JFactory::getSession();
		$params		= $app->getParams();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		if ($user->get('guest')) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		if ($item->id == 0) {
			$app->redirect('index.php', JText::_('COM_TRACKER_USER_INVALID'), 'error');
		}
		
		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('params',		$params);
		$this->assignRef('session',		$session);

		parent::display();
	}

}
