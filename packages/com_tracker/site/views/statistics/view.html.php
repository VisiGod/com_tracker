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

class TrackerViewStatistics extends JView {
	protected $state = null;
	protected $item = null;

	public function display($tpl = null) {
		$state	= $this->get('State');
		$item		= $this->get('Item');
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$params		= $app->getParams();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// No guests allowed
		if (!$params->get('allow_guest') && $user->get('guest')) $noaccess = 1;
			else $noaccess = 0;

		// Check if the user group is allowed to see the statistics
		if (is_scalar($params->get('usergroups'))) {
			foreach ($params->get('usergroups') as $group) {
				if ($group == $user->get('id_level')) $noaccess = 0;
				else $noaccess = 1;
			}
		}
		
		if ($noaccess) {
			JError::raiseNotice(500, JText::_( 'COM_TRACKER_STATISTICS_NO_GUEST' ));
			return false;
		}

		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('params',		$params);

		parent::display($tpl);
	}

}
