<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.modelform');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

class TrackerModelComment extends JModelForm {

	public function getForm($data = array(), $loadData = true) {

		$torrentid = JRequest::getInt('id', 0);
		$torrentname = base64_decode(JRequest::getString('name'));

		// Get the form.
		$form = $this->loadForm('com_tracker.comment', 'comment', array('control' => 'jform', 'load_data' => true));
		$form->torrentid = $torrentid;
		$form->torrentname = $torrentname;
		if (empty($form)) return false;
		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tracker.comment.commented.data', array());
		return $data;
	}

	public function commented() {

		$app			= JFactory::getApplication();
		$db 		= JFactory::getDBO();

		$torrentid = (int)$_POST['torrentid'];
		$userid = (int)$_POST['userid'];
		$description = $_POST['jform']['description'];

		$query = $db->getQuery(true);
		$query->insert('#__tracker_comments');
		$query->set('torrentid = '.$db->quote($torrentid));
		$query->set('userid = '.$db->quote($userid));
		$query->set('commentdate = '.$db->quote(date("Y-m-d")));
		$query->set('description = '.$db->quote($description));
		if (TrackerHelper::user_permissions('autopublish_comments', $userid, 1)) $query->set('state = 1');
		else $query->set('state = 0');
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
		}

		$app->redirect(JRoute::_('index.php?option=com_tracker&view=torrent&id='.(int)$torrentid), JText::_('COM_XBT_TRACKER_COMMENT_COMMENT_ADDED'), 'notice');
	}

}
