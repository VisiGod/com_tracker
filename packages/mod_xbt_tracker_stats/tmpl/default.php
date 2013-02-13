<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_stats
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_ADMINISTRATOR.'/components/com_tracker/helpers/tracker.php';
jimport( 'joomla.html.parameter' );

$user	= JFactory::getUser();
$appParams = $app->getParams('com_tracker');
$document =& JFactory::getDocument();
$style = '#container {
						display: table;
						width: 99%;
					}
					#caption { 
						display: table-caption;
						text-align: center;
					}
					#row  {
						display: table-row;
					}
					#value {
						display: table-cell;
						white-space: nowrap;
					}
					#value-right {
						display: table-cell;
						text-align: right;
						white-space: nowrap;
					}
					#value-left {
						display: table-cell;
						text-align: left;
						white-space: nowrap;
					}
					#value-center {
						display: table-cell;
						text-align: center;
						white-space: nowrap;
					}';
					
$document->addStyleDeclaration( $style );

	if ($params->get('number_torrents') || $params->get('number_files') || $params->get('total_seeders') || $params->get('total_leechers') || $params->get('total_completed') || $params->get('bytes_shared')) {
		echo '<div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_TOTALS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		if ($params->get('number_torrents')) 	echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_TORRENTS').'&nbsp;</h4><hr /></div>';
		if ($params->get('number_files')) 		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_FILES').'&nbsp;</h4><hr /></div>';
		if ($params->get('total_seeders')) 		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		if ($params->get('total_leechers')) 	echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		if ($params->get('total_completed')) 	echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		if ($params->get('bytes_shared')) 		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SHARED_DATA').'&nbsp;</h4><hr /></div>';
		echo '</div><div id="row">';
		if ($params->get('number_torrents'))	echo '<div id="value-center">&nbsp;'.$list->torrents.'&nbsp;<hr /></div>';
		if ($params->get('number_files')) 		echo '<div id="value-center">&nbsp;'.$list->files.'&nbsp;<hr /></div>';
		if ($params->get('total_seeders')) 		echo '<div id="value-center">&nbsp;'.$list->seeders.'&nbsp;<hr /></div>';
		if ($params->get('total_leechers')) 	echo '<div id="value-center">&nbsp;'.$list->leechers.'&nbsp;<hr /></div>';
		if ($params->get('total_completed')) 	echo '<div id="value-center">&nbsp;'.$list->completed.'&nbsp;<hr /></div>';
		if ($params->get('bytes_shared')) 		echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($list->shared).'&nbsp;<hr /></div>';
		echo '</div></div>';
	}

	if ($params->get('bytes_downloaded') || $params->get('bytes_uploaded') || $params->get('bytes_transferred')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_TRANSFERRED_DATA').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		if ($params->get('bytes_downloaded')) echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_DOWNLOADED_DATA').'&nbsp;</h4><hr /></div>';
		if ($params->get('bytes_uploaded')) 	echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_UPLOADED_DATA').'&nbsp;</h4><hr /></div>';
		if ($params->get('bytes_downloaded') || $params->get('bytes_uploaded')) echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_TOTAL_DATA').'&nbsp;</h4><hr /></div>';
		echo '</div><div id="row">';
		if ($params->get('bytes_downloaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($list->total_transferred->user_downloaded).'&nbsp;<hr /></div>';
		if ($params->get('bytes_uploaded')) 	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($list->total_transferred->user_uploaded).'&nbsp;<hr /></div>';
		if ($params->get('bytes_downloaded') || $params->get('bytes_uploaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($list->total_transferred->user_downloaded + $list->total_transferred->user_uploaded).'&nbsp;<hr /></div>';
		echo '</div></div>';
	}

	if ($params->get('top_downloaders') && count($list->top_downloaders) && $params->get('number_top_downloaders')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_TOP_DOWNLOADERS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_USER').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_DOWNLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_UPLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COUNTRY').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_GROUP').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->top_downloaders as $item) {
			echo'<div id="row">';
			echo '<div id="value-center">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			echo '<div id="value-center">&nbsp;<img id="tdcountry<'.$item->uid.'" alt="'.$item->countryname.'" src="'.JURI::base().'components/com_tracker/assets/images/flags/'.$item->country.'" width="32" /><hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->level.'&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('top_uploaders') && count($list->top_uploaders) && $params->get('number_top_uploaders')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_TOP_UPLOADERS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_USER').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_DOWNLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_UPLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COUNTRY').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_GROUP').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->top_uploaders as $item) {
			echo'<div id="row">';
			echo '<div id="value-center">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			echo '<div id="value-center">&nbsp;<img id="tdcountry<'.$item->uid.'" alt="'.$item->countryname.'" src="'.JURI::base().'components/com_tracker/assets/images/flags/'.$item->country.'" width="32" /><hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->level.'&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('top_sharers') && count($list->top_sharers) && $params->get('number_top_sharers')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_TOP_SHARERS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_USER').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_DOWNLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_UPLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_RATIO').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COUNTRY').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_GROUP').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->top_sharers as $item) {
			echo'<div id="row">';
			echo '<div id="value-center">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->ratio.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			echo '<div id="value-center">&nbsp;<img id="tdcountry<'.$item->uid.'" alt="'.$item->countryname.'" src="'.JURI::base().'components/com_tracker/assets/images/flags/'.$item->country.'" width="32" /><hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->level.'&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('worst_sharers') && count($list->worst_sharers) && $params->get('number_worst_sharers')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_WORST_SHARERS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_USER').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_DOWNLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_UPLOADED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_RATIO').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COUNTRY').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_GROUP').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->worst_sharers as $item) {
			echo'<div id="row">';
			echo '<div id="value-center">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->ratio.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			echo '<div id="value-center">&nbsp;<img id="tdcountry<'.$item->uid.'" alt="'.$item->countryname.'" src="'.JURI::base().'components/com_tracker/assets/images/flags/'.$item->country.'" width="32" /><hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->level.'&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('most_active_torrents') && count($list->most_active_torrents) && $params->get('number_most_active_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_MOST_ACTIVE_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->most_active_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('most_seeded_torrents') && count($list->most_seeded_torrents) && $params->get('number_most_seeded_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_MOST_SEEDED_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->most_seeded_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('most_leeched_torrents') && count($list->most_leeched_torrents) && $params->get('number_most_leeched_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_MOST_LEECHED_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->most_leeched_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('most_completed_torrents') && count($list->most_completed_torrents) && $params->get('number_most_completed_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_MOST_COMPLETED_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->most_completed_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('worst_active_torrents') && count($list->worst_active_torrents) && $params->get('number_worst_active_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_WORST_ACTIVE_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->worst_active_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('worst_seeded_torrents') && count($list->worst_seeded_torrents) && $params->get('number_worst_seeded_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_WORST_SEEDED_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->worst_seeded_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('worst_leeched_torrents') && count($list->worst_leeched_torrents) && $params->get('number_worst_leeched_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_WORST_LEECHED_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->worst_leeched_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('worst_completed_torrents') && count($list->worst_completed_torrents) && $params->get('number_worst_completed_torrents')) {
		echo '<br /><div id="container">';
		echo '<div id="caption"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_WORST_COMPLETED_TORRENTS').'&nbsp;</h3><hr /></div>';
		echo '<div id="row">';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_NAME').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SIZE').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_ADDED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_SEEDERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_LEECHERS').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('MOD_XBT_TRACKER_STATS_COMPLETED').'&nbsp;</h4><hr /></div>';
		echo '<div id="value-center"><h4>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</h4><hr /></div>';
		echo '</div>';
		foreach ($list->worst_completed_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value">&nbsp;'.$item->name.'&nbsp;<hr /></div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
			if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($appParams->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;<hr /></div>';
			echo '</div>';
		}
		echo '</div>';
	}

echo '</div>';