<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
//jimport('joomla.application.component.helper');

//require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';
//JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

class TrackerModelEdit extends JModelItem {

	protected $_context = 'com_tracker.torrent';

	protected function populateState() {
		$app = JFactory::getApplication();

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('torrent.id', $pk);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

	}

	public function getItem($pk = null) {
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('torrent.id');
		$params = JComponentHelper::getParams('com_tracker');

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*');
		$query->from('`#__tracker_torrents` AS a');
		
		// Join the user who added the torrent
		$query->select('u.username AS uploader');
		$query->join('LEFT', '`#__users` AS u ON u.id = a.uploader');
		
		// Join the torrent category
		$query->select('c.title AS category');
		$query->join('LEFT', '`#__categories` AS c ON c.id = a.categoryID');
		
		// End the query with the torrent ID
		$query->where('a.fid = ' . (int) $pk);

		$db->setQuery($query);

		$data = $db->loadObject();

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		if (empty($data)) {
			return JError::raiseError(404,JText::_('COM_TRACKER_INVALID_TORRENT'));
		}

		$this->_item[$pk] = $data;

		return $this->_item[$pk];
	}

	function edited() {
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

		$db 			= JFactory::getDBO();
		$user 			= JFactory::getUser();
		$params 		= JComponentHelper::getParams( 'com_tracker' );
		$app			= JFactory::getApplication();
		$upload_error 	= 0;

		$torrent['fid']			= (int)$_POST['fid'];
		$torrent['name'] 		= $_POST['name'];
		$torrent['filename'] 	= $_POST['filename'];
		$torrent['old_filename'] 	= $_POST['old_filename'];
		$torrent['description'] = $_POST['description'];
		$torrent['categoryID'] 	= (int)$_POST['categoryID'];
		$torrent['licenseID'] 	= (int)$_POST['licenseID'];
		$uploader['anonymous'] 	= (int)$_POST['uploader_anonymous'];
		$torrent['upload_multiplier'] = (float)$_POST['upload_multiplier'];
		$torrent['forum_post'] = (int)$_POST['forum_post'];
		$torrent['info_post'] = (int)$_POST['info_post'];
		
		// If we're in freeleech
		if ($params->get('freeleech') == 1) $torrent['download_multiplier'] = 0;
		else $torrent['download_multiplier'] = (float)$_POST['download_multiplier'];

		// ------------------------------------------------------------------------------------------------------------------------
		if ($params->get('use_image_file') == 1) {

			// When image_type is don't use image
			if ($_POST['default_image_type'] == 0) {
				$torrent['image_file'] = $_POST['image_file'];
			}

			// When image file is an uploaded file
			if ($_POST['default_image_type'] == 1) {
				if (!is_uploaded_file($_FILES['image_file']['tmp_name'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED_IMAGE'), 'error');
				}

				if (!filesize($_FILES['image_file']['tmp_name']) || $_FILES['image_file']['size'] == 0) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE_IMAGE'), 'error');
				}

				if (!TrackerHelper::is_image($_FILES['image_file']['tmp_name'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_NOT_AN_IMAGE_FILE'), 'error');
				}

				if (file_exists('file://' . JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']);
				}
				$image_file_extension = end(explode(".", $_FILES['image_file']['name'])); 
				$torrent['image_file'] = $_POST['info_hash'].'.'.$image_file_extension;
				$image_file_file = $_FILES['jform']['tmp_name']['image_file'];

				// And we should also move the image file if we're using it with the option of uploading an image file
				if (!move_uploaded_file($_FILES['image_file']['tmp_name'], JPATH_SITE.DS.'images/tracker/torrent_image/'.$torrent['image_file'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_PROBLEM_MOVING_FILE'), 'error');
				}

			}

			// When image file is an external link
			if ($_POST['default_image_type'] == 2) {
				
				// If the remote file is unavailable
				if(@!file_get_contents($_POST['image_file'],0,NULL,0,1)) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&amp;view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_INVALID_FILE'), 'error');
				}
				
				// check if the remote file is not an image
				if (!is_array(@getimagesize($_POST['image_file']))) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&amp;view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_NOT_IMAGE'), 'error');
				}

				if (file_exists('file://' . JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']);
				}

				$torrent['image_file'] = $_POST['image_file'];
			}

			if ($_POST['default_image_type'] == 3) {
				if (file_exists('file://' . JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DS.'images/tracker/torrent_image/'.$_POST['image_file']);
				}

				$torrent['image_file'] = "";
			}
		} else {
			$torrent['image_file'] = $_POST['image_file'];
		}

		// Rename the filename if we've changed it
		if ($torrent['filename'] <> $torrent['old_filename']) {
			$pre_file = JPATH_SITE.DS.$params->get('torrent_dir').$torrent['fid'].'_';
		
			rename($pre_file.$torrent['old_filename'].'.torrent', $pre_file.$torrent['filename'].'.torrent');
		}
		
		// We need to put back the file extension in the filename
		$torrent['filename'] = $torrent['filename'].'.torrent';
		
		
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// All is good, let's update the record in the database
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$query = $db->getQuery(true);
		$query->update('#__tracker_torrents');
		$query->set('name = '.$db->quote($torrent['name']));
		$query->set('alias = '.$db->quote($torrent['name']));
		$query->set('filename = '.$db->quote($torrent['filename']));
		$query->set('description = '.$db->quote($torrent['description']));
		$query->set('categoryID = '.$db->quote($torrent['categoryID']));
		$query->set('licenseID = '.$db->quote($torrent['licenseID']));
		$query->set('uploader_anonymous = '.$db->quote($uploader['anonymous']));
		$query->set('download_multiplier = '.$db->quote($torrent['download_multiplier']));
		$query->set('upload_multiplier = '.$db->quote($torrent['upload_multiplier']));
		$query->set('forum_post = '.$db->quote($torrent['forum_post']));
		$query->set('info_post = '.$db->quote($torrent['info_post']));
		$query->set('image_file = '.$db->quote($torrent['image_file']));
		$query->set('flags = 2');
		$query->where('fid = ' . (int) $torrent['fid']);

		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			$upload_error = $upload_error + 1;
		}
		
		// If we're in freeleech we need to edit the record of the torrent in the freeleech table
		if ($params->get('freeleech') == 1) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->update('#__tracker_torrents_freeleech');
			$query->set('download_multiplier = '.$_POST['download_multiplier']);
			$query->where('fid = ' . (int) $torrent_fid);
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseError(500, $db->getErrorMsg());
			}
		}

		if ($upload_error == 0) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent['fid']), JText::_('COM_TRACKER_EDIT_OK'), 'message');
		} else {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_EDIT_NOK'), 'error');
		}

	}

}
