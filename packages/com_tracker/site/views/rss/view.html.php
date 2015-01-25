<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class TrackerViewRSS extends JViewLegacy {

	protected $state = null;
	protected $item = null;

	public function display($tpl = null) {
		$app		= JFactory::getApplication();

		// Initialise variables
		$this->items		= $this->get('Items');
		$this->params		= $app->getParams();
		$this->user			= JFactory::getUser();

		// No guests allowed when no rss items exist
		if (!$this->params->get('allow_guest') && $this->user->get('guest') && count($this->items) == 0) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// Check for empty rss feed (if we are a guest and try to get a logged in user rss feed
		if (sizeof($this->items) == 0) {
			$app->redirect('index.php', JText::_('COM_TRACKER_RSS_UNKOWN_RSS'), 'error');
		}

		$rss	= JRequest::getVar('rss');

		if (!empty($rss)) {
			
			// Change description to be viewer friendly
			$this->items[0]->item_description = nl2br($this->items[0]->item_description);

			header('Content-Type: application/rss+xml; '.mb_internal_encoding());
			echo TrackerHelper::getRSS($this->items[0]);
			die();
		} else parent::display($tpl);
	}
}
