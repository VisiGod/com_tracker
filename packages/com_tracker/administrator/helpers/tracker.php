<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ADMINISTRATOR.'/components/com_tracker/helpers/RSSFeed.php';

class TrackerHelper extends JHelperContent {

	public static $extension = 'com_tracker';
	
	public static function addSubmenu($vName) {
		$params = JComponentHelper::getParams( 'com_tracker' );

		JHtmlSidebar::addEntry(JText::_('COM_TRACKER_CONTROL_PANEL'), 'index.php?option=com_tracker', 'trackerpanel');
		JHtmlSidebar::addEntry(JText::_('COM_TRACKER_TORRENTS'), 'index.php?option=com_tracker&view=torrents', $vName == 'torrents');
		JHtmlSidebar::addEntry(JText::_('JCATEGORIES'), 'index.php?option=com_categories&extension=com_tracker', $vName == 'categories');
		JHtmlSidebar::addEntry(JText::_('COM_TRACKER_USERS'), 'index.php?option=com_tracker&view=users', $vName == 'users');
		JHtmlSidebar::addEntry(JText::_('COM_TRACKER_GROUPS'), 'index.php?option=com_tracker&view=groups', $vName == 'groups');
		if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') JHtmlSidebar::addEntry(JText::_('COM_TRACKER_COMMENTS'), 'index.php?option=com_tracker&view=comments',  $vName == 'comments');
		if ($params->get('enable_donations')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_DONATIONS'), 'index.php?option=com_tracker&view=donations',  $vName == 'donations');
		if ($params->get('enable_licenses')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_LICENSES'), 'index.php?option=com_tracker&view=licenses', $vName == 'licenses');
		if ($params->get('enable_countries')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_COUNTRIES'), 'index.php?option=com_tracker&view=countries', $vName == 'countries');
		if ($params->get('peer_banning')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_BANCLIENTS'), 'index.php?option=com_tracker&view=banclients', $vName == 'banclients');
		if ($params->get('host_banning')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_BANHOSTS'), 'index.php?option=com_tracker&view=banhosts', $vName == 'banhosts');
		if ($params->get('enable_thankyou')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_THANKYOUS'), 'index.php?option=com_tracker&view=thankyous', $vName == 'thankyous');
		if ($params->get('enable_filetypes')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_FILETYPES'), 'index.php?option=com_tracker&view=filetypes', $vName == 'filetypes');
		if ($params->get('enable_reseedrequest')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_RESEEDS'), 'index.php?option=com_tracker&view=reseeds', $vName == 'reseeds');
		if ($params->get('enable_reporttorrent')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_REPORTS'), 'index.php?option=com_tracker&view=reports', $vName == 'reports');
		if ($params->get('enable_rss')) JHtmlSidebar::addEntry(JText::_('COM_TRACKER_RSSES'), 'index.php?option=com_tracker&view=rsses', $vName == 'rsses');
		JHtmlSidebar::addEntry(JText::_('COM_TRACKER_SETTINGS'), 'index.php?option=com_tracker&view=settings', $vName == 'settings');
		JHtmlSidebar::addEntry( JText::_('COM_TRACKER_UTILITIES'), 'index.php?option=com_tracker&view=utilities', $vName == 'utilities');

		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-trackerpanel {background-image: url(components/com_tracker/images/panel/logo-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-torrents {background-image: url(components/com_tracker/images/panel/torrent-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-users {background-image: url(components/com_tracker/images/panel/tuser-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-groups {background-image: url(components/com_tracker/images/panel/group-48x48.png);}');
		if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') $document->addStyleDeclaration('.icon-48-comments {background-image: url(components/com_tracker/images/panel/comments-48x48.png);}');
		if ($params->get('enable_donations')) $document->addStyleDeclaration('.icon-48-donations {background-image: url(components/com_tracker/images/panel/donations-48x48.png);}');
		if ($params->get('enable_licenses')) $document->addStyleDeclaration('.icon-48-licenses {background-image: url(components/com_tracker/images/panel/licenses-48x48.png);}');
		if ($params->get('enable_countries')) $document->addStyleDeclaration('.icon-48-countries {background-image: url(components/com_tracker/images/panel/countries-48x48.png);}');
		if ($params->get('peer_banning')) $document->addStyleDeclaration('.icon-48-clientban {background-image: url(components/com_tracker/images/panel/clientban-48x48.png);}');
		if ($params->get('host_banning')) $document->addStyleDeclaration('.icon-48-ipban {background-image: url(components/com_tracker/images/panel/ipban-48x48.png);}');
		if ($params->get('enable_thankyou')) $document->addStyleDeclaration('.icon-48-thankyou {background-image: url(components/com_tracker/images/panel/thankyou-48x48.png);}');
		if ($params->get('enable_filetypes')) $document->addStyleDeclaration('.icon-48-filetype {background-image: url(components/com_tracker/images/panel/filetype-48x48.png);}');
		if ($params->get('enable_reseedrequest')) $document->addStyleDeclaration('.icon-48-reseed {background-image: url(components/com_tracker/images/panel/reseed-48x48.png);}');
		if ($params->get('enable_reporttorrent')) $document->addStyleDeclaration('.icon-48-report {background-image: url(components/com_tracker/images/panel/report-48x48.png);}');
		if ($params->get('enable_rss')) $document->addStyleDeclaration('.icon-48-rsses {background-image: url(components/com_tracker/images/panel/rss-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-settings {background-image: url(components/com_tracker/images/panel/settings-48x48.png);}');
		$document->addStyleDeclaration('.icon-48-utilities {background-image: url(components/com_tracker/images/panel/utilities-48x48.png);}');
	}

	public static function quickiconButton( $link, $image, $text ) {
		?>
    	<div class="text-center middle">
        	<a href="<?php echo $link; ?>"><?php echo JHtml::_('image', JURI::root().'administrator/components/com_tracker/images/panel/'.$image , $text, null, false, false);?></a>
        	<div class="row-fluid">
	        	<span><a href="<?php echo $link; ?>"><?php echo $text; ?></a></span>
          	</div>
          	<br />
		</div>
		<?php 
	}

	public static function getActions($component = 'com_tracker', $section = '', $id = 0) {
		$user = JFactory::getUser();
		$result = new JObject;
		$assetName = 'com_tracker';
		$actions = array(
				'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);
		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}
		return $result;
	}

	public static function make_size($raw_size) {
		if ($raw_size < 1000 * 1024) return number_format($raw_size / 1024, 2) . ' '.JText::_( 'COM_TRACKER_KILOBYTES' );
		elseif ($raw_size < 1000 * 1048576) return number_format($raw_size / 1048576, 2) . ' '.JText::_( 'COM_TRACKER_MEGABYTES' );
		elseif ($raw_size < 1000 * 1073741824) return number_format($raw_size / 1073741824, 2) . ' '.JText::_( 'COM_TRACKER_GIGABYTES' );
		elseif ($raw_size < 1000 * 1099511627776) return number_format($raw_size / 1099511627776, 2) . ' '.JText::_( 'COM_TRACKER_TERABYTES' );
		else return number_format($raw_size / 1125899906842624, 2) . ' '.JText::_( 'COM_TRACKER_PETABYTES' );
	}

	public static function make_speed($bits) {
		if ($bits < 1000 * 1024) return number_format($bits / 1024, 2) . ' '.JText::_( 'COM_TRACKER_KILOBITS' );
		elseif ($bits < 1000 * 1048576) return number_format($bits / 1048576, 2) . ' '.JText::_( 'COM_TRACKER_MEGABITS' );
		elseif ($bits < 1000 * 1073741824) return number_format($bits / 1073741824, 2) . ' '.JText::_( 'COM_TRACKER_GIGABTIS' );
		elseif ($bits < 1000 * 1099511627776) return number_format($bits / 1099511627776, 2) . ' '.JText::_( 'COM_TRACKER_TERABITS' );
		else return number_format($bits / 1125899906842624, 2) . ' '.JText::_( 'COM_TRACKER_PETABITS' );
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
		$query->select('a.id AS value, a.name AS text')
			  ->from('`#__tracker_groups` AS a');
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
		$query->select('a.id AS value, a.name AS text')
			  ->from('`#__tracker_countries` AS a')
			  ->order('a.name ASC');
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
		
		$query->select('DISTINCT(tu.countryID) AS value, c.name AS text')
			  ->from('`#__tracker_countries` AS c')
			  ->join('RIGHT', '`#__tracker_users` AS tu ON tu.countryID = c.id')
			  ->where('tu.countryID <> 0')
			  ->order('c.name ASC');
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

		$query->select('tg.'.$type)
			  ->from('`#__tracker_groups` AS tg')
			  ->join('LEFT', '`#__tracker_users` AS tu ON tu.groupID = tg.id')
			  ->where('tu.id = '.(int) $userid);
		$db->setQuery($query);
		$myuser = $db->loadResult();
		return $myuser;
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
		
		$query->select('minimum_ratio, download_torrents, wait_time, peer_limit, torrent_limit, download_multiplier, upload_multiplier')
			  ->from('#__tracker_groups')
			  ->where('id = '.(int)$params->get('base_group'));
		$db->setQuery($query);
		$base_group = $db->loadAssoc();

		$query->clear()
			  ->select('u.id as id')
			  ->from('#__users as u')
			  ->join('LEFT', '`#__tracker_users` AS tu ON u.id = tu.id')
			  ->where('tu.id is null');
		$db->setQuery($query);
		$newusers = $db->loadAssocList();

		foreach($newusers as $newuser) {
			$query->clear();
			$query = $db->getQuery(true);
			$query->insert('#__tracker_users')
				  ->set('id = '.(int)$newuser['id'])
				  ->set('groupID = '.(int)$params->get('base_group'))
				  ->set('countryID = '.(int)$params->get('defaultcountry'))
				  ->set('downloaded = 0')
				  ->set('uploaded = '.($params->get('welcome_gigs') * 1073741824))
				  ->set('exemption_type = 2')
				  ->set('minimum_ratio = '.$base_group['minimum_ratio'])
				  ->set('can_leech = '.(int)$base_group['download_torrents'])
				  ->set('wait_time = '.(int)$base_group['wait_time'])
				  ->set('peer_limit = '.(int)$base_group['peer_limit'])
				  ->set('torrent_limit = '.(int)$base_group['torrent_limit'])
				  ->set('torrent_pass_version = 1')
				  ->set('multiplier_type = 0')
				  ->set('download_multiplier = '.$base_group['download_multiplier'])
				  ->set('upload_multiplier = '.$base_group['upload_multiplier'])
				  ->set('hash = "'.JUserHelper::genRandomPassword(32).'"')
				  ->set('ordering = '.(int)$newuser['id']);
			$db->setQuery($query);
			$db->execute();
		}

		// Delete the removed Joomla user from xbt user table
		$query->clear()
			  ->select('tu.id as id')
			  ->from('#__tracker_users as tu')
			  ->join('LEFT', '`#__users` AS u ON tu.id = u.id')
			  ->where('u.id is null');
		$db->setQuery($query);
		$removed_users = $db->loadAssocList();
		
		foreach($removed_users as $removed_user) {
			$query->clear()
				  ->delete()
				  ->from('#__tracker_users')
				  ->where('id = '.(int)$removed_user['id']);
			$db->setQuery($query);
			$db->execute();
		}
	}

	public static function check_user_hash($uid) {
		$db 	= JFactory::getDBO();
		$params = JComponentHelper::getParams('com_tracker');
		$query	= $db->getQuery(true);

		$query->select('hash')
			  ->from('#__tracker_users')
			  ->where('id = '.(int)$uid);
		$db->setQuery($query);
		$user_hash = $db->loadResult();

		if (empty($user_hash)) {
			$query->clear();
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__tracker_users'))
				  ->set('hash = "'.JUserHelper::genRandomPassword(32).'"')
				  ->where('id = '.(int)$uid);
			echo "<br>query = ".$query."<br>";
			$db->setQuery($query);
			$db->execute();
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
			$comments = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jcomments'.DIRECTORY_SEPARATOR.'jcomments.php';
			if (file_exists($comments)) {
				require_once($comments);
				echo JComments::showComments($torrent_id, 'com_tracker', $torrent_name);
			}
		}
	}

	public static function checkThanks($userID, $torrentID) {
		$db 	= JFactory::getDBO();
		
		$query	= $db->getQuery(true);
		$query->select('uid')
			  ->from('#__tracker_torrent_thanks')
			  ->where('uid ='.(int)$userID)
			  ->where('torrentID ='.(int)$torrentID);
		$db->setQuery($query);
		if ($db->loadResult()) return 0;
		else return $userID;
	}

	public static function getLastOrder($tablename) { // Get the last ordering from the table we choose
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('MAX(ordering)')
			  ->from('`#__'.$tablename.'`');
		$db->setQuery($query);
		$max = $db->loadResult();
		return $max+1;
	}

	public static function update_parameter($name, $value) { // Update a parameter value by name 
		// retrieve existing params
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('params')
			  ->from('`#__extensions`')
			  ->where('name = "com_tracker"');
		$db->setQuery($query);
		$params = json_decode( $db->loadResult(), true );
	
		// change the parameter value 
		$params[$name] = $value;
	
		// store the combined result
		$paramsString = json_encode( $params );
		$query->clear();
		
		$query->update('#__extensions')
			  ->set('params = '.$db->quote($paramsString))
			  ->where('name = "com_tracker"');
		$db->setQuery($query);
		$db->execute();
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
		$query->select('requester')
			  ->from('#__tracker_reseed_request')
			  ->where('requester ='.(int)$userID)
			  ->where('fid ='.(int)$torrentID);
		$db->setQuery($query);
		if ($db->loadResult()) return 0;
		else return $userID;
	}

	public static function getCountryDetails($countryID) {
		$db 	= JFactory::getDBO();
		
		$query	= $db->getQuery(true);
		$query->clear()
			  ->select('name, image')
			  ->from('#__tracker_countries')
			  ->where('id ='.(int)$countryID);
		$db->setQuery($query);
		try {
			$default_country = $db->loadObject();
		} catch (Exception $e) {
			jimport('joomla.log.log');
			JLog::add(JText::_('COM_TRACKER_CANT_GET_DEFAULT_COUNTRY'), JLog::NOTICE);
			return false;
		}

		if (empty($default_country)) {
			$default_country = new JObject;
			$default_country->name = JText::_( 'JNONE' );
			$default_country->image = 'images/tracker/flags/unknown.png' ;
		}
		
		return $default_country;
	}

	public static function checkReportedTorrent($userID, $torrentID) {
		$db 	= JFactory::getDBO();
	
		$query	= $db->getQuery(true);
		$query->select('reporter')
			  ->from('#__tracker_reported_torrents')
			  ->where('reporter ='.(int)$userID)
			  ->where('fid ='.(int)$torrentID);
		$db->setQuery($query);
		if ($db->loadResult()) return 0;
		else return $userID;
	}

	public static function checkTorrentType($torrentID) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('download_multiplier, created_time, seeders')
			  ->from('#__tracker_torrents')
			  ->where('fid ='.(int)$torrentID);
		$db->setQuery($query);
		$torrent_type = $db->loadObject();
		$params = JComponentHelper::getParams( 'com_tracker' );

		// Check if torrent is free
		if ($params->get('enable_torrent_type_free') == 1 && $torrent_type->download_multiplier == 0) {
				echo '<img id="'.$torrentID.'free" alt="'.JText::_('COM_TRACKER_FREE').'" src="'.JURI::base().$params->get('torrent_type_free_image').'" />';
		}

		// Check if torrent is semi-free
		if ($params->get('enable_torrent_type_semifree') == 1 && ($torrent_type->download_multiplier <= $params->get('torrent_type_semifree_value') && $torrent_type->download_multiplier > 0)) {
				echo '<img id="'.$torrentID.'semifree" alt="'.JText::_('COM_TRACKER_SEMIFREE').'" src="'.JURI::base().$params->get('torrent_type_semifree_image').'" />';
		}

		// Check if torrent is new
		if ($params->get('enable_torrent_type_new') && ((date("U") - strtotime($torrent_type->created_time)) < ($params->get('torrent_type_new_value') * 3600))) {
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
		$query->select($value.' as value')
			  ->select($text.' as text')
			  ->from('`#__tracker_'.$table.'`');
		if (isset($state)) $query->where('state = ' . (int) $state);
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public static function changeUsersPermission($permission, $groupID, $enable) {
		JArrayHelper::toInteger($groupID);
		$groupID = implode( ',', $groupID );
		
		$db 	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__tracker_users'))
			  ->set($db->quoteName($permission) . ' = '.$enable)
			  ->where($db->quoteName('groupID') . ' IN ('.$groupID.')')
			  ->where($db->quoteName('exemption_type') . ' = 2');
		$db->setQuery($query);
		
		try {
			$result = $db->execute();
		} catch (Exception $e) {
			return false;
		}
		return true;
	}

	public static function getRSS($data = null) {
		// Initialise variables.
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$config = JFactory::getConfig();
		$query	= $db->getQuery(true);
		$params	= JComponentHelper::getParams('com_tracker');
		$lang 	= JFactory::getLanguage();
	
		// Get the items for the RSS channel
		$query->select('t.fid');
			
		// We need to use one field to do the preg_match.
		$used_fields = $data->item_title.' '.$data->item_description;
			
		// Select the fields in the item title and description
		if (preg_match('/{name}/',$used_fields)) 		$query->select('t.name');
		if (preg_match('/{description}/',$used_fields)) $query->select('t.description');
		if (preg_match('/{size}/',$used_fields))		$query->select('t.size');
		if (preg_match('/{upload_date}/',$used_fields))	$query->select('t.created_time');
		if (preg_match('/{seeders}/',$used_fields))		$query->select('t.seeders');
		if (preg_match('/{leechers}/',$used_fields))	$query->select('t.leechers');
		if (preg_match('/{completed}/',$used_fields))	$query->select('t.completed');
		if (preg_match('/{image}/',$used_fields))		$query->select('t.image_file');

		// Join on category table.
		if (preg_match('/{category}/',$used_fields) || $data->rss_type == 1) {
			$query->select('c.title AS category')
				  ->join('LEFT', '#__categories AS c on c.id = t.categoryID');
		}
	
		// Join on user table.
		if (preg_match('/{uploader}/',$used_fields)) {
			$query->select('u.username as user')
				  ->join('LEFT', '#__users AS u on u.id = r.created_user_id');
		}
			
		// Join on licenses table
		if (preg_match('/{license}/',$used_fields) || $data->rss_type == 2) {
			$query->select('l.shortname as license')
				  ->join('LEFT', '#__tracker_licenses AS l on l.id = t.licenseID');
		}
			
		$query->from('#__tracker_torrents AS t');
	
		// Show torrents with the selected categories
		if ($data->rss_type == 1) {
			$query->where('t.categoryID IN ( '.$data->rss_type_items.' )');
			// Or show torrents with the selected licenses
		} else if ($data->rss_type == 2) {
			$query->where('t.licenseID IN ( '.$data->rss_type_items.' )');
		}
		$query->order('t.fid DESC');
	
		// Limit the number of items we get from the DB
		$db->setQuery($query,0,$data->item_count);
		$items = $db->loadObjectList();

		$feed = new RSSFeed();
		$feed->SetChannel(JURI::getInstance()->toString(), 					// RSS URL
				$data->channel_title,										// RSS Channel Title
				$data->channel_description,									// RSS Channel Description
				$lang->getTag(),											// RSS Language
				'&#174;'.$config->get( 'config.sitename' ).date("Y"),		// RSS Copyright
				$data->user,												// RSS Creator
				$data->name);												// RSS Name
	
		foreach ($items as $i => $item) {
			// Now we need to prepare the preg_replace items. It's an ugly code but didn't found a better one
			$source = array();
			$destination = array();
	
			if (preg_match('/{name}/',$used_fields)) {
				array_push($source, '/{name}/');
				array_push($destination, $item->name);
			}
			if (preg_match('/{description}/',$used_fields)) {
				array_push($source, '/{description}/');
				array_push($destination, $item->description);
			}
//TODO: CHANGE LINK FROM TEXT TO HREF
			if (preg_match('/{link}/',$used_fields)) {
				array_push($source, '/{link}/');
				array_push($destination, JRoute::_(JURI::base().'index.php?option=com_tracker&view=torrent&id='.$item->fid, true, -1));
			}
			if (preg_match('/{size}/',$used_fields)) {
				array_push($source, '/{size}/');
				array_push($destination, $item->size);
			}
			if (preg_match('/{upload_date}/',$used_fields)) {
				array_push($source, '/{upload_date}/');
				array_push($destination, $item->created_time);
			}
			if (preg_match('/{seeders}/',$used_fields)) {
				array_push($source, '/{seeders}/');
				array_push($destination, $item->seeders);
			}
			if (preg_match('/{leechers}/',$used_fields)) {
				array_push($source, '/{leechers}/');
				array_push($destination, $item->leechers);
			}
			if (preg_match('/{completed}/',$used_fields)) {
				array_push($source, '/{completed}/');
				array_push($destination, $item->completed);
			}

//TODO: CHANGE IMAGE FROM TEXT TO IMG
			if (preg_match('/{image}/',$used_fields)) {
				array_push($source, '/{image}/');

				$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
				// If we dont have a link in the field
				if(!preg_match($reg_exUrl, $item->image_file)) :
					if (file_exists($_SERVER['DOCUMENT_ROOT'].JURI::base(true).'/images/tracker/torrent_image/'.$item->image_file) && !empty($item->image_file)) :
			 			$item->image_file = JURI::base().'images/tracker/torrent_image/'.$item->image_file;
				 	else :
				 		$item->image_file = JURI::base().$params->get('default_image_file');
				 	endif;
				 endif;

				array_push($destination, $item->image_file);
				}
			
			if (preg_match('/{category}/',$used_fields)) {
				array_push($source, '/{category}/');
				array_push($destination, $item->category);
			}
			if (preg_match('/{uploader}/',$used_fields)) {
				array_push($source, '/{uploader}/');
				array_push($destination, $item->user);
			}
			if (preg_match('/{license}/',$used_fields)) {
				array_push($source, '/{license}/');
				array_push($destination, $item->license);
			}
	
			$name = htmlspecialchars(preg_replace($source, $destination, $data->item_title));
			$description = htmlspecialchars(preg_replace($source, $destination, $data->item_description));

			$feed->SetItem(JRoute::_(JURI::base().'index.php?option=com_tracker&view=torrent&id='.$item->fid, true, -1), $name, $description);
		}
	
		echo $feed->output();
	}

	public static function checkComponentConfig() {
		$app	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$params = JComponentHelper::getParams( 'com_tracker' );
		$torrent_dir = $params->get('torrent_dir');
		$testFile = JPATH_SITE.DIRECTORY_SEPARATOR.$torrent_dir.'index.html';

		// First we test if the torrent folder exists and it's writable
		if (!is_writable(dirname($testFile))) {
			$app->redirect(JRoute::_(JURI::root().'index.php'), JPATH_SITE.DIRECTORY_SEPARATOR.$torrent_dir.' '.JText::_('COM_TRACKER_DOESNT_EXISTS_OR_ISNT_WRITABLE'), 'error');
		}

		// Then we test if we have categories created
		$query	= $db->getQuery(true);
		$query->select('COUNT(*)')
			  ->from('#__categories')
			  ->where('extension = "com_tracker"')
			  ->where('published = 1');
		$db->setQuery($query);
		if ($db->loadResult() == 0) {
			$app->redirect(JRoute::_(JURI::root().'index.php'), JText::_('COM_TRACKER_THERE_ARENT_ANY_CATEGORIES_IN_TRACKER'), 'error');
		}
		
	}

	public static function downloadArrowType($seeds, $leechers) {
		if (($seeds > 0 && $leechers > 0) || $seeds > 0)
			return '<img src="'.JURI::base().'components/com_tracker/assets/images/download_good.png" alt="'.JText::_( 'TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ).'" border="0" />';
		else if ($seeds == 0 && $leechers > 0)
			return '<img src="'.JURI::base().'components/com_tracker/assets/images/download_medium.png" alt="'.JText::_( 'TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ).'" border="0" />';
		else
			return '<img src="'.JURI::base().'components/com_tracker/assets/images/download_bad.png" alt="'.JText::_( 'TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ).'" border="0" />';
	}
}