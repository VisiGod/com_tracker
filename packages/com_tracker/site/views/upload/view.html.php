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

class TrackerViewUpload extends JViewLegacy {

	protected $state = null;
	protected $item = null;

	public function display($tpl = null) {
		$app		= JFactory::getApplication();
		
		// Initialise variables
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->user		= JFactory::getUser();
		$this->params	= $app->getParams();
		$this->form		= $this->get('Form');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if ($this->user->get('guest') && !$this->params->get('allow_guest')) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}
		
		if (TrackerHelper::user_permissions('upload_torrents', $this->user->id) == 0) {
			$app->redirect('index.php', JText::_('COM_TRACKER_USER_CANT_UPLOAD'), 'error');
		}
		
		return parent::display($tpl);
	}

}
