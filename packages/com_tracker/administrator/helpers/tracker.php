<?php
/**
 * @version			2.5.12-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

abstract class TrackerHelper {

	public static function addSubmenu($submenu) {
		$params = JComponentHelper::getParams( 'com_tracker' );

		JSubMenuHelper::addEntry(JText::_('COM_TRACKER_CONTROL_PANEL'), 'index.php?option=com_tracker', $submenu == 'trackerpanel');
		JSubMenuHelper::addEntry(JText::_('COM_TRACKER_TORRENTS'), 'index.php?option=com_tracker&view=torrents', $submenu == 'torrents');
		JSubMenuHelper::addEntry(JText::_('JCATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_tracker', $submenu == 'categories');
		JSubMenuHelper::addEntry(JText::_('COM_TRACKER_USERS'), 'index.php?option=com_tracker&view=users', $submenu == 'users');
		JSubMenuHelper::addEntry(JText::_('COM_TRACKER_GROUPS'), 'index.php?option=com_tracker&view=groups', $submenu == 'groups');
		if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') JSubMenuHelper::addEntry(JText::_('COM_TRACKER_COMMENTS'), 'index.php?option=com_tracker&view=comments',  $submenu == 'comments');
		if ($params->get('enable_donations')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_DONATIONS'), 'index.php?option=com_tracker&view=donations',  $submenu == 'donations');
		if ($params->get('enable_licenses')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_LICENSES'), 'index.php?option=com_tracker&view=licenses', $submenu == 'licenses');
		if ($params->get('enable_countries')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_COUNTRIES'), 'index.php?option=com_tracker&view=countries', $submenu == 'countries');
		if ($params->get('peer_banning')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_BANCLIENTS'), 'index.php?option=com_tracker&view=banclients', $submenu == 'banclients');
		if ($params->get('host_banning')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_BANHOSTS'), 'index.php?option=com_tracker&view=banhosts', $submenu == 'banhosts');
		if ($params->get('enable_thankyou')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_THANKYOUS'), 'index.php?option=com_tracker&view=thankyous', $submenu == 'thankyous');
		if ($params->get('enable_filetypes')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_FILETYPES'), 'index.php?option=com_tracker&view=filetypes', $submenu == 'filetypes');
		if ($params->get('enable_reseedrequest')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_RESEEDS'), 'index.php?option=com_tracker&view=reseeds', $submenu == 'reseeds');
		if ($params->get('enable_reporttorrent')) JSubMenuHelper::addEntry(JText::_('COM_TRACKER_REPORTS'), 'index.php?option=com_tracker&view=reports', $submenu == 'reports');
		JSubMenuHelper::addEntry(JText::_('COM_TRACKER_SETTINGS'), 'index.php?option=com_tracker&view=settings', $submenu == 'settings');
		JSubMenuHelper::addEntry( JText::_('COM_TRACKER_UTILITIES'), 'index.php?option=com_tracker&view=utilities', $submenu == 'utilities');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-trackerpanel {background-image: url(components/com_tracker/images/panel/logo-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-torrents {background-image: url(components/com_tracker/images/panel/torrent-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-users {background-image: url(components/com_tracker/images/panel/tuser-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-groups {background-image: url(components/com_tracker/images/panel/group-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-comments {background-image: url(components/com_tracker/images/panel/comments-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-donations {background-image: url(components/com_tracker/images/panel/donations-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-licenses {background-image: url(components/com_tracker/images/panel/licenses-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-countries {background-image: url(components/com_tracker/images/panel/countries-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-clientban {background-image: url(components/com_tracker/images/panel/clientban-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-ipban {background-image: url(components/com_tracker/images/panel/ipban-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-thankyou {background-image: url(components/com_tracker/images/panel/thankyou-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-filetype {background-image: url(components/com_tracker/images/panel/filetype-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-reseed {background-image: url(components/com_tracker/images/panel/reseed-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-report {background-image: url(components/com_tracker/images/panel/report-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-settings {background-image: url(components/com_tracker/images/panel/settings-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-utilities {background-image: url(components/com_tracker/images/panel/utilities-48x48.png);}');
	}

	public static function getActions($Id = 0, $Asset = NULL) {
		$user	= JFactory::getUser();
		$result	= new JObject;
 
		if($Asset == NULL) {
			$assetName = 'com_tracker';
			$actions = array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete', 'core.edit.state', 'core.edit.state');
		} else {
			$assetName = 'com_tracker.'. $Asset . '.' . (int) $Id;
			$actions = array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete');
		}
 
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 
		return $result;
	}

	public static function make_size($bytes) {
		if ($bytes < 1000 * 1024) return number_format($bytes / 1024, 2) . ' '.JText::_( 'COM_TRACKER_KILOBYTES' );
		elseif ($bytes < 1000 * 1048576) return number_format($bytes / 1048576, 2) . ' '.JText::_( 'COM_TRACKER_MEGABYTES' );
		elseif ($bytes < 1000 * 1073741824) return number_format($bytes / 1073741824, 2) . ' '.JText::_( 'COM_TRACKER_GIGABYTES' );
		else return number_format($bytes / 1099511627776, 2) . ' '.JText::_( 'COM_TRACKER_TERABYTES' );
	}

	public static function make_speed($bits) {
		if ($bits < 1000 * 1024) return number_format($bits / 1024, 2) . ' '.JText::_( 'COM_TRACKER_KILOBITS' );
		elseif ($bits < 1000 * 1048576) return number_format($bits / 1048576, 2) . ' '.JText::_( 'COM_TRACKER_MEGABITS' );
		elseif ($bits < 1000 * 1073741824) return number_format($bits / 1073741824, 2) . ' '.JText::_( 'COM_TRACKER_GIGABTIS' );
		else return number_format($bits / 1099511627776, 2) . ' '.JText::_( 'COM_TRACKER_TERABITS' );
	}
	
	public static function make_ratio($downloaded, $uploaded, $clean='0') {
		if ($downloaded > 0 && $uploaded > 0) {
			$temp_ratio = number_format(($uploaded/$downloaded), 2, '.', ' ');
			if ($clean = 1) return $temp_ratio;
			if ($temp_ratio < 1 ) return "<font color='red'><b>".$temp_ratio."</b></font>";
			else return "<font color='blue'><b>".$temp_ratio."</b></font>";
		}
		elseif ($clean = 1) return 0;
		elseif ($downloaded < 1 && $uploaded > 0) return '<b>'.JText::_( 'COM_TRACKER_SEED' ).'</b>';
		elseif ($downloaded < 1 && $uploaded < 1) return JText::_( 'COM_TRACKER_NONE' );
		elseif ($downloaded > 0 && $uploaded < 1) return '<b>'.JText::_( 'COM_TRACKER_LEECH' ).'</b>';
		else return JText::_( 'COM_TRACKER_UNKNOWN' );
	}

	public static function getGroups() {
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('a.id AS value, a.name AS text');
		$query->from('`#__tracker_groups` AS a');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option) {
			$option->text = '- '.$option->text;
		}

		return $options;
	}

	public static function getAllCountries() {
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('a.id AS value, a.name AS text');
		$query->from('`#__tracker_countries` AS a');
		$query->order('a.name ASC');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option) {
			$option->text = '- '.$option->text;
		}

		return $options;
	}

	public static function getUsedCountries() {

		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		
		$query->select('DISTINCT(tu.countryID) AS value, c.name AS text');
		$query->from('`#__tracker_countries` AS c');
		$query->join('RIGHT', '`#__tracker_users` AS tu ON tu.countryID = c.id');
		$query->where('tu.countryID <> 0');
		$query->order('c.name ASC');
		
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option) {
			$option->text = '- '.$option->text;
		}

		return $options;
	}

	public static function user_permissions($type, $userid='0') {
		$db			= JFactory::getDBO();
		$query	= $db->getQuery(true);

		if (!$userid) {
			$params = JComponentHelper::getParams( 'com_tracker' );
			$userid = $params->get('guest_user');
		}

		$query->select('tg.'.$type);
		$query->from('`#__tracker_groups` AS tg');
		$query->join('LEFT', '`#__tracker_users` AS tu ON tu.groupID = tg.id');
		$query->where('tu.id = '.(int) $userid);

		$db->setQuery($query);
		$myuser = $db->loadResult();
		return $myuser;
	}

	public static function get_percent_completed_image($p) {
		$params = JComponentHelper::getParams( 'com_tracker' );
		$config = new JConfig();

		if ($p == 0) $progress = "<img src='".JURI::base()."components/com_tracker/assets/images/progbar-rest.gif' style='height: 9px' width='".$params->get('progress_bar_size')."' alt='".$config->sitename."'/>";
		if ($p >= 100) $progress = "<img src='".JURI::base()."components/com_tracker/assets/images/progbar-green.gif' style='height: 9px' width='".$params->get('progress_bar_size')."' alt='".$config->sitename."'/>";
		if ($p >= 1 && $p <= 30) $progress = "<img src='".JURI::base()."components/com_tracker/assets/images/progbar-red.gif' style='height: 9px' width='".round($p*($params->get('progress_bar_size')/100))."' alt='".$config->sitename."'/><img src='".JURI::base()."components/com_tracker/assets/images/progbar-rest.gif' style='height: 9px' width='".round((100-$p)*($params->get('progress_bar_size')/100))."' alt='".$config->sitename."'/>";
		if ($p >= 31 && $p <= 65) $progress = "<img src='".JURI::base()."components/com_tracker/assets/images/progbar-yellow.gif' style='height: 9px' width='".round($p*($params->get('progress_bar_size')/100))."' alt='".$config->sitename."'/><img src='".JURI::base()."components/com_tracker/assets/images/progbar-rest.gif' style='height: 9px' width='".round((100-$p)*($params->get('progress_bar_size')/100))."' alt='".$config->sitename."'/>";
		if ($p >= 66 && $p <= 99) $progress = "<img src='".JURI::base()."components/com_tracker/assets/images/progbar-green.gif' style='height: 9px' width='".round($p*($params->get('progress_bar_size')/100))."' alt='".$config->sitename."'/><img src='".JURI::base()."components/com_tracker/assets/images/progbar-rest.gif' style='height: 9px' width='".round((100-$p)*($params->get('progress_bar_size')/100))."' alt='".$config->sitename."'/>";
			return "<img src='".JURI::base()."components/com_tracker/assets/images/bar_left.gif' alt='".$config->sitename."'/>" . $progress ."<img src='".JURI::base()."components/com_tracker/assets/images/bar_right.gif' alt='".$config->sitename."'/>";
	}

	public static function sanitize_filename($str, $relative_path = FALSE) {
		$bad = array(
			'../', '<!--', '-->', '<', '>',
			"'", '"', '&', '$', '#',
			'{', '}', '[', ']', '=',
			';', '?', '%20', '%22',
			'%3c',      // <
			'%253c',    // <
			'%3e',      // >
			'%0e',      // >
			'%28',      // (
			'%29',      // )
			'%2528',    // (
			'%26',      // &
			'%24',      // $
			'%3f',      // ?
			'%3b',      // ;
			'%3d'       // =
		);

		if ( ! $relative_path) {
			$bad[] = './';
			$bad[] = '/';
		}

		$str = TrackerHelper::remove_invisible_characters($str, FALSE);
		return stripslashes(str_replace($bad, '', $str));
	}

	public static function remove_invisible_characters($str, $url_encoded = TRUE) {
		$non_displayables = array();

		// every control character except newline (dec 10),
		// carriage return (dec 13) and horizontal tab (dec 09)
		if ($url_encoded) {
			$non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127

		do {
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}

	public static function last_activity($mtime, $timetype='0', $long='0') {
		if ($timetype == 1) {
			list($date, $time) = explode(' ', $mtime);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);
			$mtime = mktime($hour, $minute, $second, $month, $day, $year);
		}
		
		$reg = date("Y-m-d-H-i-s", $mtime);
		list($year, $month, $day, $hour, $minute, $second) = explode('-', $reg);
		$century = mktime($hour, $minute, $second, $month, $day, $year);
		$difference = time() - $century;
		$last_activity = '';
		if ($difference > 31536000) {
			$last_activity .= floor($difference / 31536000).'&nbsp;';
			$difference -= 31536000 * floor($difference / 31536000);
			$last_activity .= ($long == 1) ? JText::_( 'COM_TRACKER_YEAR' ).'&nbsp;' : JText::_( 'COM_TRACKER_YEAR_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 2419200) {
			$last_activity .= floor($difference / 2419200).'&nbsp;';
			$difference -= 2419200 * floor($difference / 2419200);
			$last_activity .= ($long == 1) ? JText::_( 'COM_TRACKER_MONTH' ).'&nbsp;' : JText::_( 'COM_TRACKER_MONTH_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 604800) {
			$last_activity .= floor($difference / 604800).'&nbsp;';
			$difference -= 604800 * floor($difference / 604800);
			$last_activity .= ($long == 1) ? JText::_( 'COM_TRACKER_WEEK' ).'&nbsp;' : JText::_( 'COM_TRACKER_WEEK_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 86400) {
			$last_activity .= floor($difference / 86400).'&nbsp;';
			$difference -= 86400 * floor($difference / 86400);
			$last_activity .= ($long == 1) ? JText::_( 'COM_TRACKER_DAY' ).'&nbsp;' : JText::_( 'COM_TRACKER_DAY_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 3600) {
			$last_activity .= floor($difference / 3600).'&nbsp;';
			$difference -= 3600 * floor($difference / 3600);
			$last_activity .= ($long == 1) ? JText::_( 'COM_TRACKER_HOUR' ).'&nbsp;' : JText::_( 'COM_TRACKER_HOUR_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 60) {
			$last_activity .= floor($difference / 60).'&nbsp;';
			$difference -= 60 * floor($difference / 60);
			$last_activity .= ($long == 1) ? JText::_( 'COM_TRACKER_MINUTE' ).'&nbsp;' : JText::_( 'COM_TRACKER_MINUTE_SYMBOL' ).'&nbsp;';
			$last_activity .= JText::_( 'COM_TRACKER_AND' ).'&nbsp;';
		}
		$last_activity .= floor($difference).'&nbsp;';
		$last_activity .= ($long == 1) ? JText::_( 'COM_TRACKER_SECOND' ).'&nbsp;' : JText::_( 'COM_TRACKER_SECOND_SYMBOL' ).'&nbsp;';
		$last_activity .= JText::_( 'COM_TRACKER_AGO' );
		return $last_activity;
	}

	public static function relativeTime($time, $short = false) {
		// Check if this function is better than the last_activity
		$SECOND = 1;
		$MINUTE = 60 * $SECOND;
		$HOUR = 60 * $MINUTE;
		$DAY = 24 * $HOUR;
		$MONTH = 30 * $DAY;
		$before = time() - $time;

		if ($before < 0) {
			return "not yet";
		}

		if ($short) {
			if ($before < 1 * $MINUTE) return ($before <5) ? "just now" : $before . " ago";
			if ($before < 2 * $MINUTE) return "1m ago";
			if ($before < 45 * $MINUTE) return floor($before / 60) . "m ago";
			if ($before < 90 * $MINUTE) return "1h ago";
			if ($before < 24 * $HOUR) return floor($before / 60 / 60). "h ago";
			if ($before < 48 * $HOUR) return "1d ago";
			if ($before < 30 * $DAY) return floor($before / 60 / 60 / 24) . "d ago";
			if ($before < 12 * $MONTH) {
				$months = floor($before / 60 / 60 / 24 / 30);
				return $months <= 1 ? "1mo ago" : $months . "mo ago";
			} else {
				$years = floor  ($before / 60 / 60 / 24 / 30 / 12);
				return $years <= 1 ? "1y ago" : $years."y ago";
			}
		}

		if ($before < 1 * $MINUTE) return ($before <= 1) ? "just now" : $before . " seconds ago";
		if ($before < 2 * $MINUTE) return "a minute ago";
		if ($before < 45 * $MINUTE) return floor($before / 60) . " minutes ago";
		if ($before < 90 * $MINUTE) return "an hour ago";
		if ($before < 24 * $HOUR) return (floor($before / 60 / 60) == 1 ? 'about an hour' : floor($before / 60 / 60).' hours'). " ago";
		if ($before < 48 * $HOUR) return "yesterday";
		if ($before < 30 * $DAY) return floor($before / 60 / 60 / 24) . " days ago";
		if ($before < 12 * $MONTH) {
			$months = floor($before / 60 / 60 / 24 / 30);
			return $months <= 1 ? "one month ago" : $months . " months ago";
		} else {
			$years = floor  ($before / 60 / 60 / 24 / 30 / 12);
			return $years <= 1 ? "one year ago" : $years." years ago";
		}

		return $time;
	}

	public static function traffic_per_day($traffic, $id) {
		jimport('joomla.user.user');
		JLoader::register('JTableUser', JPATH_PLATFORM.'/joomla/database/table/user.php');
		$db	= JFactory::getDBO();
		
		$user = JFactory::getUser($id);
		
		list($date, $time) = explode(' ', $user->registerDate);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
		$regdate = mktime($hour, $minute, $second, $month, $day, $year);
	
		$regged_days = ((time() - $regdate) / 86400); // translate the unixtime that is in seconds to days...
	
		if ($regged_days > 0) $ul_bytes_per_day = TrackerHelper::make_size($traffic/$regged_days);
		else $ul_bytes_per_day = 0;
	
		return $ul_bytes_per_day;
	}

	public static function get_ratio($upload, $download) {
		if ($upload == 0 && $download == 0) return JText::_( 'COM_TRACKER_NO_DOWNLOAD_UPLOAD' );
		elseif ($upload > 0 && $download < 1) return JText::_( 'COM_TRACKER_JUST_SEEDED' );
		elseif ($upload < 1 && $download > 0) return JText::_( 'COM_TRACKER_JUST_LEECHEED' );
		else return '<span style="color: ' . TrackerHelper::get_ratio_color(($upload/$download)) . ';">'.number_format(($upload/$download), 2).'</span>';
	}
	
	public static function get_ratio_color($ratio) {
		if ($ratio < 0.1) return "#ff0000";
		elseif ($ratio < 0.2) return "#ee0000";
		elseif ($ratio < 0.3) return "#dd0000";
		elseif ($ratio < 0.4) return "#cc0000";
		elseif ($ratio < 0.5) return "#bb0000";
		elseif ($ratio < 0.6) return "#aa0000";
		elseif ($ratio < 0.7) return "#990000";
		elseif ($ratio < 0.8) return "#880000";
		elseif ($ratio < 0.9) return "#770000";
		elseif ($ratio < 1) return "#660000";
		return "#000000";
	}

	public static function get_new_users() { // Insert new users into the tracker_users table
		$db 	= JFactory::getDBO();
		$params = JComponentHelper::getParams('com_tracker');
		$query	= $db->getQuery(true);
		
		$query->select('minimum_ratio, download_torrents, wait_time, peer_limit, torrent_limit, download_multiplier, upload_multiplier');
		$query->from('#__tracker_groups');
		$query->where('id = '.(int)$params->get('base_group'));
		$db->setQuery($query);
		$base_group = $db->loadAssoc();
		
		$query->clear();
		$query->select('u.id as id');
		$query->from('#__users as u');
		$query->join('LEFT', '`#__tracker_users` AS tu ON u.id = tu.id');
		$query->where('tu.id is null');
		$db->setQuery($query);
		$newusers = $db->loadAssocList();

		foreach($newusers as $newuser) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->insert('#__tracker_users');

			$query->set('id = '.(int)$newuser['id']);
			$query->set('groupID = '.(int)$params->get('base_group'));
			$query->set('countryID = '.(int)$params->get('defaultcountry'));
			$query->set('downloaded = 0');
			$query->set('uploaded = '.($params->get('welcome_gigs') * 1073741824));
			$query->set('exemption_type = 2');
			$query->set('minimum_ratio = '.$base_group['minimum_ratio']);
			$query->set('can_leech = '.(int)$base_group['download_torrents']);
			$query->set('wait_time = '.(int)$base_group['wait_time']);
			$query->set('peer_limit = '.(int)$base_group['peer_limit']);
			$query->set('torrent_limit = '.(int)$base_group['torrent_limit']);
			$query->set('torrent_pass_version = 1');
			$query->set('multiplier_type = 0');
			$query->set('download_multiplier = '.$base_group['download_multiplier']);
			$query->set('upload_multiplier = '.$base_group['upload_multiplier']);
			$query->set('ordering = '.(int)$newuser['id']);
			$db->setQuery($query);
			$db->query();
		}

	}

	public static function make_wait_time($difference, $long) {
		$wait_time = '';
		if ($difference > 31536000) {
			$wait_time .= floor($difference / 31536000).'&nbsp;';
			$difference -= 31536000 * floor($difference / 31536000);
			$wait_time .= ($long == 1) ? JText::_( 'COM_TRACKER_YEAR' ).'&nbsp;' : JText::_( 'COM_TRACKER_YEAR_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 2419200) {
			$wait_time .= floor($difference / 2419200).'&nbsp;';
			$difference -= 2419200 * floor($difference / 2419200);
			$wait_time .= ($long == 1) ? JText::_( 'COM_TRACKER_MONTH' ).'&nbsp;' : JText::_( 'COM_TRACKER_MONTH_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 604800) {
			$wait_time .= floor($difference / 604800).'&nbsp;';
			$difference -= 604800 * floor($difference / 604800);
			$wait_time .= ($long == 1) ? JText::_( 'COM_TRACKER_WEEK' ).'&nbsp;' : JText::_( 'COM_TRACKER_WEEK_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 86400) {
			$wait_time .= floor($difference / 86400).'&nbsp;';
			$difference -= 86400 * floor($difference / 86400);
			$wait_time .= ($long == 1) ? JText::_( 'COM_TRACKER_DAY' ).'&nbsp;' : JText::_( 'COM_TRACKER_DAY_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 3600) {
			$wait_time .= floor($difference / 3600).'&nbsp;';
			$difference -= 3600 * floor($difference / 3600);
			$wait_time .= ($long == 1) ? JText::_( 'COM_TRACKER_HOUR' ).'&nbsp;' : JText::_( 'COM_TRACKER_HOUR_SYMBOL' ).'&nbsp;';
		}
		if ($difference > 60) {
			$wait_time .= floor($difference / 60).'&nbsp;';
			$difference -= 60 * floor($difference / 60);
			$wait_time .= ($long == 1) ? JText::_( 'COM_TRACKER_MINUTE' ).'&nbsp;' : JText::_( 'COM_TRACKER_MINUTE_SYMBOL' ).'&nbsp;';
			$wait_time .= JText::_( 'COM_TRACKER_AND' ).'&nbsp;';
		}
		$wait_time .= floor($difference).'&nbsp;';
		$wait_time .= ($long == 1) ? JText::_( 'COM_TRACKER_SECOND' ).'&nbsp;' : JText::_( 'COM_TRACKER_SECOND_SYMBOL' ).'&nbsp;';
		return $wait_time;
	}

	public static function comments($torrent_id, $torrent_name) {
		$db 	= JFactory::getDBO();
		$params = JComponentHelper::getParams('com_tracker');
		
		if ($params->get('comment_system') == 'jcomments') {
			$comments = JPATH_SITE . DS .'components' . DS . 'com_jcomments' . DS . 'jcomments.php';
			if (file_exists($comments)) {
				require_once($comments);
				echo JComments::showComments($torrent_id, 'com_tracker', $torrent_name);
			}
		}
	}

	public static function checkThanks($userID, $torrentID) {
		$db 	= JFactory::getDBO();
		
		$query	= $db->getQuery(true);
		$query->select('uid');
		$query->from('#__tracker_torrent_thanks');
		$query->where('uid ='.(int)$userID);
		$query->where('torrentID ='.(int)$torrentID);
		$db->setQuery($query);
		if ($db->loadResult()) return 0;
		else return $userID;
	}

	public static function getLastOrder($tablename) { // Get the last ordering from the table we choose
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('MAX(ordering)');
		$query->from('`#__'.$tablename.'`');
		$db->setQuery($query);
		$max = $db->loadResult();
		return $max+1;
	}

	public static function update_parameter($name, $value) { // Update a parameter value by name 
		// retrieve existing params
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('params');
		$query->from('`#__extensions`');
		$query->where('name = "com_tracker"');
		$db->setQuery($query);
		$params = json_decode( $db->loadResult(), true );
	
		// change the parameter value 
		$params[$name] = $value;
	
		// store the combined result
		$paramsString = json_encode( $params );
		$query->clear();
		
		$query->update('#__extensions');
		$query->set('params = '.$db->quote($paramsString));
		$query->where('name = "com_tracker"');
		$db->setQuery($query);
		$db->query();
	}

	public static function getFileImage($filename) {	// echos the filetype of the image
		$extension = JFile::getExt($filename);
		
		$filetype_imagelink = JURI::base().'/images/tracker/filetypes/'.$extension.'.png';
		$filetype_imagepath = $_SERVER['DOCUMENT_ROOT'].JUri::root(true).'/images/tracker/filetypes/'.$extension.'.png';
		
		if (is_file($filetype_imagepath)) echo '<img id="'.$filename.'" alt="'.$filename.'" src="'.$filetype_imagelink.'" width="60" />';
		else echo '<img id="'.$filename.'" alt="'.$filename.'" src="'.JUri::root(true).'/images/tracker/filetypes/default.png'.'" width="60" />';
	}

	public static function checkReseedRequest($userID, $torrentID) {
		$db 	= JFactory::getDBO();
	
		$query	= $db->getQuery(true);
		$query->select('requester');
		$query->from('#__tracker_reseed_request');
		$query->where('requester ='.(int)$userID);
		$query->where('fid ='.(int)$torrentID);
		$db->setQuery($query);
		if ($db->loadResult()) return 0;
		else return $userID;
	}

	public static function getCountryDetails($countryID) {
		$db 	= JFactory::getDBO();
		
		$query	= $db->getQuery(true);
		$query->select('name, image');
		$query->from('#__tracker_countries');
		$query->where('id ='.(int)$countryID);
		$db->setQuery($query);
		try {
			$default_country = $db->loadNextObject();
		} catch (Exception $e) {
			// $this->setError(JText::_( 'COM_TRACKER_CANT_GET_DEFAULT_COUNTRY'));
			jimport('joomla.log.log');
			JLog::add(JText::_('COM_TRACKER_CANT_GET_DEFAULT_COUNTRY'), JLog::NOTICE);

			return false;
		}
		return $default_country;
	}

	public static function checkReportedTorrent($userID, $torrentID) {
		$db 	= JFactory::getDBO();
	
		$query	= $db->getQuery(true);
		$query->select('reporter');
		$query->from('#__tracker_reported_torrents');
		$query->where('reporter ='.(int)$userID);
		$query->where('fid ='.(int)$torrentID);
		$db->setQuery($query);
		if ($db->loadResult()) return 0;
		else return $userID;
	}

	public static function checkTorrentType($torrentID) {
		$db 	= JFactory::getDBO();
		
		$query	= $db->getQuery(true);
		$query->select('download_multiplier, created_time, seeders');
		$query->from('#__tracker_torrents');
		$query->where('fid ='.(int)$torrentID);
		$db->setQuery($query);
		$torrent_type = $db->loadNextObject();

		$params = JComponentHelper::getParams( 'com_tracker' );
		
		// Check if torrent is free
		if ($params->get('enable_torrent_type_free')) {
			if ($torrent_type->download_multiplier == 0)
				echo '<img id="'.$torrentID.'free" alt="'.JText::_('COM_TRACKER_FREE').'" src="'.JURI::base().$params->get('torrent_type_free_image').'" />';
		}

		// Check if torrent is semi-free
		if ($params->get('enable_torrent_type_semifree')) {
			if ($torrent_type->download_multiplier <= $params->get('torrent_type_semifree_value') && $torrent_type->download_multiplier > 0)
				echo '<img id="'.$torrentID.'semifree" alt="'.JText::_('COM_TRACKER_SEMIFREE').'" src="'.JURI::base().$params->get('torrent_type_semifree_image').'" />';
		}

		// Check if torrent is new
		if ($params->get('enable_torrent_type_new')) {
			if ((date("U") - strtotime($torrent_type->created_time)) < ($params->get('torrent_type_new_value') * 3600))
				echo '<img id="'.$torrentID.'new" alt="'.JText::_('COM_TRACKER_NEW').'" src="'.JURI::base().$params->get('torrent_type_new_image').'" />';
		}

		// Now for the messy part. We need to check if top or hot isn't used and return only what's used
		// Or if both are used we need to compare the values to see which one we check first
		
		// If we're using only top value and not using hot value
		if ($params->get('enable_torrent_type_top') && !$params->get('enable_torrent_type_hot')) {
			if ($torrent_type->seeders >= $params->get('torrent_type_top_value'))
				echo '<img id="'.$torrentID.'top" alt="'.JText::_('COM_TRACKER_TOP').'" src="'.JURI::base().$params->get('torrent_type_top_image').'" />';
		}
		
		//But if we're only using hot value and not using top value
		if ($params->get('enable_torrent_type_hot') && !$params->get('enable_torrent_type_top')) {
			if ($torrent_type->seeders >= $params->get('torrent_type_hot_value'))
				echo '<img id="'.$torrentID.'hot" alt="'.JText::_('COM_TRACKER_HOT').'" src="'.JURI::base().$params->get('torrent_type_hot_image').'" />';
		}

		// To finish the mess, we're using both values (top and hot) and we need to check which one is bigger
		if ($params->get('enable_torrent_type_hot') && $params->get('enable_torrent_type_top')) {
			// If top value > hot value, we check top first
			if ($params->get('torrent_type_top_value') > $params->get('torrent_type_hot_value')) {
				if ($torrent_type->seeders >= $params->get('torrent_type_top_value'))
					echo '<img id="'.$torrentID.'top" alt="'.JText::_('COM_TRACKER_TOP').'" src="'.JURI::base().$params->get('torrent_type_top_image').'" />';
				elseif ($torrent_type->seeders >= $params->get('torrent_type_hot_value'))
					echo '<img id="'.$torrentID.'hot" alt="'.JText::_('COM_TRACKER_HOT').'" src="'.JURI::base().$params->get('torrent_type_hot_image').'" />';
			} else { // Or we check hot first
				if ($torrent_type->seeders >= $params->get('torrent_type_hot_value'))
					echo '<img id="'.$torrentID.'hot" alt="'.JText::_('COM_TRACKER_HOT').'" src="'.JURI::base().$params->get('torrent_type_hot_image').'" />';
				elseif ($torrent_type->seeders >= $params->get('torrent_type_top_value'))
					echo '<img id="'.$torrentID.'top" alt="'.JText::_('COM_TRACKER_TOP').'" src="'.JURI::base().$params->get('torrent_type_top_image').'" />';
			}
		}
	}

	public static function is_image($path) {
		$a = getimagesize($path);
		$image_type = $a[2];

		if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) return true;
		return false;
	}

	public static function SelectList($table, $value, $text, $state) {
		$db 	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($value.' as value');
		$query->select($text.' as text');
		$query->from('`#__tracker_'.$table.'`');
		if (isset($state)) $query->where('state = ' . (int) $state);
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public static function changeUsersPermission($permission, $groupID, $enable) {
		JArrayHelper::toInteger($groupID);
		$groupID = implode( ',', $groupID );
		
		$db 	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__tracker_users'));
		$query->set($db->quoteName($permission) . ' = '.$enable);
		$query->where($db->quoteName('groupID') . ' IN ('.$groupID.')');
		$query->where($db->quoteName('exemption_type') . ' = 2');
		$db->setQuery($query);
		
		try {
			$result = $db->query();
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
// ########################################################################################################################################
/*
	public static function checkComponentConfigured() {
		$db			= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select('name, value');
		$query->from('xbt_config');
		$db->setQuery( $query );
		$data = $db->loadObjectList('name');
		if ($data) return '&nbsp;-&nbsp;<img style="vertical-align:middle;" src="'.JURI::root(true).'/administrator/components/com_tracker/images/ok.png" />&nbsp;-&nbsp;'.JText::_( 'COM_TRACKER_PANEL_COMPONENT_CONFIGURED' );
		else return '&nbsp;-&nbsp;<img style="vertical-align:middle;" src="'.JURI::root(true).'/administrator/components/com_tracker/images/nok.png" />&nbsp;-&nbsp;'.JText::_( 'COM_TRACKER_PANEL_COMPONENT_NOT_CONFIGURED' );
	}

	public static function checkFolder($full_folder, $folder) {
		if (JFolder::exists($full_folder) && strlen($folder) > 0 && TrackerHelper::is_folder_writable($full_folder)) return '<img style="vertical-align:middle;" src="'.JURI::root(true).'/administrator/components/com_tracker/images/ok.png" />&nbsp;-&nbsp;'.JText::_( "COM_TRACKER_PANEL_DIRECTORY_EXIST" );
			else if (JFolder::exists($full_folder) && strlen($folder) > 0 && !TrackerHelper::is_folder_writable($full_folder)) return '<img style="vertical-align:middle;" src="'.JURI::root(true).'/administrator/components/com_tracker/images/nok.png" />&nbsp;-&nbsp;'.JText::_( "COM_TRACKER_PANEL_DIRECTORY_EXIST_CANT_WRITE" );
				else return '<img style="vertical-align:middle;" src="'.JURI::root(true).'/administrator/components/com_tracker/images/nok.png" />&nbsp;-&nbsp;'.JText::_( "COM_TRACKER_PANEL_DIRECTORY_DONT_EXIST" );
	}

	public static function is_folder_writable($path) {
	//will work in despite of Windows ACLs bug
	//NOTE: use a trailing slash for folders!!!
	//see http://bugs.php.net/bug.php?id=27609
	//see http://bugs.php.net/bug.php?id=30931
		if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
			return TrackerHelper::is_folder_writable($path.uniqid(mt_rand()).'.tmp');
		else if (is_dir($path))
			return TrackerHelper::is_folder_writable($path.'/'.uniqid(mt_rand()).'.tmp');
		// check tmp file for read/write capabilities
		$rm = file_exists($path);
		$f = @fopen($path, 'a');
		if ($f===false)
			return false;
		fclose($f);
		if (!$rm)
			unlink($path);
		return true;
	}

*/
}
