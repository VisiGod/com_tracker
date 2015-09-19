<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class TrackerModelRSS extends JModelList {

	protected function populateState($ordering = 'ordering', $direction = 'DESC') {
		$app = JFactory::getApplication();

		// Load the rss id from the request.
		$rss = JRequest::getInt('rss');
		$this->setState('rss', $rss);

		// Load user password hash from the request.
		$hash = JRequest::getString('hash');
		$this->setState('hash', $hash);

		// Load user uid from the request.
		$uid = JRequest::getInt('uid');
		$this->setState('uid', $uid);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}

	protected function getListQuery($rss = null, $hash = null, $uid = null) {
		// Initialise variables.
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$rss = (!empty($rss)) ? $rss : (int) $this->getState('rss');
		$hash = (!empty($hash)) ? $hash : $this->getState('hash');
		$uid = (!empty($uid)) ? $uid : (int) $this->getState('uid');

		// Generate the hash for the user if it doesn't exists
		if ($uid) TrackerHelper::check_user_hash($uid);

		// If we don't request a specific rss we will get the default page with the available RSS's
		if (empty($rss) && empty($hash) && empty($uid)) {
			$query->select('r.id, r.name, r.channel_title, r.channel_description, r.rss_authentication, r.rss_type, r.item_count')
				  ->from('#__tracker_rss AS r')
				  ->where('r.state = 1');

			// Join on the tracker user table.
			$query->select('tu.id as uid, tu.hash as hash')
				  ->join('LEFT', '#__tracker_users AS tu on tu.id = '.$user->get('id'));
			return $query;
		} elseif (!empty($rss) && empty($hash) && empty($uid)) {
			// check for guest accessible RSS
			$query->select('r.name, r.channel_title, r.channel_description, r.rss_type')
				  ->select('r.rss_type_items, r.item_count, r.item_title, r.item_description')
				  ->from('#__tracker_rss AS r')
				  ->where('r.id = "'.$rss.'"')
				  ->where('r.rss_authentication = 0')
			// Join on user table.
				  ->select('u.username as user')
				  ->join('LEFT', '#__users AS u on u.id = r.created_user_id');
			return $query;
		} elseif (empty($rss) && (!empty($hash) || !empty($uid))) {
			return $app->redirect(JURI::base(), JText::_('COM_TRACKER_RSS_UNKOWN_RSS'), 'error');
		} else {
			// We need to check if the RSS is restricted by group
			$query->select('r.rss_authentication, r.rss_authentication_items')
				  ->from('#__tracker_rss AS r')
				  ->where('r.id = "'.$rss.'"');
			$db->setQuery($query);
			$rss_authentication = $db->loadObject();

			// First we check if the user and the hash match with the given hash and user
			// And also check if the group matches if the RSS authentication is group based
			$query->select('tu.hash, tu.groupID')
				  ->from('#__tracker_users AS tu')
				  ->where('tu.hash = "'.$hash.'"')
				  ->where('tu.id = '.$uid);
			if ($rss_authentication->rss_authentication == 2) $query->where('tu.groupID IN ('.$rss_authentication->rss_authentication_items.')');
			$db->setQuery($query);
			$data = $db->loadObject();

			// Wrong RSS Auth... user doesnt match hash
			if (empty($data) || empty($rss)) {
				return $app->redirect(JURI::base(), JText::_('COM_TRACKER_RSS_WRONG_AUTH'), 'error');
			}

			$query->clear();
			$query->select('r.name, r.channel_title, r.channel_description, r.rss_type')
				  ->select('r.rss_type_items, r.item_count, r.item_title, r.item_description')
				  ->from('#__tracker_rss AS r')
				  ->where('r.id = "'.$rss.'"')
				  ->select('u.username as user')
				  ->join('LEFT', '#__users AS u on u.id = r.created_user_id');
			return $query;
		}
	}
}
