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

class TrackerViewTorrents extends JViewLegacy {

	protected $state;
	protected $items;
	protected $pagination;

	public function display($tpl = null) {
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();

		$limitstart = JRequest::getInt('limitstart');
		JRequest::setVar('start',$limitstart,'get','true');
		
		// Initialise variables
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$pagination	= $this->get('Pagination');
		$params		= $app->getParams();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if ($user->get('guest') && ($params->get('allow_guest') == 0)) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		if ($user->get('guest') && $params->get('allow_guest') == 1) {
			$user->id = $params->get('guest_user');
		}

		$this->assignRef('state', $state);
		$this->assignRef('items', $items);
		$this->assignRef('item', $item);
		$this->assignRef('params', $params);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('user', $user);

		parent::display($tpl);
	}

}
