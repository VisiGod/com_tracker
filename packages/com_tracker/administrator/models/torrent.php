<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class TrackerModelTorrent extends JModelAdmin {

	protected function allowEdit($data = array(), $key = 'fid') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_tracker.torrent.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	public function getTable($type = 'Torrent', $prefix = 'TrackerTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_tracker.torrent', 'torrent', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;
		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.edit.torrent.data', array());
		if (empty($data)) $data = $this->getItem();
		return $data;
	}

	public function delete(&$itemIds) {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$params = JComponentHelper::getParams( 'com_tracker' );

		// Sanitize the ids.
		$itemIds = array_unique($itemIds);
		JArrayHelper::toInteger($itemIds);

		// Update the flag type of all torrents (when flag = 1, torrent will be deleted).
		$query->clear();
		$query->update($db->quoteName('#__tracker_torrents'));
		$query->set($db->quoteName('flags') . ' = 1');
		$query->where($db->quoteName('fid') . ' IN (' . implode(',', $itemIds) . ')');
		$db->setQuery($query);
		try {
			$result = $db->query();
		} catch (Exception $e) {
			return false;
		}
		
		// Delete the image file
		if ($params->get('use_image_file')) {
			foreach ($itemIds as $itemId) {
				$query = $db->getQuery(true);
				$query->select('image_file');
				$query->from('#__tracker_torrents');
				$query->where('fid = ' . (int) $itemId);
				$db->setQuery($query);
				$image_file = $db->loadResult();
				
				if ($image_file) {
					// Delete the image
					@unlink (JPATH_SITE.DS.'images/tracker/torrent_image/'.$itemId.'_'.$image_file);
				}
			}
		}

		// Delete the torrent thanks
		$query->clear();
		$query->delete($db->quoteName('#__tracker_torrent_thanks'));
		$query->where($db->quoteName('torrentID') . ' IN (' . implode(',', $itemIds) . ')');
		$db->setQuery($query);
		try {
			$result = $db->query();
		} catch (Exception $e) {
			return false;
		}

		// Delete the reported torrent
		$query->clear();
		$query->delete($db->quoteName('#__tracker_reported_torrents'));
		$query->where($db->quoteName('fid') . ' IN (' . implode(',', $itemIds) . ')');
		$db->setQuery($query);
		try {
			$result = $db->query();
		} catch (Exception $e) {
			return false;
		}

		// Delete the reseed requested torrent
		$query->clear();
		$query->delete($db->quoteName('#__tracker_reseed_request'));
		$query->where($db->quoteName('fid') . ' IN (' . implode(',', $itemIds) . ')');
		$db->setQuery($query);
		try {
			$result = $db->query();
		} catch (Exception $e) {
			return false;
		}

		// Delete the torrent file
		$query = $db->getQuery(true);
		$query->select('filename');
		$query->from('#__tracker_torrents');
		$query->where('fid = ' . (int) $itemId);
		$db->setQuery($query);
		$file = $db->loadResult();
		
		// Delete the real torrent file
		@unlink (JPATH_SITE.DS.$params->get('torrent_dir').$itemId.'_'.$file);
		

		return true;
	}

	public function save($data) {
		$app			= JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_tracker' );

		if ($params->get('use_image_file') == 1) {
		
			// When image_type is don't use image
			if ($_POST['default_image_type'] == 0) {
				$data['image_file'] = $_POST['image_file'];
			}

			// When image file is an uploaded file
			if ($_POST['default_image_type'] == 1) {
				if (!is_uploaded_file($_FILES['image_file']['tmp_name'])) {
					$this->setError(JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED_IMAGE'));
					return false;
				}
		
				if (!filesize($_FILES['image_file']['tmp_name']) || $_FILES['image_file']['size'] == 0) {
					$this->setError(JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE_IMAGE'));
					return false;
				}
		
				if (!TrackerHelper::is_image($_FILES['image_file']['tmp_name'])) {
					$this->setError(JText::_('COM_TRACKER_UPLOAD_NOT_AN_IMAGE_FILE'));
					return false;
				}
		
				if (file_exists('file://' . JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']);
				}
				$image_file_extension = end(explode(".", $_FILES['image_file']['name']));
				$data['image_file'] = $_POST['info_hash'].'.'.$image_file_extension;
		
				// And we should also move the image file if we're using it with the option of uploading an image file
				if (!move_uploaded_file($_FILES['image_file']['tmp_name'], JPATH_SITE.DS.'images/tracker/torrent_image/'.$data['image_file'])) {
					$this->setError(JText::_('COM_TRACKER_UPLOAD_PROBLEM_MOVING_FILE'));
					return false;
				}
			}

			// When image file is an external link
			if ($_POST['default_image_type'] == 2) {
				// If the remote file is unavailable
				if(@!file_get_contents($_POST['image_file'],0,NULL,0,1)) {
					$this->setError(JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_INVALID_FILE'));
					return false;
				}

				// check if the remote file is not an image
				if (!is_array(@getimagesize($_POST['image_file']))) {
					$this->setError(JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_NOT_IMAGE'));
					return false;
				}
		
				if (file_exists('file://' . JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']);
				}
				$data['image_file'] = $_POST['image_file'];
			}
			
			// When we want to remove the old image file or image link
			if ($_POST['default_image_type'] == 3) {

				if (file_exists('file://' . JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']);
				}

				$data['image_file'] = $_POST['image_file'];
			}
		} else {
			$data['image_file'] = "";
		}
		
		// Rename the filename if we've changed it
		if ($data['filename'] <> $_POST['old_filename']) {
			$pre_file = JPATH_SITE.DS.$params->get('torrent_dir').$data['fid'].'_';

			rename($pre_file.$_POST['old_filename'].'.torrent', $pre_file.$data['filename'].'.torrent');
		}
		
		// We need to put back the file extension in the filename
		$data['filename'] = $data['filename'].'.torrent';
		
		return parent::save($data);
	}
	
}