<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controllerform');

class TrackerControllerThankyou extends JControllerForm {

	function __construct() {
		$this->view_list = 'thankyous';
		parent::__construct();
	}
}