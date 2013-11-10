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

class TrackerControllerTorrent extends JControllerForm {

	protected $view_item = 'torrent';
	protected $view_list = 'torrents';

	public function getModel($name = 'torrent', $prefix = '', $config = array('ignore_request' => true)) {
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


	public function download() {
		$id = JRequest::getInt('id', 0);

		if ($id) {
			$model = $this->getModel('Torrent','TrackerModel',array('ignore_request'=>true));
			$model->setState('torrent.id',$id);
			$model->download();
		}
	}

	public function thanks() {
		$id = JRequest::getInt('id', 0);
	
		if ($id) {
			$model = $this->getModel('Torrent','TrackerModel',array('ignore_request'=>true));
			$model->setState('torrent.id',$id);
			$model->thanks();
		}
	}

	public function reseed() {
		$id = JRequest::getInt('id', 0);
	
		if ($id) {
			$model = $this->getModel('Torrent','TrackerModel',array('ignore_request'=>true));
			$model->setState('torrent.id',$id);
			$model->reseed();
		}
	}

	public function reported() {
		$app	= JFactory::getApplication();
		$model = $this->getModel('Torrent','TrackerModel',array('ignore_request'=>true));
	
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$app->setUserState('com_tracker.reported.torrent.data', $data);
		$model->reported();
	}
	
	public function uploaded() {
		$app	= JFactory::getApplication();
		$model = $this->getModel('Torrent','TrackerModel',array('ignore_request'=>true));

		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$app->setUserState('com_tracker.uploaded.torrent.data', $data);
		$model->uploaded();
	}

	
/*
	public function commented() {
		$app	= JFactory::getApplication();
		$model = $this->getModel('Comment','TrackerModel',array('ignore_request'=>true));

		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$app->setUserState('com_tracker.commented.torrent.data', $data);
		$model->commented();
	}
*/
	public function edited() {
		$app	= JFactory::getApplication();
		$model = $this->getModel('Edit','TrackerModel',array('ignore_request'=>true));

		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$app->setUserState('com_tracker.edited.torrent.data', $data);
		$model->edited();
	}

}