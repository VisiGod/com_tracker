<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class com_trackerInstallerScript {

	function postflight($type, $parent) {

		if ($type == 'install') {
			$db = &JFactory::getDBO();
			$app = JFactory::getApplication();
			$query	= $db->getQuery(true);
			
			// Update the component parameters with the default ones
			$defaults  = '{';
			$defaults .= '"torrent_multiplier":"1",';
			$defaults .= '"host_banning":"1",';
			$defaults .= '"peer_banning":"1",';
			$defaults .= '"peer_speed":"0",';
			$defaults .= '"enable_comments":"1",';
			$defaults .= '"enable_donations":"1",';
			$defaults .= '"enable_countries":"1",';
			$defaults .= '"category_image_size":"36",';
			$defaults .= '"trackers_address":"",';
			$defaults .= '"donation_ratio":"2.5",';
			$defaults .= '"torrent_dir":"torrents",';
			$defaults .= '"max_torrent_size":"1048576",';
			$defaults .= '"progress_bar_size":"50",';
			$defaults .= '"allow_upload_anonymous":"1",';
			$defaults .= '"enable_licenses":"1",';
			$defaults .= '"use_image_file":"1",';
			$defaults .= '"make_private":"1",';
			$defaults .= '"tag_in_torrent":"1",';
			$defaults .= '"forum_post_id":"1",';
			$defaults .= '"forum_post_url":"http://forum.site.com/index.php?showtopic":"",';
			$defaults .= '"torrent_information":"1",';
			$defaults .= '"info_post_description":"Torrent Information",';
			$defaults .= '"info_post_url":"http://www.site.com/index.php?info":"",';
			$defaults .= '"base_group":"1",';
			$defaults .= '"defaultcountry":"999",';
			$defaults .= '"allow_guest":"0",';
			$defaults .= '"guest_user":"",';
			$defaults .= '"welcome_gigs":"0",';
			$defaults .= '"comment_only_leecher":"1",';

			$defaults .= '"forum_integration":"0",';
			$defaults .= '"forum_db_server":"localhost",';
			$defaults .= '"forum_db_port":"3306",';
			$defaults .= '"forum_database":"forum_database",';
			$defaults .= '"forum_db_user":"forum_user",';
			$defaults .= '"forum_db_password":"forum_pass",';
			$defaults .= '"forum_tableprefix":"prefix_",';

			$defaults .= '"forum_member_tablename":"members",';
			$defaults .= '"forum_group_tablename":"groups",';
			$defaults .= '"forum_name_field":"name",';
			$defaults .= '"forum_id_field":"user_id",';
			$defaults .= '"forum_group_field":"mgroup",';
			$defaults .= '"forum_posts_field":"posts",';
			$defaults .= '"forum_group_id_field":"g_id",';
			$defaults .= '"forum_group_name_field":"g_title"';
//
			$defaults .= '}';
			// JSON format for the parameters
			$query->update('#__extensions');
			$query->set("params = '" . $defaults . "'");
			$query->where("name = 'com_tracker'");
			$db->setQuery($query);
			$db->query();
			
			//Insert the default user group
			$query->clear();
			$query->insert('#__tracker_groups');
			$query->set('id = 1');
			$query->set('name = "default"');
			$query->set('view_torrents = 1');
			$query->set('edit_torrents = 0');
			$query->set('delete_torrents = 0');
			$query->set('upload_torrents = 0');
			$query->set('download_torrents = 1');
			$query->set('can_leech = 1');
			$query->set('wait_time = 0');
			$query->set('peer_limit = 1');
			$query->set('torrent_limit = 1');
			$query->set('minimum_ratio = 1');
			$query->set('download_multiplier = 1');
			$query->set('upload_multiplier = 1');
			$query->set('view_comments = 1');
			$query->set('write_comments = 0');
			$query->set('edit_comments = 0');
			$query->set('delete_comments = 0');
			$query->set('autopublish_comments = 0');
			$query->set('ordering = 0');
			$query->set('state = 1');
			$db->setQuery($query);
			$db->query();

			//Insert the default license
			$query->clear();
			$query->insert('#__tracker_licenses');
			$query->set('shortname = "GNU GPL v3"');
			$query->set('alias = "gnu-gpl-v3"');
			$query->set('fullname = "GNU General Public License (GPL) version 3"');
			$query->set('description = "This is the latest version of the GNU GPL"');
			$query->set('link = "http://www.gnu.org/licenses/gpl-3.0.html"');
			$query->set('ordering = 1');
			$query->set('state = 1');
			$db->setQuery($query);
			$db->query();

			//Insert the XBT default values
			$settings = array();
			$settings['announce_interval'] 			= '1800';
			$settings['anonymous_announce'] 		= '0';
			$settings['anonymous_scrape'] 			= '0';
			$settings['auto_register'] 				= '0';
			$settings['clean_up_interval'] 			= '60';
			$settings['daemon'] 					= '1';
			$settings['debug'] 						= '0';
			$settings['full_scrape'] 				= '0';
			$settings['gzip_scrape']				= '1';
			$settings['listen_ipa'] 				= $_SERVER['SERVER_ADDR'];
			$settings['listen_port'] 				= '2710';
			$settings['log_access'] 				= '0';
			$settings['log_announce'] 				= '1';
			$settings['log_scrape'] 				= '0';
			$settings['offline_message'] 			= '';
			$settings['pid_file'] 					= 'xbt_tracker.pid';
			$settings['query_log'] 					= '0';
			$settings['read_config_interval'] 		= '60';
			$settings['read_db_interval'] 			= '60';
			$settings['redirect_url'] 				= JURI::root();
			$settings['scrape_interval'] 			= '0';
			$settings['write_db_interval'] 			= '15';
			$settings['table_announce_log'] 		= $app->getCfg('dbprefix', 1).'tracker_announce_log';
			$settings['table_files'] 				= $app->getCfg('dbprefix', 1).'tracker_torrents';
			$settings['table_files_users'] 			= $app->getCfg('dbprefix', 1).'tracker_files_users';
			$settings['table_scrape_log'] 			= $app->getCfg('dbprefix', 1).'tracker_scrape_log';
			$settings['table_users'] 				= $app->getCfg('dbprefix', 1).'users';
			$settings['column_files_completed'] 	= 'completed';
			$settings['column_files_fid'] 			= 'fid';
			$settings['column_files_leechers'] 		= 'leechers';
			$settings['column_files_seeders'] 		= 'seeders';
			$settings['column_users_uid'] 			=	'id';
			$settings['torrent_pass_private_key'] 	= com_trackerInstallerScript::code(27);
			$query->clear();
			foreach($settings as $name => $value) {
				$query = "INSERT INTO xbt_config ( name, value ) VALUES ('" . $name . "', '" . $value . "' );";
				$db->setQuery($query);
				$db->query();
			}
		}

		// Insert the users into the tracker_users table
		$db = &JFactory::getDBO();
		$app = JFactory::getApplication();
		$query	= $db->getQuery(true);
		$query  = "INSERT IGNORE INTO ".$app->getCfg('dbprefix', 1)."tracker_users (id) SELECT id FROM ".$app->getCfg('dbprefix', 1)."users";
		$db->setQuery($query);
		$db->query();
	}

	function code($nc, $a='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
		$l=strlen($a)-1; $r='';
		while($nc-->0) $r.=$a{mt_rand(0,$l)};
		return $r;
	}
}