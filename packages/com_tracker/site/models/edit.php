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
jimport('joomla.application.component.helper');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

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

	public function &getItem($pk = null) {
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
		
		if ($params->get('enable_licenses')) {
			// Join the torrent license
			$query->select('l.shortname AS torrent_license');
			$query->join('LEFT', '`#__tracker_licenses` AS l ON l.id = a.licenseID');
		}

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

		$db 			=& JFactory::getDBO();
		$user 			=& JFactory::getUser();
		$params 		=& JComponentHelper::getParams( 'com_tracker' );
		$app			= JFactory::getApplication();
		$upload_error 	= 0;

		$torrent_fid			= $_POST['fid'];
		$torrent_name 			= $_POST['name'];
		$torrent_filename 		= $_POST['filename'];
		$torrent_description 	= $_POST['description'];
		$torrent_categoryID 	= $_POST['categoryID'];
		
		// If we're using Licenses
		if ($params->get('use_licenses') == 1) $torrent_license = $_POST['licenseID'];
		else $torrent_licenseID = 0;
		
		// IF we're allowing anonymous uploaders names
		if ($params->get('allow_upload_anonymous') == 1) $uploader_anonymous = $_POST['uploader_anonymous'];
		else $uploader_anonymous = 0;
		
		// If we're using the multiplier mod 
		if ($params->get('torrent_multiplier') == 1) $torrent_upload_multiplier = $_POST['upload_multiplier'];
		else $torrent_upload_multiplier = 1;
		
		// If we're in freeleech
		if ($params->get('freeleech') == 1) $torrent_download_multiplier = 0;
		else $torrent_download_multiplier = $_POST['download_multiplier'];
		
		// If we're using the Forum Post
		if ($params->get('forum_post_id') == 1) $torrent_forum_post = $_POST['forum_post'];
		else $torrent_forum_post = 0;

		// If we're using the Torrent Information
		if ($params->get('torrent_information') == 1) $torrent_info_post = $_POST['info_post'];
		else $torrent_info_post = 0;

		// ------------------------------------------------------------------------------------------------------------------------
/*	Image file still to implement
		if ($params->get('use_image_file') == 1 && $params->get('image_width') > 0) {
			$image = $_FILES["image_file"];
			$tmpimagename = $image["tmp_name"];

			if (!is_uploaded_file($tmpimagename)) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_OPS_SOMETHING_HAPPENED_IMAGE'), 'error');
			}

			if (!filesize($tmpimagename)) {
				$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_UPLOAD_EMPTY_FILE_IMAGE'), 'error');
			}

			$image_file_query_field_name = ", image_file";
			$image_file_extension = end(explode(".", $_FILES["image_file"]["name"])); 
			$image_file_query_value = preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).'.'.$image_file_extension;
			$image_file_file = $image["tmp_name"];

		} else {
			$image_file_query_field_name = ", image_file";
			$image_file_query_value = "";
		}
*/
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		// All is good, let's update the record in the database
		// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


		$query = $db->getQuery(true);
		$query->update('#__tracker_torrents');
		$query->set('name = '.$db->quote($torrent_name));
		$query->set('alias = '.$db->quote($torrent_name));
		$query->set('filename = '.$db->quote($torrent_filename));
		$query->set('description = '.$db->quote($torrent_description));
		$query->set('categoryID = '.$db->quote($torrent_categoryID));
		$query->set('licenseID = '.$db->quote($torrent_licenseID));
		$query->set('uploader_anonymous = '.$db->quote($uploader_anonymous));
		$query->set('download_multiplier = '.$db->quote($torrent_download_multiplier));
		$query->set('upload_multiplier = '.$db->quote($torrent_upload_multiplier));
		$query->set('forum_post = '.$db->quote($torrent_forum_post));
		$query->set('info_post = '.$db->quote($torrent_info_post));
		$query->set('flags = 2');
		$query->where('fid = ' . (int) $torrent_fid);

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

/* Need to implement torrent image
		if ($params->get('use_image_file') && $params->get('image_width') > 0) {
			$torrent_image_file = JPATH_SITE.DS.$params->get('torrent_dir').$torrent_id."_".preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).".".$image_file_extension;
			$torrent_image_file_thumb = JPATH_SITE.DS.$params->get('torrent_dir').'thumb_'.$torrent_id."_".preg_replace('/[^0-9a-zA-Z\_\-\.]/','',$torrent_name).".".$image_file_extension;
			if (move_uploaded_file($image_file_file, $torrent_image_file)) {
			// Thumbnail generation
			$thumb = new easyphpthumbnail;
			// Set thumbsize - automatic resize for landscape or portrait
			$thumb -> Thumbsize = $params->get('image_width');
			// Create the thumbnail and output to screen
			$thumb -> Thumblocation = JPATH_SITE.DS.$params->get('torrent_dir');
			$thumb -> Thumbprefix = 'thumb_';
			$thumb -> Thumbsaveas = 'png';
			$thumb -> Thumbfilename = $torrent_image_file;
			$thumb -> Createthumb($torrent_image_file,'file');
				$upload_error = $upload_error + 0;
			} else {
				$upload_error = $upload_error + 1;
			}
		}
*/

		if ($upload_error == 0) {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.$torrent_fid), JText::_('COM_TRACKER_EDIT_OK'), 'message');
		} else {
			$app->redirect(JRoute::_('index.php?option=com_tracker&view=upload'), JText::_('COM_TRACKER_EDIT_NOK'), 'error');
		}

	}

}
