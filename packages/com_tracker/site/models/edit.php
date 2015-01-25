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

		$temp_torrent['fid']				= (int)$_POST['fid'];
		$temp_torrent['name'] 				= $_POST['name'];
		$temp_torrent['old_filename'] 		= $_POST['old_filename'];
		$temp_torrent['description'] 		= $_POST['description'];
		$temp_torrent['category'] 			= (int)$_POST['categoryID'];
		$temp_torrent['license'] 			= (int)$_POST['licenseID'];
		$temp_torrent['uploader_anonymous'] = (int)$_POST['uploader_anonymous'];
		$temp_torrent['upload_multiplier']	= (float)$_POST['upload_multiplier'];
		$temp_torrent['forum_post'] 		= (int)$_POST['forum_post'];
		$temp_torrent['info_post'] 			= (int)$_POST['info_post'];

		// Check if we're using tags
		$params->get('torrent_tags') == 1 ? $temp_torrent['tags']= $_POST['tags'] : $temp_torrent['tags'] = '';

		// If we're in freeleech
		$params->get('freeleech') == 1 ? $temp_torrent['download_multiplier'] = 0 : $temp_torrent['download_multiplier'] = 1;

		// ------------------------------------------------------------------------------------------------------------------------
		// Now let's see if we've uploaded a new torrent file or choose to keep the old one
		// ------------------------------------------------------------------------------------------------------------------------
		if ($_POST['torrent_file'] == 0) { // We're keeping the original torrent
			if (empty($temp_torrent['name'])) $temp_torrent['name'] = $temp_torrent['old_name'];
		} else { // We've uploaded a new torrent file to replace the old one
			// Let's take care of the .torrent file first. We'll make an unique md5 filename to prevent stupid and unsuported filenames
			$temp_torrent['filename'] = md5(uniqid());

			// If something wrong happened during the file upload, we bail out
			if (!is_uploaded_file($_FILES['filename']['tmp_name'])) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED'), 'error');
			}

			// If we try to upload an empty file (0 bytes size)
			if ($_FILES['filename']['size'] == 0) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE'), 'error');
			}

			// Check if the torrent file is really a valid torrent file
			if (!Torrent::is_torrent($_FILES['filename']['tmp_name'])) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_NOT_BENCODED_FILE'), 'error');
			}

			// Let's create our new torrent object
			$torrent = new Torrent( $_FILES['filename']['tmp_name'] );
			
			// And check for errors. Need to find a way to test them all :)
			if ( $errors = $torrent->errors() ) var_dump( $errors );
			
			// Private Torrents
			if (($params->get('make_private') == 1) && !$torrent->is_private()) $torrent->is_private(true);
			
			// If the user didnt wrote a name for the torrent, we get it from the filename
			if (empty($_POST['name'])) {
				$temp_torrent['name'] = pathinfo($_FILES['filename']['name'],PATHINFO_FILENAME);
			} else {
				$temp_torrent['name'] = $_POST['name'];
			}

			// Since we're updating the torrent, we must check if we're not updating it with another one that already exists
			$query = $db->getQuery(true);
			$query->select('count(fid)')
				  ->from('#__tracker_torrents')
				  ->where('info_hash = UNHEX("'.$torrent->hash_info().'")')
				  ->where('fid <> '.(int)$temp_torrent['fid']);
			$db->setQuery($query);
			if ($db->loadResult() > 0) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$temp_torrent['fid']), JText::_('COM_TRACKER_EDIT_TORRENT_ALREADY_EXISTS'), 'error');
			}
		}

		// ------------------------------------------------------------------------------------------------------------------------
		// Let's process the image file if we use it
		if ($params->get('use_image_file')) {
			$image_file_query_value = "";
			
			// When image file is an uploaded file
			if ($_POST['default_image_type'] == 1) {
				if (!is_uploaded_file($_FILES['image_file_file']['tmp_name'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED_IMAGE'), 'error');
				}

				if (!filesize($_FILES['image_file_file']['tmp_name']) || $_FILES['image_file_file']['size'] == 0 || $_FILES['image_file_file']['error']) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE_IMAGE'), 'error');
				}

				if (!TrackerHelper::is_image($_FILES['image_file_file']['tmp_name'])) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_NOT_AN_IMAGE_FILE'), 'error');
				}

				// We need to check if we have a new torrent hash or if we are just updating the image file
				$_POST['torrent_file'] ? $image_torrent_hash = $torrent->hash_info(): $image_torrent_hash = $_POST['info_hash']; 

				$temp_torrent['torrent_image_extension'] = pathinfo($_FILES['image_file_file']['name'], PATHINFO_EXTENSION);
				$image_file_query_value = $image_torrent_hash.'.'.$temp_torrent['torrent_image_extension'];
				$temp_torrent['torrent_image_file'] = $_FILES['image_file_file']['tmp_name'];
			}

			// When image file is an external link
			if ($_POST['default_image_type'] == 2) {
				// If the remote file is unavailable
				if(@!file_get_contents($_POST['image_file_link'],0,NULL,0,1)) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_INVALID_FILE'), 'error');
				}

				// check if the remote file is not an image
				if (!is_array(@getimagesize($_POST['image_file_link']))) {
					$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_REMOTE_IMAGE_NOT_IMAGE'), 'error');
				}
				
				$image_file_query_value = $_POST['image_file_link'];
			}
		}

		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// All is good, let's update the record in the database
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		$query = $db->getQuery(true);
		$query->update('#__tracker_torrents')
			  ->set('name = '.$db->quote($temp_torrent['name']))
			  ->set('alias = '.$db->quote($temp_torrent['name']))
			  ->set('filename = '.$db->quote($temp_torrent['filename']))
			  ->set('description = '.$db->quote($temp_torrent['description']))
			  ->set('categoryID = '.$db->quote($temp_torrent['category']))
			  ->set('licenseID = '.$db->quote($temp_torrent['license']))
			  ->set('uploader_anonymous = '.$db->quote($temp_torrent['uploader_anonymous']))
			  ->set('forum_post = '.$db->quote($temp_torrent['forum_post']))
			  ->set('info_post = '.$db->quote($temp_torrent['info_post']))
			  ->set('upload_multiplier = 1')
			  ->set('download_multiplier = '.$db->quote($temp_torrent['download_multiplier']))			  
			  ->set('image_file = '.$db->quote($image_file_query_value))
			  ->set('tags = '.$db->quote($temp_torrent['tags']))
			  ->set('flags = 2');
		// Since we're updating the torrent file we must change some values
		if ($_POST['torrent_file'] == 1) {
			$query->set('info_hash = UNHEX("'.$torrent->hash_info().'")')
				  ->set('size = '.$db->quote($torrent->size()))
			  	  ->set('number_files = '.$db->quote(count($torrent->content())));
		}
		$query->where('fid = ' . (int) $temp_torrent['fid']);

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
				  ->where('torrentID ='.$db->quote($temp_torrent['fid']));
			$db->setQuery($query);
			if (!$db->execute()) {
				JError::raiseError(500, $db->getErrorMsg());
			}
			// And add the new ones
			foreach ($torrent->content() as $filename => $filesize) {
				$query->clear();
				$query = $db->getQuery(true);
				$query->insert('#__tracker_files_in_torrents')
					  ->set('torrentID = '.$db->quote($temp_torrent['fid']))
					  ->set('filename = '.$db->quote($filename))
					  ->set('size = '.$db->quote($filesize));
				$db->setQuery($query);
				if (!$db->execute()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}

			// We need to delete the old torrent file first
			$old_torrent = JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$temp_torrent['fid']."_".$temp_torrent['old_filename'].'.torrent';
			@unlink($old_torrent);

			// Now we copy the new one again
			$torrent_file = JPATH_SITE.DIRECTORY_SEPARATOR.$params->get('torrent_dir').$temp_torrent['fid']."_".$temp_torrent['filename'].'.torrent';
			if (!$torrent->save($torrent_file)) $app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$temp_torrent['fid']), JText::_('COM_TRACKER_UPLOAD_PROBLEM_MOVING_FILE'), 'error');

		}

		// And we should also move the image file if we're using it with the option of uploading an image file
		if ($params->get('use_image_file') && ($_POST['default_image_type'] == 1) && isset($_FILES['image_file_file']['tmp_name'])) {
			// We first delete the old image
			@unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['old_image']);
			if (!move_uploaded_file($_FILES['image_file_file']['tmp_name'], JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$image_file_query_value)) $upload_error = 1;
		}

		// If we choose to switch between a local image file and an external link
		if ($params->get('use_image_file') && ($_POST['default_image_type'] == (2 || 3))) {
			if (file_exists('file://' . JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['old_image'])) {
				@unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images/tracker/torrent_image/'.$_POST['old_image']);
			}
		}

		// If we're in freeleech we need to edit the record of the torrent in the freeleech table
		if ($params->get('freeleech') == 1) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->update('#__tracker_torrents_freeleech')
				  ->set('download_multiplier = '.$temp_torrent['download_multiplier'])
				  ->where('fid = ' . (int) $temp_torrent['fid']);
			$db->setQuery( $query );
			if (!$db->execute()) {
				JError::raiseError(500, $db->getErrorMsg());
			}
		}

		if ($upload_error == 0) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$temp_torrent['fid']), JText::_('COM_TRACKER_EDIT_OK'), 'message');
		} else {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=edit&id='.$temp_torrent['fid']), JText::_('COM_TRACKER_EDIT_NOK'), 'error');
		}

	}

}
