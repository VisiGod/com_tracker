<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TrackerViewEdit extends JView {
	protected $state = null;
	protected $item = null;

	public function display($tpl = null) {
		$state	= $this->get('State');
		$item		= $this->get('Item');
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if($item === false)
		{
			return JError::raiseError(404, JText::_('COM_TRACKER_NO_TORRENT'));
		}
		
		if ($user->get('guest')) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}			

		$this->assignRef('params',		$params);
		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);

		parent::display($tpl);
	}

}
