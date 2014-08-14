<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
require_once JPATH_COMPONENT.'/helpers/Torrent.php';

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

		$query->select('a.*')
			  ->from('`#__tracker_torrents` AS a')
		
		// Join the user who added the torrent
			  ->select('u.username AS uploader')
			  ->join('LEFT', '`#__users` AS u ON u.id = a.uploader')
		
		// Join the torrent category
			  ->select('c.title AS category')
			  ->join('LEFT', '`#__categories` AS c ON c.id = a.categoryID')
		
		// End the query with the torrent ID
			  ->where('a.fid = ' . (int) $pk);

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

	public function edited() {
		$db 			= JFactory::getDBO();
		$user 			= JFactory::getUser();
		$params 		= JComponentHelper::getParams( 'com_tracker' );
		$app			= JFactory::getApplication();
		$upload_error 	= 0;

		$torrent['fid']					= (int)$_POST['fid'];
		$torrent['name'] 				= $_POST['name'];
		$torrent['filename']			= $_POST['filename'];
		$torrent['old_filename'] 		= $_POST['old_filename'];
		$torrent['description'] 		= $_POST['description'];
		$torrent['categoryID'] 			= (int)$_POST['categoryID'];
		$torrent['licenseID'] 			= (int)$_POST['licenseID'];
		$uploader['anonymous'] 			= (int)$_POST['uploader_anonymous'];
		$torrent['upload_multiplier']	= (float)$_POST['upload_multiplier'];
		$torrent['forum_post'] 			= (int)$_POST['forum_post'];
		$torrent['info_post'] 			= (int)$_POST['info_post'];
		$torrent['tags'] 				= $_POST['tags'];

		// If we're in freeleech
		if ($params->get('freeleech') == 1) $torrent['download_multiplier'] = 0;
		else $torrent['download_multiplier'] = (float)$_POST['download_multiplier'];

		// ------------------------------------------------------------------------------------------------------------------------
		// Now let's see if we've uploaded a new torrent file or choose to keep the old one
		// ------------------------------------------------------------------------------------------------------------------------
		if ($_POST['torrent_file'] == 0) { // We're keeping the original torrent
			if (empty($torrent['name'])) $torrent['name'] = $torrent['old_filename'];
			if (empty($torrent['filename'])) $torrent['filename'] = $torrent['name'].'.torrent'; 
			else $torrent['filename'] = $torrent['filename'].'.torrent';

			// Rename the filename if we've changed it
			if ($torrent['filename'] <> $torrent['old_filename'].'.torrent') {
				$pre_file = JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrent['fid'].'_';
				rename($pre_file.$torrent['old_filename'].'.torrent', $pre_file.$torrent['filename']);
			}
		} else { // We've uploaded a new torrent file to replace the old one
			// Sanitize the filename
			$torrent['filename'] = TrackerHelper::sanitize_filename($_FILES['filename']['name']);

			// If something wrong happened during the file upload, we go back to the editing
			if (!is_uploaded_file($_FILES['filename']['tmp_name'])) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED'), 'error');
			}

			// If we try to upload an empty file (0 bytes size)
			if ($_FILES['filename']['size'] == 0) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE'), 'error');
			}
			
			// Check if the torrent file is really a valid torrent file
			if (!Torrent::is_torrent($_FILES['filename']['tmp_name'])) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_NOT_BENCODED_FILE'), 'error');
			}

			// Let's create our new torrent object
			$new_torrent = new Torrent( $_FILES['filename']['tmp_name']);
			// And check for errors. Need to find a way to test them all :)
			if ( $errors = $new_torrent->errors() ) var_dump( $errors );
			
			// Private Torrents
			if (($params->get('make_private') == 1) && !$new_torrent->is_private()) $new_torrent->is_private(true);
			
			// If the user didnt wrote a name for the torrent, we get it from the filename
			if (empty($_POST['filename'])) {
				$filename = pathinfo($_FILES['filename']['name']);
				$torrent['filename'] = $filename['filename'];
			}
			
			if (empty($torrent['name'])) $torrent['name'] = $new_torrent->name();

			// Since we're updating the torrent, we must check if we're not updating it with another one that already exists
			$query = $db->getQuery(true);
			$query->select('count(fid)')
				  ->from('#__tracker_torrents')
				  ->where('info_hash = UNHEX("'.$new_torrent->hash_info().'")')
				  ->where('fid <> '.(int)$torrent['fid']);
			$db->setQuery($query);
			if ($db->loadResult() > 0) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_EDIT_TORRENT_ALREADY_EXISTS'), 'error');
			}
		}

		// ------------------------------------------------------------------------------------------------------------------------
		if ($params->get('use_image_file') == 1) {

			// When image_type is don't use image
			if ($_POST['image_type'] == 0) {
				$torrent['image_file'] = $_POST['image_file'];
			}

			// When image file is an uploaded file
			if ($_POST['image_type'] == 1) {
				if (!is_uploaded_file($_FILES['image_file']['tmp_name'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED_IMAGE'), 'error');
				}

				if (!filesize($_FILES['image_file']['tmp_name']) || $_FILES['image_file']['size'] == 0) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE_IMAGE'), 'error');
				}

				if (!TrackerHelper::is_image($_FILES['image_file']['tmp_name'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_NOT_AN_IMAGE_FILE'), 'error');
				}

				if (file_exists('file://' . JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['image_file']);
				}
				$image_file_extension = end(explode(".", $_FILES['image_file']['name'])); 
				$torrent['image_file'] = $_POST['info_hash'].'.'.$image_file_extension;
				$image_file_file = $_FILES['jform']['tmp_name']['image_file'];

				// And we should also move the image file if we're using it with the option of uploading an image file
				if (!move_uploaded_file($_FILES['image_file']['tmp_name'], JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$torrent['image_file'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_PROBLEM_MOVING_FILE'), 'error');
				}

			}

			// When image file is an external link
			if ($_POST['image_type'] == 2) {
				
				// If the remote file is unavailable
				if(@!file_get_contents($_POST['image_file'],0,NULL,0,1)) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&amp;view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_INVALID_FILE'), 'error');
				}
				
				// check if the remote file is not an image
				if (!is_array(@getimagesize($_POST['image_file']))) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&amp;view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_NOT_IMAGE'), 'error');
				}

				if (file_exists('file://' . JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['image_file']);
				}

				$torrent['image_file'] = $_POST['image_file'];
			}

			if ($_POST['image_type'] == 3) {
				if (file_exists('file://' . JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['image_file']) && !empty($_POST['image_file'])) {
					// Delete the previous image file from disk
					@unlink (JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['image_file']);
				}
				$torrent['image_file'] = "";
			}
		} else {
			$torrent['image_file'] = $_POST['image_file'];
		}

		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// All is good, let's update the record in the database
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$query = $db->getQuery(true);
		$query->update('#__tracker_torrents')
			  ->set('name = '.$db->quote($torrent['name']))
			  ->set('alias = '.$db->quote($torrent['name']))
			  ->set('filename = '.$db->quote($torrent['filename']))
			  ->set('description = '.$db->quote($torrent['description']))
			  ->set('categoryID = '.$db->quote($torrent['categoryID']))
			  ->set('licenseID = '.$db->quote($torrent['licenseID']))
			  ->set('uploader_anonymous = '.$db->quote($uploader['anonymous']))
			  ->set('download_multiplier = '.$db->quote($torrent['download_multiplier']))
			  ->set('upload_multiplier = '.$db->quote($torrent['upload_multiplier']))
			  ->set('forum_post = '.$db->quote($torrent['forum_post']))
			  ->set('info_post = '.$db->quote($torrent['info_post']))
			  ->set('image_file = '.$db->quote($torrent['image_file']))
			  ->set('tags = '.$db->quote($torrent['tags']))
			  ->set('flags = 2');
		// Since we're updating the torrent file we must change some values
		if ($_POST['torrent_file'] == 1) {
			$query->set('info_hash = UNHEX("'.$new_torrent->hash_info().'")')
				  ->set('size = '.$db->quote($new_torrent->size()))
				  ->set('number_files = '.$db->quote(count($new_torrent->content())));
		}
		$query->where('fid = ' . (int) $torrent['fid']);

		$db->setQuery($query);
		if (!$db->execute()) {
			JError::raiseError(500, $db->getErrorMsg());
			$upload_error = $upload_error + 1;
		}

		// We're uploading a new torrent file, the torrent contents probably changed
		if ($_POST['torrent_file'] == 1) {
			// We need to delete the old values
			$query->clear();
			$query = $db->getQuery(true);
			$query->delete('#__tracker_files_in_torrents')
				  ->where('torrentID ='.$db->quote($torrent['fid']));
			$db->setQuery($query);
			$db->execute();
			if ($error = $db->getErrorMsg()) {
				$this->setError($error);
				return false;
			}
			// And add the new ones
			foreach ($new_torrent->content() as $filename => $filesize) {
				$query->clear();
				$query = $db->getQuery(true);
				$query->insert('#__tracker_files_in_torrents')
					  ->set('torrentID = '.$db->quote($torrent['fid']))
					  ->set('filename = '.$db->quote($filename))
					  ->set('size = '.$db->quote($filesize));
				$db->setQuery($query);
				if (!$db->execute()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}

			// And we need to overwrite the previous torrent from the server with the new one
			if (!move_uploaded_file($_FILES['filename']['tmp_name'], JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrent['fid']."_".$_FILES['filename']['name']))
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$torrent['fid']), JText::_('COM_TRACKER_UPLOAD_PROBLEM_MOVING_FILE'), 'error');

			// But we also need to delete the old torrent file
			@unlink(JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$torrent['fid']."_".$torrent['old_filename'].'.torrent');
		}

		// If we're in freeleech we need to edit the record of the torrent in the freeleech table
		if ($params->get('freeleech') == 1) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->update('#__tracker_torrents_freeleech')
				  ->set('download_multiplier = '.$_POST['download_multiplier'])
				  ->where('fid = ' . (int) $torrent['fid']);
			$db->setQuery( $query );
			if (!$db->execute()) {
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
