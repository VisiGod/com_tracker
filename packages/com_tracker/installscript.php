<?php
/**
 * @version		3.3.2-dev
 * @package		Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class com_trackerInstallerScript {

	function update( $parent ) {
		$jversion = new JVersion();

		// Installing component manifest file version
		$this->release = $parent->get( "manifest" )->version;

		// First changes in database model since version 3.3.1-dev 
		if ($this->release < '3.3.1-dev') {
			// Added Tags
			$db = JFactory::getDbo();
			$db->setQuery('ALTER TABLE #__tracker_torrents ADD `tags` VARCHAR(16380) NOT NULL AFTER `image_file`');
			$db->execute();

			// Added Seeding Time
			$db = JFactory::getDbo();
			$db->setQuery('ALTER TABLE #__tracker_files_users ADD `seeding_time` INT NOT NULL AFTER `up_rate`');
			$db->execute();
		}

		// Release 3.3.1-dev - The introduction of RSS
		if ($this->release < '3.3.1-dev') {
			$db = JFactory::getDbo();
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__tracker_rss` (
							`id` INT(11) NOT NULL AUTO_INCREMENT,
							`channel_title` VARCHAR(50) NOT NULL,
							`channel_description` VARCHAR(100) NOT NULL,
							`rss_authentication` TINYINT(1) NOT NULL DEFAULT '0',
							`rss_authentication_items` VARCHAR(100) DEFAULT NULL,
							`rss_type` TINYINT(1) NOT NULL DEFAULT '0',
							`rss_type_items` VARCHAR(100) DEFAULT NULL,
							`item_count` TINYINT(1) UNSIGNED NOT NULL DEFAULT '10',
							`item_title` VARCHAR(50) NOT NULL,
							`item_description` VARCHAR(250) NOT NULL,
							`created_user_id` INT(10) UNSIGNED NOT NULL,
							`created_time` DATETIME DEFAULT NULL,
							`ordering` INT(11) NOT NULL,
							`state` TINYINT(1) NOT NULL DEFAULT '1',
							PRIMARY KEY (`id`)
							);");
			$db->execute();
			
			$query	= $db->getQuery(true);
			$db->setQuery('ALTER TABLE #__tracker_users ADD `hash` VARCHAR(32) NOT NULL AFTER `upload_multiplier`');
			$db->execute();
		}

		// First changes in database model since version 3.3.2-dev
		if ($this->release < '3.3.2-dev') {

			// Added nfo file support
			$db = JFactory::getDbo();
			$db->setQuery('ALTER TABLE #__tracker_torrents ADD `nfo_file` VARCHAR(255) AFTER `tags`');
			$db->execute();
		}

		if ($this->release < '3.3.3-dev') {
		
			// Added nfo file support
			$db = JFactory::getDbo();
			//$db->setQuery('ALTER TABLE #__tracker_torrents ADD `nfo_file` VARCHAR(255) AFTER `tags`');
			$db->execute();
		}
	}
	
	function postflight($type, $parent) {
		if ($type == 'install') {
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$query	= $db->getQuery(true);

			// Update the component parameters with the default ones
			$defaults  = '{';
			$defaults .= '"torrent_multiplier":"1",';
			$defaults .= '"peer_banning":"1",';
			$defaults .= '"host_banning":"1",';
			$defaults .= '"peer_speed":"1",';
			$defaults .= '"enable_donations":"1",';
			$defaults .= '"enable_countries":"1",';
			$defaults .= '"enable_licenses":"1",';
			$defaults .= '"enable_filetypes":"1",';
			$defaults .= '"enable_thankyou":"1",';
			$defaults .= '"enable_reseedrequest":"1",';
			$defaults .= '"enable_reporttorrent":"1",';
			$defaults .= '"enable_rss":"1",';
			$defaults .= '"freeleech":"0",';
			$defaults .= '"enable_torrent_type":"1",';
			$defaults .= '"enable_torrent_type_new":"1",';
			$defaults .= '"torrent_type_new_image":"images\/tracker\/torrenttype\/new.png",';
			$defaults .= '"torrent_type_new_text":"The torrent was uploaded in the last %s hours",';
			$defaults .= '"torrent_type_new_value":24,';
			$defaults .= '"enable_torrent_type_top":"1",';
			$defaults .= '"torrent_type_top_image":"images\/tracker\/torrenttype\/top.png",';
			$defaults .= '"torrent_type_top_text":"The torrent has %s or more seeders",';
			$defaults .= '"torrent_type_top_value":3,';
			$defaults .= '"enable_torrent_type_hot":"1",';
			$defaults .= '"torrent_type_hot_image":"images\/tracker\/torrenttype\/hot.png",';
			$defaults .= '"torrent_type_hot_text":"The torrent has %s or more seeders",';
			$defaults .= '"torrent_type_hot_value":5,';
			$defaults .= '"enable_torrent_type_semifree":"1",';
			$defaults .= '"torrent_type_semifree_image":"images\/tracker\/torrenttype\/semifree.png",';
			$defaults .= '"torrent_type_semifree_text":"The torrent is in semi free leech mode",';
			$defaults .= '"torrent_type_semifree_value":"0.5",';
			$defaults .= '"enable_torrent_type_free":"1",';
			$defaults .= '"torrent_type_free_image":"images\/tracker\/torrenttype\/free.png",';
			$defaults .= '"torrent_type_free_text":"The torrent is in free leech mode",';
			$defaults .= '"allow_guest":"1",';
			$defaults .= '"guest_user":"340",';
			$defaults .= '"torrent_user":"328",';
			$defaults .= '"use_image_file":"1",';
			$defaults .= '"image_width":150,';
			$defaults .= '"default_image_file":"images\/tracker\/torrent_image\/torrent.png",';
			$defaults .= '"image_type":"0",';
			$defaults .= '"torrent_tags":"1",';
			$defaults .= '"tag_in_torrent":"1",';
			$defaults .= '"use_nfo_files":"1",';
			$defaults .= '"nfo_background_color":"#000000",';
			$defaults .= '"allow_upload_anonymous":"1",';
			$defaults .= '"make_private":"1",';
			$defaults .= '"welcome_gigs":0,';
			$defaults .= '"category_image_size":36,';
			$defaults .= '"trackers_address":"",';
			$defaults .= '"donation_ratio":"2.5",';
			$defaults .= '"torrent_dir":"torrents\/",';
			$defaults .= '"max_torrent_size":1048576,';
			$defaults .= '"progress_bar_size":50,';
			$defaults .= '"user_in_torrent_details":"0",';
			$defaults .= '"base_group":"1",';
			$defaults .= '"defaultcountry":"170",';
			$defaults .= '"forum_post_id":"0",';
			$defaults .= '"forum_post_url":"http:\/\/forum.site.com\/index.php?showtopic=",';
			$defaults .= '"torrent_information":"0",';
			$defaults .= '"info_post_description":"Torrent Information",';
			$defaults .= '"info_post_url":"http:\/\/www.site.com\/index.php?info=",';
			$defaults .= '"jquery_url":"http:\/\/code.jquery.com\/jquery-latest.js",';
			$defaults .= '"jquery_ui_url":"http:\/\/code.jquery.com\/ui\/1.10.2\/jquery-ui.js",';
			$defaults .= '"jquery_smoothness_theme_url":"http:\/\/code.jquery.com\/ui\/1.10.2\/themes\/smoothness\/jquery-ui.css",';
			$defaults .= '"enable_comments":"0",';
			$defaults .= '"comment_system":"jcomments",';
			$defaults .= '"comment_only_leecher":"0",';
			$defaults .= '"forum_integration":"0",';
			$defaults .= '"forum_db_server":"localhost",';
			$defaults .= '"forum_db_port":3306,';
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
			$defaults .= '}';
			$query->update($db->quoteName('#__extensions'));
			$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
			$query->where($db->quoteName('name') . ' = ' . $db->quote('com_tracker'));
			$db->setQuery($query);
			$db->execute();

			//Insert the default countries
			$query	= $db->getQuery(true);
			$query = "INSERT INTO ".$app->getCfg('dbprefix', 1)."tracker_countries VALUES ";
			$query .= "(1, 'Afghanistan', 'images/tracker/flags/Afghanistan.png', 1, 1), (2, 'Albania', 'images/tracker/flags/Albania.png', 2, 1), (3, 'Algeria', 'images/tracker/flags/Algeria.png', 3, 1), ";
			$query .= "(4, 'American Samoa', 'images/tracker/flags/American.Samoa.png', 4, 1), (5, 'Andorra', 'images/tracker/flags/Andorra.png', 5, 1), (6, 'Angola', 'images/tracker/flags/Angola.png', 6, 1), ";
			$query .= "(7, 'Anguilla', 'images/tracker/flags/Anguilla.png', 7, 1), (8, 'Antigua and Barbuda', 'images/tracker/flags/Antigua.and.Barbuda.png', 8, 1), (9, 'Argentina', 'images/tracker/flags/Argentina.png', 9, 1), ";
			$query .= "(10, 'Armenia', 'images/tracker/flags/Armenia.png', 10, 1), (11, 'Aruba', 'images/tracker/flags/Aruba.png', 11, 1), (12, 'Australia', 'images/tracker/flags/Australia.png', 12, 1), ";
			$query .= "(13, 'Austria', 'images/tracker/flags/Austria.png', 13, 1), (14, 'Azerbaijan', 'images/tracker/flags/Azerbaijan.png', 14, 1), (15, 'Bahamas', 'images/tracker/flags/Bahamas.png', 15, 1), ";
			$query .= "(16, 'Bahrain', 'images/tracker/flags/Bahrain.png', 16, 1), (17, 'Bangladesh', 'images/tracker/flags/Bangladesh.png', 17, 1), (18, 'Barbados', 'images/tracker/flags/Barbados.png', 18, 1), ";
			$query .= "(19, 'Belarus', 'images/tracker/flags/Belarus.png', 19, 1), (20, 'Belgium', 'images/tracker/flags/Belgium.png', 20, 1), (21, 'Belize', 'images/tracker/flags/Belize.png', 21, 1), ";
			$query .= "(22, 'Benin', 'images/tracker/flags/Benin.png', 22, 1), (23, 'Bermuda', 'images/tracker/flags/Bermuda.png', 23, 1), (24, 'Bhutan', 'images/tracker/flags/Bhutan.png', 24, 1), ";
			$query .= "(25, 'Bolivia', 'images/tracker/flags/Bolivia.png', 25, 1), (26, 'Bosnia and Herzegovina', 'images/tracker/flags/Bosnia.and.Herzegovina.png', 26, 1), (27, 'Botswana', 'images/tracker/flags/Botswana.png', 27, 1), ";
			$query .= "(28, 'Bouvet Island', 'images/tracker/flags/Bouvet.Island.png', 28, 1), (29, 'Brazil', 'images/tracker/flags/Brazil.png', 29, 1), (30, 'British Indian Ocean Territory', 'images/tracker/flags/British.Indian.Ocean.Territory.png', 30, 1), ";
			$query .= "(31, 'British Virgin Islands', 'images/tracker/flags/British.Virgin.Islands.png', 31, 1), (32, 'Brunei', 'images/tracker/flags/Brunei.png', 32, 1), (33, 'Bulgaria', 'images/tracker/flags/Bulgaria.png', 33, 1), ";
			$query .= "(34, 'Burkina Faso', 'images/tracker/flags/Burkina.Faso.png', 34, 1), (35, 'Burma', 'images/tracker/flags/Burma.png', 35, 1), (36, 'Burundi', 'images/tracker/flags/Burundi.png', 36, 1), ";
			$query .= "(37, 'Cambodia', 'images/tracker/flags/Cambodia.png', 37, 1), (38, 'Cameroon', 'images/tracker/flags/Cameroon.png', 38, 1), (39, 'Canada', 'images/tracker/flags/Canada.png', 39, 1), ";
			$query .= "(40, 'Cape Verde', 'images/tracker/flags/Cape.Verde.png', 40, 1), (41, 'Cayman Islands', 'images/tracker/flags/Cayman.Islands.png', 41, 1), (42, 'Central African Republic', 'images/tracker/flags/Central.African.Republic.png', 42, 1), ";
			$query .= "(43, 'Chad', 'images/tracker/flags/Chad.png', 43, 1), (44, 'Chile', 'images/tracker/flags/Chile.png', 44, 1), (45, 'China', 'images/tracker/flags/China.png', 45, 1), ";
			$query .= "(46, 'Christmas Islands', 'images/tracker/flags/Christmas.Islands.png', 46, 1), (47, 'Cocos (Keeling) Islands', 'images/tracker/flags/Cocos.(Keeling).Islands.png', 47, 1), (48, 'Colombia', 'images/tracker/flags/Colombia.png', 48, 1), ";
			$query .= "(49, 'Comoros', 'images/tracker/flags/Comoros.png', 49, 1), (50, 'Cook Islands', 'images/tracker/flags/Cook.Islands.png', 50, 1), (51, 'Costa Rica', 'images/tracker/flags/Costa.Rica.png', 51, 1), ";
			$query .= "(52, 'Cote d\'Ivoire', 'images/tracker/flags/Cote.d\'Ivoire.png', 52, 1), (53, 'Croatia', 'images/tracker/flags/Croatia.png', 53, 1), (54, 'Cuba', 'images/tracker/flags/Cuba.png', 54, 1), ";
			$query .= "(55, 'Cyprus', 'images/tracker/flags/Cyprus.png', 55, 1), (56, 'Czech Republic', 'images/tracker/flags/Czech.Republic.png', 56, 1), (57, 'Democratic Republic of the Congo', 'images/tracker/flags/Democratic.Republic.of.the.Congo.png', 57, 1), ";
			$query .= "(58, 'Denmark', 'images/tracker/flags/Denmark.png', 58, 1), (59, 'Djibouti', 'images/tracker/flags/Djibouti.png', 59, 1), (60, 'Dominica', 'images/tracker/flags/Dominica.png', 60, 1), ";
			$query .= "(61, 'Dominican Republic', 'images/tracker/flags/Dominican.Republic.png', 61, 1), (62, 'Ecuador', 'images/tracker/flags/Ecuador.png', 62, 1), (63, 'Egypt', 'images/tracker/flags/Egypt.png', 63, 1), ";
			$query .= "(64, 'El Salvador', 'images/tracker/flags/El.Salvador.png', 64, 1), (65, 'England', 'images/tracker/flags/England.png', 65, 1), (66, 'Equatorial Guinea', 'images/tracker/flags/Equatorial.Guinea.png', 66, 1), ";
			$query .= "(67, 'Eritrea', 'images/tracker/flags/Eritrea.png', 67, 1), (68, 'Estonia', 'images/tracker/flags/Estonia.png', 68, 1), (69, 'Ethiopia', 'images/tracker/flags/Ethiopia.png', 69, 1), ";
			$query .= "(70, 'European Union', 'images/tracker/flags/European.Union.png', 70, 1), (71, 'Falkland Islands (Islas Malvinas)', 'images/tracker/flags/Falkland.Islands.(Islas.Malvinas).png', 71, 1), (72, 'Faroe Islands', 'images/tracker/flags/Faroe.Islands.png', 72, 1), ";
			$query .= "(73, 'Fiji', 'images/tracker/flags/Fiji.png', 73, 1), (74, 'Finland', 'images/tracker/flags/Finland.png', 74, 1), (75, 'France', 'images/tracker/flags/France.png', 75, 1), ";
			$query .= "(76, 'French Polynesia', 'images/tracker/flags/French.Polynesia.png', 76, 1), (77, 'Gabon', 'images/tracker/flags/Gabon.png', 77, 1), (78, 'Gambia', 'images/tracker/flags/Gambia.png', 78, 1), ";
			$query .= "(79, 'Georgia', 'images/tracker/flags/Georgia.png', 79, 1), (80, 'Germany', 'images/tracker/flags/Germany.png', 80, 1), (81, 'Ghana', 'images/tracker/flags/Ghana.png', 81, 1), ";
			$query .= "(82, 'Gibraltar', 'images/tracker/flags/Gibraltar.png', 82, 1), (83, 'Greece', 'images/tracker/flags/Greece.png', 83, 1), (84, 'Greenland', 'images/tracker/flags/Greenland.png', 84, 1), ";
			$query .= "(85, 'Grenada', 'images/tracker/flags/Grenada.png', 85, 1), (86, 'Guam', 'images/tracker/flags/Guam.png', 86, 1), (87, 'Guatemala', 'images/tracker/flags/Guatemala.png', 87, 1), ";
			$query .= "(88, 'Guernsey', 'images/tracker/flags/Guernsey.png', 88, 1), (89, 'Guinea Bissau', 'images/tracker/flags/Guinea.Bissau.png', 89, 1), (90, 'Guinea', 'images/tracker/flags/Guinea.png', 90, 1), ";
			$query .= "(91, 'Guyana', 'images/tracker/flags/Guyana.png', 91, 1), (92, 'Haiti', 'images/tracker/flags/Haiti.png', 92, 1), (93, 'Honduras', 'images/tracker/flags/Honduras.png', 93, 1), ";
			$query .= "(94, 'Hong Kong', 'images/tracker/flags/Hong.Kong.png', 94, 1), (95, 'Hungary', 'images/tracker/flags/Hungary.png', 95, 1), (96, 'Iceland', 'images/tracker/flags/Iceland.png', 96, 1), ";
			$query .= "(97, 'India', 'images/tracker/flags/India.png', 97, 1), (98, 'Indonesia', 'images/tracker/flags/Indonesia.png', 98, 1), (99, 'Iran', 'images/tracker/flags/Iran.png', 99, 1), ";
			$query .= "(100, 'Iraq', 'images/tracker/flags/Iraq.png', 100, 1), (101, 'Ireland', 'images/tracker/flags/Ireland.png', 101, 1), (102, 'Isle of Man', 'images/tracker/flags/Isle.of.Man.png', 102, 1), ";
			$query .= "(103, 'Israel', 'images/tracker/flags/Israel.png', 103, 1), (104, 'Italy', 'images/tracker/flags/Italy.png', 104, 1), (105, 'Jamaica', 'images/tracker/flags/Jamaica.png', 105, 1), ";
			$query .= "(106, 'Japan', 'images/tracker/flags/Japan.png', 106, 1), (107, 'Jordan', 'images/tracker/flags/Jordan.png', 107, 1), (108, 'Kazakhstan', 'images/tracker/flags/Kazakhstan.png', 108, 1), ";
			$query .= "(109, 'Kenya', 'images/tracker/flags/Kenya.png', 109, 1), (110, 'Kiribati', 'images/tracker/flags/Kiribati.png', 110, 1), (111, 'Kosovo', 'images/tracker/flags/Kosovo.png', 111, 1), ";
			$query .= "(112, 'Kurdistan Nation', 'images/tracker/flags/Kurdistan.Nation.png', 112, 1), (113, 'Kuwait', 'images/tracker/flags/Kuwait.png', 113, 1), (114, 'Kyrgyzstan', 'images/tracker/flags/Kyrgyzstan.png', 114, 1), ";
			$query .= "(115, 'Laos', 'images/tracker/flags/Laos.png', 115, 1), (116, 'Latvia', 'images/tracker/flags/Latvia.png', 116, 1), (117, 'Lebanon', 'images/tracker/flags/Lebanon.png', 117, 1), ";
			$query .= "(118, 'Lesotho', 'images/tracker/flags/Lesotho.png', 118, 1), (119, 'Liberia', 'images/tracker/flags/Liberia.png', 119, 1), (120, 'Libya', 'images/tracker/flags/Libya.png', 120, 1), ";
			$query .= "(121, 'Liechtenstein', 'images/tracker/flags/Liechtenstein.png', 121, 1), (122, 'Lithuania', 'images/tracker/flags/Lithuania.png', 122, 1), (123, 'Luxembourg', 'images/tracker/flags/Luxembourg.png', 123, 1), ";
			$query .= "(124, 'Macau', 'images/tracker/flags/Macau.png', 124, 1), (125, 'Macedonia', 'images/tracker/flags/Macedonia.png', 125, 1), (126, 'Madagascar', 'images/tracker/flags/Madagascar.png', 126, 1), ";
			$query .= "(127, 'Malawi', 'images/tracker/flags/Malawi.png', 127, 1), (128, 'Malaysia', 'images/tracker/flags/Malaysia.png', 128, 1), (129, 'Maldives', 'images/tracker/flags/Maldives.png', 129, 1), ";
			$query .= "(130, 'Mali', 'images/tracker/flags/Mali.png', 130, 1), (131, 'Malta', 'images/tracker/flags/Malta.png', 131, 1), (132, 'Marshall Islands', 'images/tracker/flags/Marshall.Islands.png', 132, 1), ";
			$query .= "(133, 'Mauritania', 'images/tracker/flags/Mauritania.png', 133, 1), (134, 'Mauritius', 'images/tracker/flags/Mauritius.png', 134, 1), (135, 'Mayotte', 'images/tracker/flags/Mayotte.png', 135, 1), ";
			$query .= "(136, 'Mexico', 'images/tracker/flags/Mexico.png', 136, 1), (137, 'Micronesia', 'images/tracker/flags/Micronesia.png', 137, 1), (138, 'Moldavia', 'images/tracker/flags/Moldavia.png', 138, 1), ";
			$query .= "(139, 'Monaco', 'images/tracker/flags/Monaco.png', 139, 1), (140, 'Mongolia', 'images/tracker/flags/Mongolia.png', 140, 1), (141, 'Montenegro', 'images/tracker/flags/Montenegro.png', 141, 1), ";
			$query .= "(142, 'Montserrat', 'images/tracker/flags/Montserrat.png', 142, 1), (143, 'Morocco', 'images/tracker/flags/Morocco.png', 143, 1), (144, 'Mozambique', 'images/tracker/flags/Mozambique.png', 144, 1), ";
			$query .= "(145, 'Namibia', 'images/tracker/flags/Namibia.png', 145, 1), (146, 'Nauru', 'images/tracker/flags/Nauru.png', 146, 1), (147, 'Nepal', 'images/tracker/flags/Nepal.png', 147, 1), ";
			$query .= "(148, 'Netherlands Antilles', 'images/tracker/flags/Netherlands.Antilles.png', 148, 1), (149, 'Netherlands', 'images/tracker/flags/Netherlands.png', 149, 1), (150, 'New Zealand', 'images/tracker/flags/New.Zealand.png', 150, 1), ";
			$query .= "(151, 'Nicaragua', 'images/tracker/flags/Nicaragua.png', 151, 1), (152, 'Niger', 'images/tracker/flags/Niger.png', 152, 1), (153, 'Nigeria', 'images/tracker/flags/Nigeria.png', 153, 1), ";
			$query .= "(154, 'Niue', 'images/tracker/flags/Niue.png', 154, 1), (155, 'Norfolk Island', 'images/tracker/flags/Norfolk.Island.png', 155, 1), (156, 'North Korea', 'images/tracker/flags/North.Korea.png', 156, 1), ";
			$query .= "(157, 'Northern Mariana Islands', 'images/tracker/flags/Northern.Mariana.Islands.png', 157, 1), (158, 'Norway', 'images/tracker/flags/Norway.png', 158, 1), (159, 'Oman', 'images/tracker/flags/Oman.png', 159, 1), ";
			$query .= "(160, 'Pakistan', 'images/tracker/flags/Pakistan.png', 160, 1), (161, 'Palau', 'images/tracker/flags/Palau.png', 161, 1), (162, 'Palestine', 'images/tracker/flags/Palestine.png', 162, 1), ";
			$query .= "(163, 'Panama', 'images/tracker/flags/Panama.png', 163, 1), (164, 'Papua New Guinea', 'images/tracker/flags/Papua.New.Guinea.png', 164, 1), (165, 'Paraguay', 'images/tracker/flags/Paraguay.png', 165, 1), ";
			$query .= "(166, 'Peru', 'images/tracker/flags/Peru.png', 166, 1), (167, 'Philippines', 'images/tracker/flags/Philippines.png', 167, 1), (168, 'Pitcairn Islands', 'images/tracker/flags/Pitcairn.Islands.png', 168, 1), ";
			$query .= "(169, 'Poland', 'images/tracker/flags/Poland.png', 169, 1), (170, 'Portugal', 'images/tracker/flags/Portugal.png', 170, 1), (171, 'Puerto Rico', 'images/tracker/flags/Puerto.Rico.png', 171, 1), ";
			$query .= "(172, 'Qatar', 'images/tracker/flags/Qatar.png', 172, 1), (173, 'Republic of the Congo', 'images/tracker/flags/Republic.of.the.Congo.png', 173, 1), ";
			$query .= "(175, 'Romania', 'images/tracker/flags/Romania.png', 175, 1), (176, 'Russia', 'images/tracker/flags/Russia.png', 176, 1), (177, 'Rwanda', 'images/tracker/flags/Rwanda.png', 177, 1), ";
			$query .= "(178, 'Saint Helena', 'images/tracker/flags/Saint.Helena.png', 178, 1), (179, 'Saint Kitts and Nevis', 'images/tracker/flags/Saint.Kitts.and.Nevis.png', 179, 1), (180, 'Saint Lucia', 'images/tracker/flags/Saint.Lucia.png', 180, 1), ";
			$query .= "(181, 'Saint Pierre and Miquelon', 'images/tracker/flags/Saint.Pierre.and.Miquelon.png', 181, 1), (182, 'Saint Vincent', 'images/tracker/flags/Saint.Vincent.png', 182, 1), (183, 'Samoa', 'images/tracker/flags/Samoa.png', 183, 1), ";
			$query .= "(184, 'San Marino', 'images/tracker/flags/San.Marino.png', 184, 1), (185, 'Sao Tome and Principe', 'images/tracker/flags/Sao.Tome.and.Principe.png', 185, 1), (186, 'Saudi Arabia', 'images/tracker/flags/Saudi.Arabia.png', 186, 1), ";
			$query .= "(187, 'Scotland', 'images/tracker/flags/Scotland.png', 187, 1), (188, 'Senegal', 'images/tracker/flags/Senegal.png', 188, 1), (189, 'Serbia', 'images/tracker/flags/Serbia.png', 189, 1), ";
			$query .= "(190, 'Seychelles', 'images/tracker/flags/Seychelles.png', 190, 1), (191, 'Sierra Leone', 'images/tracker/flags/Sierra.Leone.png', 191, 1), (192, 'Singapore', 'images/tracker/flags/Singapore.png', 192, 1), ";
			$query .= "(193, 'Slovakia', 'images/tracker/flags/Slovakia.png', 193, 1), (194, 'Slovenia', 'images/tracker/flags/Slovenia.png', 194, 1), (195, 'Solomon Islands', 'images/tracker/flags/Solomon.Islands.png', 195, 1), ";
			$query .= "(196, 'Somalia', 'images/tracker/flags/Somalia.png', 196, 1), (197, 'South Africa', 'images/tracker/flags/South.Africa.png', 197, 1), (198, 'South Georgia and the South Sandwitch Islands', 'images/tracker/flags/South.Georgia.and.the.South.Sandwitch.Islands.png', 198, 1), ";
			$query .= "(199, 'South Korea', 'images/tracker/flags/South.Korea.png', 199, 1), (200, 'Spain', 'images/tracker/flags/Spain.png', 200, 1), (201, 'Sri Lanka', 'images/tracker/flags/Sri.Lanka.png', 201, 1), ";
			$query .= "(202, 'Sudan', 'images/tracker/flags/Sudan.png', 202, 1), (203, 'Suriname', 'images/tracker/flags/Suriname.png', 203, 1), (204, 'Swaziland', 'images/tracker/flags/Swaziland.png', 204, 1), ";
			$query .= "(205, 'Sweden', 'images/tracker/flags/Sweden.png', 205, 1), (206, 'Switzerland', 'images/tracker/flags/Switzerland.png', 206, 1), (207, 'Syria', 'images/tracker/flags/Syria.png', 207, 1), ";
			$query .= "(208, 'Taiwan', 'images/tracker/flags/Taiwan.png', 208, 1), (209, 'Tajikistan', 'images/tracker/flags/Tajikistan.png', 209, 1), (210, 'Tamil Nation', 'images/tracker/flags/Tamil.Nation.png', 210, 1), ";
			$query .= "(211, 'Tanzania', 'images/tracker/flags/Tanzania.png', 211, 1), (212, 'Thailand', 'images/tracker/flags/Thailand.png', 212, 1), (213, 'Tibet', 'images/tracker/flags/Tibet.png', 213, 1), ";
			$query .= "(214, 'Timor Leste', 'images/tracker/flags/Timor.Leste.png', 214, 1), (215, 'Togo', 'images/tracker/flags/Togo.png', 215, 1), (216, 'Tonga', 'images/tracker/flags/Tonga.png', 216, 1), ";
			$query .= "(217, 'Trinidad and Tobago', 'images/tracker/flags/Trinidad.and.Tobago.png', 217, 1), (218, 'Tunisia', 'images/tracker/flags/Tunisia.png', 218, 1), (219, 'Turkey', 'images/tracker/flags/Turkey.png', 219, 1), ";
			$query .= "(220, 'Turkmenistan', 'images/tracker/flags/Turkmenistan.png', 220, 1), (221, 'Turks and Caicos Islands', 'images/tracker/flags/Turks.and.Caicos.Islands.png', 221, 1), (222, 'Tuvalu', 'images/tracker/flags/Tuvalu.png', 222, 1), ";
			$query .= "(223, 'Uganda', 'images/tracker/flags/Uganda.png', 223, 1), (224, 'Ukraine', 'images/tracker/flags/Ukraine.png', 224, 1), (225, 'United Arab Emirates', 'images/tracker/flags/United.Arab.Emirates.png', 225, 1), ";
			$query .= "(226, 'United Kingdom', 'images/tracker/flags/United.Kingdom.png', 226, 1), (227, 'United States', 'images/tracker/flags/United.States.png', 227, 1), (228, 'Uruguay', 'images/tracker/flags/Uruguay.png', 228, 1), ";
			$query .= "(229, 'Uzbekistan', 'images/tracker/flags/Uzbekistan.png', 229, 1), (230, 'Vanuatu', 'images/tracker/flags/Vanuatu.png', 230, 1), (231, 'Vatican City', 'images/tracker/flags/Vatican.City.png', 231, 1), ";
			$query .= "(232, 'Venezuela', 'images/tracker/flags/Venezuela.png', 232, 1), (233, 'Vietnam', 'images/tracker/flags/Vietnam.png', 233, 1), (234, 'Virgin Islands', 'images/tracker/flags/Virgin.Islands.png', 234, 1), ";
			$query .= "(235, 'Wales', 'images/tracker/flags/Wales.png', 235, 1), (236, 'Wallis and Futuna', 'images/tracker/flags/Wallis.and.Futuna.png', 236, 1), (237, 'Yemen', 'images/tracker/flags/Yemen.png', 237, 1), ";
			$query .= "(238, 'Zambia', 'images/tracker/flags/Zambia.png', 238, 1), (239, 'Zimbabwe', 'images/tracker/flags/Zimbabwe.png', 239, 1), (240, 'Antartica', 'images/tracker/flags/Antartica.png', 240, 1), (241, 'Unknown', 'images/tracker/flags/unknown.png', 241, 1) ";
			$db->setQuery($query);
			$db->execute();

			//Insert the default user group
			$query	= $db->getQuery(true);
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
			$db->execute();

			//Insert the default license
			$query = $db->getQuery(true);
			$query->insert('#__tracker_licenses');
			$query->set('shortname = "GNU GPL v3"');
			$query->set('alias = "gnu-gpl-v3"');
			$query->set('fullname = "GNU General Public License (GPL) version 3"');
			$query->set('description = "This is the latest version of the GNU GPL"');
			$query->set('link = "http://www.gnu.org/licenses/gpl-3.0.html"');
			$query->set('ordering = 1');
			$query->set('state = 1');
			$db->setQuery($query);
			$db->execute();

			//Insert the default file types
			$query = $db->getQuery(true);
			$query = "INSERT INTO ".$app->getCfg('dbprefix', 1)."tracker_filetypes VALUES ";
			$query .= "(1, '7z', 'images/tracker/filetypes/7z.png', 1, 1), (2, 'avi', 'images/tracker/filetypes/avi.png', 2, 1), (3, 'bmp', 'images/tracker/filetypes/bmp.png', 3, 1), ";
			$query .= "(4, 'css', 'images/tracker/filetypes/css.png', 4, 1), (5, 'csv', 'images/tracker/filetypes/csv.png', 5, 1), (6, 'default', 'images/tracker/filetypes/default.png', 6, 1), ";
			$query .= "(7, 'dll', 'images/tracker/filetypes/dll.png', 7, 1), (8, 'doc', 'images/tracker/filetypes/doc.png', 8, 1), (9, 'docx', 'images/tracker/filetypes/docx.png', 9, 1), ";
			$query .= "(10, 'dwg', 'images/tracker/filetypes/dwg.png', 10, 1), (11, 'fla', 'images/tracker/filetypes/fla.png', 11, 1), (12, 'fon', 'images/tracker/filetypes/fon.png', 12, 1), ";
			$query .= "(13, 'gif', 'images/tracker/filetypes/gif.png', 13, 1), (14, 'hlp', 'images/tracker/filetypes/hlp.png', 14, 1), (15, 'htm', 'images/tracker/filetypes/htm.png', 15, 1), ";
			$query .= "(16, 'html', 'images/tracker/filetypes/html.png', 16, 1), (17, 'ini', 'images/tracker/filetypes/ini.png', 17, 1), (18, 'jpeg', 'images/tracker/filetypes/jpeg.png', 18, 1), ";
			$query .= "(19, 'jpg', 'images/tracker/filetypes/jpg.png', 19, 1), (20, 'mdb', 'images/tracker/filetypes/mdb.png', 20, 1), (21, 'midi', 'images/tracker/filetypes/midi.png', 21, 1), ";
			$query .= "(22, 'mkv', 'images/tracker/filetypes/mkv.png', 22, 1), (23, 'mov', 'images/tracker/filetypes/mov.png', 23, 1), (24, 'mp3', 'images/tracker/filetypes/mp3.png', 24, 1), ";
			$query .= "(25, 'mp4', 'images/tracker/filetypes/mp4.png', 25, 1), (26, 'mpg', 'images/tracker/filetypes/mpg.png', 26, 1), (27, 'odbc', 'images/tracker/filetypes/odbc.png', 27, 1), ";
			$query .= "(28, 'ogg', 'images/tracker/filetypes/ogg.png', 28, 1), (29, 'pdf', 'images/tracker/filetypes/pdf.png', 29, 1), (30, 'php', 'images/tracker/filetypes/php.png', 30, 1), ";
			$query .= "(31, 'png', 'images/tracker/filetypes/png.png', 31, 1), (32, 'pps', 'images/tracker/filetypes/pps.png', 32, 1), (33, 'ppsx', 'images/tracker/filetypes/ppsx.png', 33, 1), ";
			$query .= "(34, 'ppt', 'images/tracker/filetypes/ppt.png', 34, 1), (35, 'pptx', 'images/tracker/filetypes/pptx.png', 35, 1), (36, 'psd', 'images/tracker/filetypes/psd.png', 36, 1), ";
			$query .= "(37, 'rar', 'images/tracker/filetypes/rar.png', 37, 1), (38, 'reg', 'images/tracker/filetypes/reg.png', 38, 1), (39, 'rtf', 'images/tracker/filetypes/rtf.png', 39, 1), ";
			$query .= "(40, 'sql', 'images/tracker/filetypes/sql.png', 40, 1), (41, 'swf', 'images/tracker/filetypes/swf.png', 41, 1), (42, 'sys', 'images/tracker/filetypes/sys.png', 42, 1), ";
			$query .= "(43, 'tar', 'images/tracker/filetypes/tar.png', 43, 1), (44, 'tif', 'images/tracker/filetypes/tif.png', 44, 1), (45, 'tiff', 'images/tracker/filetypes/tiff.png', 45, 1), ";
			$query .= "(46, 'ttf', 'images/tracker/filetypes/ttf.png', 46, 1), (47, 'txt', 'images/tracker/filetypes/txt.png', 47, 1), (48, 'url', 'images/tracker/filetypes/url.png', 48, 1), ";
			$query .= "(49, 'wav', 'images/tracker/filetypes/wav.png', 49, 1), (50, 'wma', 'images/tracker/filetypes/wma.png', 50, 1), (51, 'wmv', 'images/tracker/filetypes/wmv.png', 51, 1), ";
			$query .= "(52, 'xls', 'images/tracker/filetypes/xls.png', 52, 1), (53, 'xlsx', 'images/tracker/filetypes/xlsx.png', 53, 1), (54, 'xml', 'images/tracker/filetypes/xml.png', 54, 1), ";
			$query .= "(55, 'zip', 'images/tracker/filetypes/zip.png', 55, 1)";
			$db->setQuery($query);
			$db->execute();

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
			$settings['query_log'] 					= 'xbt_tracker_query.log';
			$settings['read_config_interval'] 		= '60';
			$settings['read_db_interval'] 			= '60';
			$settings['redirect_url'] 				= JURI::root();
			$settings['scrape_interval'] 			= '0';
			$settings['write_db_interval'] 			= '15';
			$settings['table_announce_log'] 		= $app->getCfg('dbprefix', 1).'tracker_announce_log';
			$settings['table_files'] 				= $app->getCfg('dbprefix', 1).'tracker_torrents';
			$settings['table_files_users'] 			= $app->getCfg('dbprefix', 1).'tracker_files_users';
			$settings['table_scrape_log'] 			= $app->getCfg('dbprefix', 1).'tracker_scrape_log';
			$settings['table_users'] 				= $app->getCfg('dbprefix', 1).'tracker_users';
			$settings['table_deny_from_clients'] 	= $app->getCfg('dbprefix', 1).'tracker_deny_from_clients';
			$settings['table_deny_from_hosts'] 		= $app->getCfg('dbprefix', 1).'tracker_deny_from_hosts';
			$settings['column_files_completed'] 	= 'completed';
			$settings['column_files_fid'] 			= 'fid';
			$settings['column_files_leechers'] 		= 'leechers';
			$settings['column_files_seeders'] 		= 'seeders';
			$settings['column_users_uid'] 			= 'id';
			$settings['torrent_pass_private_key'] 	= self::code(27);
			foreach($settings as $name => $value) {
				$query = "INSERT INTO xbt_config ( name, value ) VALUES ('" . $name . "', '" . $value . "' );";
				$db->setQuery($query);
				$db->execute();
			}
		}

		// Insert the users into the tracker_users table
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$query	= $db->getQuery(true);
		$query  = "INSERT IGNORE INTO ".$app->getCfg('dbprefix', 1)."tracker_users (id) SELECT id FROM ".$app->getCfg('dbprefix', 1)."users";
		$db->setQuery($query);
		$db->execute();
	}

	private static function code($nc, $a='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
		$l=strlen($a)-1; $r='';
		while($nc-->0) $r.=$a{mt_rand(0,$l)};
		return $r;
	}

}