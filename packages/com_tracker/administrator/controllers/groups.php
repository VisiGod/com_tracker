<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');

class TrackerControllerGroups extends JControllerAdmin {

	public function getModel($name = 'group', $prefix = 'TrackerModel', $config = array()) {
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	public function saveOrderAjax() {
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');
	
		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);
	
		// Get the model
		$model = $this->getModel();
	
		// Save the ordering
		$return = $model->saveorder($pks, $order);
	
		if ($return) {
			echo "1";
		}
	
		// Close the application
		JFactory::getApplication()->close();
	}

	public function __construct($config = array()) {
		parent::__construct($config);

		$this->registerTask('view_torrents',		'changeTypeYes');
		$this->registerTask('no_view_torrents',		'changeTypeNo');
		$this->registerTask('edit_torrents',		'changeTypeYes');
		$this->registerTask('no_edit_torrents',		'changeTypeNo');
		$this->registerTask('delete_torrents',		'changeTypeYes');
		$this->registerTask('no_delete_torrents',		'changeTypeNo');
		$this->registerTask('upload_torrents',		'changeTypeYes');
		$this->registerTask('no_upload_torrents',		'changeTypeNo');
		$this->registerTask('download_torrents',		'changeTypeYes');
		$this->registerTask('no_download_torrents',		'changeTypeNo');
		$this->registerTask('can_leech',		'changeTypeYes');
		$this->registerTask('no_can_leech',		'changeTypeNo');
		$this->registerTask('view_comments',		'changeTypeYes');
		$this->registerTask('no_view_comments',	'changeTypeNo');
		$this->registerTask('write_comments',		'changeTypeYes');
		$this->registerTask('no_write_comments',	'changeTypeNo');
		$this->registerTask('edit_comments',		'changeTypeYes');
		$this->registerTask('no_edit_comments',	'changeTypeNo');
		$this->registerTask('delete_comments',		'changeTypeYes');
		$this->registerTask('no_delete_comments',	'changeTypeNo');
		$this->registerTask('autopublish_comments',		'changeTypeYes');
		$this->registerTask('no_autopublish_comments',	'changeTypeNo');
		$this->registerTask('state',		'changeTypeYes');
		$this->registerTask('no_state',		'changeTypeNo');
	}

	public function changeTypeNO() {
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$this->task = ltrim($this->task, 'no_');

		if (empty($ids)) JError::raiseWarning(500, JText::_('COM_TRACKER_GROUPS_NO_GROUP_SELECTED'));
		else {
			// Remove the ability to leech from the users of the specified group
			if ($this->task == 'can_leech') TrackerHelper::changeUsersPermission('can_leech', $ids, 0);
			// Get the model.
			$model = $this->getModel();
			// Change the state of the records.
			if (!$model->changeValue($ids, $this->task, 0)) JError::raiseWarning(500, $model->getError());
			else $this->setMessage(JText::_('COM_TRACKER_GROUP_CHANGE_'.strtoupper($this->task).'_NO'));
		}
		$this->setRedirect('index.php?option=com_tracker&view=groups');
	}

	public function changeTypeYES() {
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');

		if (empty($ids)) JError::raiseWarning(500, JText::_('COM_TRACKER_GROUPS_NO_GROUP_SELECTED'));
		else {
			// Add the ability to leech from the users of the specified group
			if ($this->task == 'can_leech') TrackerHelper::changeUsersPermission('can_leech', $ids, 1);
			// Get the model.
			$model = $this->getModel();
			// Change the state of the records.
			if (!$model->changeValue($ids, $this->task, 1)) JError::raiseWarning(500, $model->getError());
			else $this->setMessage(JText::_('COM_TRACKER_GROUP_CHANGE_'.strtoupper($this->task).'_YES'));
		}
		$this->setRedirect('index.php?option=com_tracker&view=groups');
	}

}