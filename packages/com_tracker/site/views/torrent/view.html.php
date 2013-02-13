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

class TrackerViewTorrent extends JViewLegacy {

	protected $state = null;
	protected $item = null;

	function display($tpl = null) {

		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$pathway 	= $app->getPathway();

		// Initialise variables
		$state		= $this->get('State');
		$item		= $this->get('Item');
		$pagination	= $this->get('Pagination');
		$params		= $app->getParams();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if($item === false) {
			return JError::raiseError(404, JText::_('COM_TRACKER_NO_TORRENT'));
		}

		if ($user->get('guest') && $params->get('allow_guest') == 0) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		if ($user->get('guest') && $params->get('allow_guest') == 1) {
			$user = JUser::getTable('user', 'TrackerTable');
			$user->load($params->get('guest_user'));
		}

		$pathway->addItem($item->name, 'http://www.yourdomain.tld');
		
		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}

}
