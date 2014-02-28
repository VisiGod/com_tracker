CREATE TABLE IF NOT EXISTS `#__tracker_announce_log` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`ipa` INT(10) UNSIGNED DEFAULT NULL,
`port` INT(11) DEFAULT NULL,
`event` INT(11) DEFAULT NULL,
`info_hash` BINARY(20) DEFAULT NULL,
`peer_id` BINARY(20) DEFAULT NULL,
`downloaded` BIGINT(20) DEFAULT NULL,
`left0` BIGINT(20) DEFAULT NULL,
`uploaded` BIGINT(20) DEFAULT NULL,
`uid` INT(11) UNSIGNED NOT NULL,
`mtime` INT(11) DEFAULT NULL,
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_comments` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`torrentID` INT(11) NOT NULL,
`created_user_id` INT(10) UNSIGNED NOT NULL,
`created_time` DATETIME DEFAULT NULL,
`comment` TEXT NOT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_countries` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(255) NOT NULL,
`image` VARCHAR(255) NOT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_deny_from_clients` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`peer_id` CHAR(20) NOT NULL,
`peer_description` VARCHAR(255) NOT NULL,
`created_time` DATETIME DEFAULT NULL,
`created_user_id` INT(10) UNSIGNED NOT NULL,
`comment` VARCHAR(255) NOT NULL,
`ordering` INT(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY (`peer_id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_deny_from_hosts` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`created_time` DATETIME DEFAULT NULL,
`created_user_id` INT(10) UNSIGNED NOT NULL,
`comment` VARCHAR(255) NOT NULL,
`begin` INT(10) UNSIGNED DEFAULT NULL,
`end` INT(10) UNSIGNED DEFAULT NULL,
`ordering` INT(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY (`begin`,`end`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_donations` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`uid` INT(11) UNSIGNED NOT NULL,
`ratio` FLOAT(11,2) NOT NULL DEFAULT '0',
`donated` float(11,2) NOT NULL DEFAULT '0',
`donation_date` DATE DEFAULT NULL,
`credited` float(11,2) NOT NULL DEFAULT '0',
`created_time` DATETIME DEFAULT NULL,
`created_user_id` INT(10) UNSIGNED NOT NULL,
`comments` VARCHAR(255) DEFAULT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_filetypes` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(50) NOT NULL,
`image` VARCHAR(255) NOT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_reported_torrents` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`fid` INT(11) UNSIGNED NOT NULL,
`reporter` INT(11) UNSIGNED NOT NULL,
`comments` VARCHAR(255) DEFAULT NULL,
`created_time` DATETIME DEFAULT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_reseed_request` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`fid` INT(11) UNSIGNED NOT NULL,
`requester` INT(11) UNSIGNED NOT NULL,
`created_time` DATETIME DEFAULT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_torrents` (
`fid` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`info_hash` BINARY(20) NOT NULL,
`leechers` INT(11) NOT NULL DEFAULT '0',
`seeders` INT(11) NOT NULL DEFAULT '0',
`completed` INT(11) NOT NULL DEFAULT '0',
`flags` INT(11) NOT NULL DEFAULT '2',
`mtime` INT(11) NOT NULL DEFAULT '0',
`ctime` INT(11) NOT NULL DEFAULT '0',
`name` VARCHAR(255) NOT NULL,
`alias` VARCHAR(255) NOT NULL,
`filename` VARCHAR(255) NOT NULL,
`description` text NOT NULL,
`categoryID` INT(11) UNSIGNED NOT NULL,
`size` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
`created_time` DATETIME DEFAULT NULL,
`uploader` INT(11) UNSIGNED NOT NULL,
`number_files` INT(11) UNSIGNED NOT NULL DEFAULT '0',
`uploader_anonymous` TINYINT(1) NOT NULL DEFAULT '0',
`forum_post` INT(11) UNSIGNED NOT NULL DEFAULT '0',
`info_post` INT(11) UNSIGNED NOT NULL DEFAULT '0',
`download_multiplier` FLOAT(11,2) NOT NULL DEFAULT '1',
`upload_multiplier` FLOAT(11,2) NOT NULL DEFAULT '1',
`licenseID` INT(11) NOT NULL,
`image_file` VARCHAR(255) NOT NULL,
`tags` VARCHAR(16380) NOT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`fid`),
UNIQUE KEY (`info_hash`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_torrent_thanks` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`torrentID` INT(11) UNSIGNED NOT NULL,
`uid` INT(11) UNSIGNED NOT NULL,
`created_time` DATETIME DEFAULT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`),
UNIQUE KEY (`torrentID`,`uid`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_files_in_torrents` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`torrentID` INT(11) UNSIGNED NOT NULL,
`filename` VARCHAR(255) NOT NULL,
`size` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_files_users` (
`fid` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`uid` INT(11) UNSIGNED NOT NULL,
`active` TINYINT(4) NOT NULL DEFAULT '0',
`announced` INT(11) NOT NULL DEFAULT '0',
`completed` INT(11) NOT NULL DEFAULT '0',
`downloaded` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
`left` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
`uploaded` BIGINT(20) NOT NULL DEFAULT '0',
`mtime` INT(11) NOT NULL DEFAULT '0',
`down_rate` INT(10) NOT NULL DEFAULT '0',
`up_rate` INT(10) NOT NULL DEFAULT '0',
UNIQUE KEY (`fid`,`uid`),
KEY (`uid`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_licenses` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`shortname` VARCHAR(20) NOT NULL,
`alias` VARCHAR(255) NOT NULL,
`fullname` varchar(255) NOT NULL,
`description` TEXT NOT NULL,
`link` TEXT NOT NULL,
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_scrape_log` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ipa` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`uid` INT(11) NOT NULL DEFAULT '0',
`mtime` INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_users` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`groupID` INT(11) NOT NULL DEFAULT '1',
`countryID` INT(11) NOT NULL,
`downloaded` BIGINT(20) DEFAULT '0',
`uploaded` BIGINT(20) DEFAULT '0',
`exemption_type` TINYINT(4) NOT NULL DEFAULT '2',
`minimum_ratio` FLOAT(11,2) NOT NULL DEFAULT '1',
`can_leech` TINYINT(1) NOT NULL DEFAULT '0',
`wait_time` INT(11) NOT NULL DEFAULT '0',
`peer_limit` INT(11) NOT NULL DEFAULT '1',
`torrent_limit` INT(11) NOT NULL DEFAULT '1',
`torrent_pass_version` INT(11) NOT NULL DEFAULT '1',
`multiplier_type` TINYINT(1) NOT NULL DEFAULT '0',
`download_multiplier` FLOAT(11,2) NOT NULL DEFAULT '1',
`upload_multiplier` FLOAT(11,2) NOT NULL DEFAULT '1',
`hash` VARCHAR(32) NOT NULL,
`ordering` INT(11) NOT NULL,
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_groups` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(50) NOT NULL,
`view_torrents` TINYINT(1) NOT NULL DEFAULT '0',
`edit_torrents` TINYINT(1) NOT NULL DEFAULT '0',
`delete_torrents` TINYINT(1) NOT NULL DEFAULT '0',
`upload_torrents` TINYINT(1) NOT NULL DEFAULT '0',
`download_torrents` TINYINT(1) NOT NULL DEFAULT '0',
`can_leech` TINYINT(1) NOT NULL DEFAULT '0',
`wait_time` INT(11) NOT NULL DEFAULT '0',
`peer_limit` INT(11) NOT NULL DEFAULT '0',
`torrent_limit` INT(11) NOT NULL DEFAULT '0',
`minimum_ratio` FLOAT(11,2) NOT NULL DEFAULT '1',
`download_multiplier` FLOAT(11,2) NOT NULL DEFAULT '1',
`upload_multiplier` FLOAT(11,2) NOT NULL DEFAULT '1',
`view_comments` TINYINT(1) NOT NULL DEFAULT '1',
`write_comments` TINYINT(1) NOT NULL DEFAULT '0',
`edit_comments` TINYINT(1) NOT NULL DEFAULT '0',
`delete_comments` TINYINT(1) NOT NULL DEFAULT '0',
`autopublish_comments` TINYINT(1) NOT NULL DEFAULT '0',
`ordering` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__tracker_rss` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(50) NOT NULL,
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
);

CREATE TABLE IF NOT EXISTS `xbt_config` (
`name` VARCHAR(255) DEFAULT NULL,
`value` VARCHAR(255) NOT NULL
);
