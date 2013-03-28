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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

class TrackerViewReport extends JView {
	protected $state = null;
	protected $item = null;

	public function display($tpl = null) {
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$params		= $app->getParams();
		
		$state		= $this->get('State');
		$item		= $this->get('Item');
		$this->form	= $this->get('Form');
		
		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('params',		$params);

		parent::display($tpl);
	}

}
