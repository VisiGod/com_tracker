<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');

class TrackerControllerReports extends JControllerAdmin {

	public function getModel($name = 'Report', $prefix = 'TrackerModel', $config = array()) {
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
	
	public function saveOrderAjax() {
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('id', array(), 'array');
		$order = $input->post->get('order', array(), 'array');
	
		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);
	
		// Get the model
		$model = $this->getModel();
	
		// Save the ordering
		$return = $model->saveorder($pks, $order);
	
		if ($return) {
			echo "1";
		}
	
		// Close the application
		JFactory::getApplication()->close();
	}
}