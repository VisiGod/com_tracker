<?php
/**
 * @version			2.5.12-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class TrackerControllerSetting extends JControllerForm {

	protected $text_prefix = 'COM_TRACKER_SETTING';

	public function display($cachable = false, $urlparams = false) {
		$this->setRedirect(JRoute::_('index.php?option=com_tracker&view=settings', false));
	}

	protected function allowEdit($data = array(), $key = 'name') {
		// Check if this person is a Super Admin
		if (JAccess::check($data[$key], 'core.admin'))
		{
			// If I'm not a Super Admin, then disallow the edit.
			if (!JFactory::getUser()->authorise('core.admin'))
			{
				return false;
			}
		}

		return parent::allowEdit($data, $key);
	}

}
