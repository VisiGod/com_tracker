<?php
/**
 * @version			2.5.13-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.modelform');

class TrackerModelUpload extends JModelForm {
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_tracker.torrent', 'torrent', array('control' => 'jform', 'load_data' => true));
		if (empty($form)) return false;
		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.uploaded.torrent.data', array());
		return $data;
	}
}
