<?php
/**
 * @version			3.3.2-dev
* @package			Joomla
* @subpackage	com_tracker
* @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
* @license			GNU General Public License version 2 or later; see LICENSE.txt
*/

// no direct access
defined('_JEXEC') or die;

class TrackerControllerUserPanel extends JControllerForm {

	protected $view_list = 'userpanel';

	public function getModel($name = 'userpanel', $prefix = '', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	protected function getReturnPage() {
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return))) {
			return JUri::base();
		} else {
			return base64_decode($return);
		}
	}

	public function resetpassversion() {
		$id = JRequest::getInt('id', 0);

		if ($id) {
			$model = $this->getModel('UserPanel','TrackerModel',array('ignore_request'=>true));
			$model->setState('userpasskey.id',$id);
			$model->resetpassversion();
		}
	}
}
