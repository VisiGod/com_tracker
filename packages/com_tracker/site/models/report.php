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
jimport('joomla.application.component.modelform');

class TrackerModelReport extends JModelForm {
	
	public function &getItem() {
		$user	= JFactory::getUser();
		$id 	= JRequest::getInt('id', 0);
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('name');
		$query->from('#__tracker_torrents');
		$query->where('fid = ' . (int) $id);
		$db->setQuery($query);
		$data = $db->loadObject();
		$data->fid = $id;
		$data->reporter = $user->id;
		$data->reporter_name = $user->username;
		
		if (empty($data)) {
			return JError::raiseError(404, JText::_('COM_TRACKER_NO_TORRENT'));
		}
		
		return $data;
	}

	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_tracker.report', 'report', array('control' => 'jform', 'load_data' => true));
		if (empty($form)) return false;
		return $form;
	}

	protected function loadFormData() {
		$data = JFactory::getApplication()->getUserState('com_tracker.reported.torrent.data', array());
		return $data;
	}
}
