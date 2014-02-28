<?php
/**
 * @version			2.5.13-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

class TrackerViewUpload extends JView {
	protected $state = null;
	protected $item = null;

	public function display($tpl = null) {
		$state	= $this->get('State');
		$item		= $this->get('Item');
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$params		= $app->getParams();
		$this->form		= $this->get('Form');

		if ($user->get('guest') && !$params->get('allow_guest')) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		if (TrackerHelper::user_permissions('upload_torrents', $user->id) == 0) {
			$app->redirect('index.php', JText::_('COM_TRACKER_USER_CANT_UPLOAD'), 'error');
		}

		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('params',		$params);

		parent::display($tpl);
	}

}
