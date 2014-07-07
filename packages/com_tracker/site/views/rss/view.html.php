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

	public function display($cachable = false, $urlparams = false) {
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$params		= $app->getParams();

		// Initialise variables
		$items		= $this->get('Items');

		// No guests allowed
		if (!$params->get('allow_guest') && $user->get('guest')) {
			$app->redirect('index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('items', $items);
		$this->assignRef('params', 	$params);
		
		$rss = JRequest::getVar('rss');
		
		if (!empty($rss)) {
			header('Content-Type: application/rss+xml; charset=ISO-8859-1');
			echo TrackerHelper::getRSS($items[0]);
			die();
		} else parent::display();
		
	}

}
