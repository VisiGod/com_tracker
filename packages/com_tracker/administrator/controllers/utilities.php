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

class TrackerControllerUtilities extends JControllerAdmin {

	protected $text_prefix = 'COM_TRACKER_UTILITIES';

	public function getModel($name = 'Utilities', $prefix = 'TrackerModel', $config = array()) {
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	public function clearannounce() {
		$model = $this->getModel();

		if (!$model->clearannounce()) JError::raiseWarning(500, $model->getError());
		else $this->setMessage(JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES_ANNOUNCE_LOG_OPTIMIZED'));

		$this->setRedirect('index.php?option=com_tracker&view=utilities');
	}

	public function optimizetables() {
		$model = $this->getModel();

		if (!$model->optimizetables()) JError::raiseWarning(500, $model->getError());
		else $this->setMessage(JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES_TABLES_WERE_OPTIMIZED'));

		$this->setRedirect('index.php?option=com_tracker&view=utilities');
	}

	public function importgroups() {
		$model = $this->getModel();

		if (!$model->importgroups()) JError::raiseWarning(500, $model->getError());
		else $this->setMessage(JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES_GROUPS_IMPORTED'));

		$this->setRedirect('index.php?option=com_tracker&view=utilities');
	}
	
	public function enable_free_leech() {
		$model = $this->getModel();
	
		if (!$model->enable_free_leech()) JError::raiseWarning(500, $model->getError());
		else $this->setMessage(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_ENABLED'));
	
		$this->setRedirect('index.php?option=com_tracker&view=utilities');
	}

	public function disable_free_leech() {
		$model = $this->getModel();
	
		if (!$model->disable_free_leech()) JError::raiseWarning(500, $model->getError());
		else $this->setMessage(JText::_( 'COM_TRACKER_UTILITY_FREE_LEECH_DISABLED'));
	
		$this->setRedirect('index.php?option=com_tracker&view=utilities');
	}
	
}
