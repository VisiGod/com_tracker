<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

class TrackerControllerUsers extends JControllerAdmin {

	protected $text_prefix = 'COM_TRACKER_USERS';

	public function getModel($name = 'User', $prefix = 'TrackerModel', $config = array()) {
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	public function __construct($config = array()) {
		parent::__construct($config);

		$this->registerTask('leech',		'changeLeech');
		$this->registerTask('unleech',		'changeLeech');
	}

	public function changeLeech() {
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('leech' => 1, 'unleech' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('COM_TRACKER_USER_NO_USER_SELECTED'));
		} else {
			// Get the model.
			$model = $this->getModel();
			// Change the state of the records.
			if (!$model->leech($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			} else {
				if ($value == 1){
					$this->setMessage(JText::_('COM_TRACKER_USER_CAN_LEECH_YES'));
				} else if ($value == 0){
					$this->setMessage(JText::_('COM_TRACKER_USER_CAN_LEECH_NO'));
				}
			}
		}
		$this->setRedirect('index.php?option=com_tracker&view=users');
	}

}