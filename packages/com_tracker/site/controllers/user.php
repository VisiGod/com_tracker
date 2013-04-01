<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class TrackerControllerUser extends JControllerForm {

	public function getModel($name = 'userpanel', $prefix = '', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	protected function getReturnPage() {
		$return = JRequest::getVar('return', null, 'default', 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return))) {
			return JURI::base();
		} else {
			return base64_decode($return);
		}
	}

	function resetpassversion() {
		$id = JRequest::getInt('id', 0);

		if ($id) {
			$model = $this->getModel('Userpanel','TrackerModel',array('ignore_request'=>true));
			$model->setState('userpasskey.id',$id);
			$model->resetpassversion();
		}
	}

}