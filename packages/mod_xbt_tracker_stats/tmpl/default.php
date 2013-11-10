<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_stats
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.html.parameter' );

$user	= JFactory::getUser();
$appParams = $app->getParams('com_tracker');

JHTML::_('behavior.modal', 'a.modal', array('handler' => 'ajax'));
$session	= JFactory::getSession();
$tab_options = array(
		'onActive' => 'function(title, description){
		description.setStyle("display", "block");
		title.addClass("open").removeClass("closed");
	}',
		'onBackground' => 'function(title, description){
		description.setStyle("display", "none");
		title.addClass("closed").removeClass("open");
	}',
		'startOffset' => 0,
		'useCookie' => true,
);
$document = JFactory::getDocument();
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
						white-space: pre-wrap;
					}
					#value-right {
						display: table-cell;
						text-align: right;
						white-space: pre-wrap;
					}
					#value-left {
						display: table-cell;
						text-align: left;
						white-space: pre-wrap;
					}
					#value-center {
						display: table-cell;
						text-align: center;
						white-space: pre-wrap;
					}';
					
$document->addStyleDeclaration( $style );

if ($params->get('number_torrents') || $params->get('number_files') || $params->get('total_seeders') || 
	$params->get('total_leechers') || $params->get('total_completed') || $params->get('bytes_shared') ||
	$params->get('download_speed') || $params->get('upload_speed') ) {
	echo '<div id="container">';
	echo '<div id="row">';
	if ($params->get('number_torrents')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_TORRENTS').'&nbsp;</b></div>';
	if ($params->get('number_files')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_FILES').'&nbsp;</b></div>';
	if ($params->get('total_seeders')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SEEDERS').'&nbsp;</b></div>';
	if ($params->get('total_leechers')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_LEECHERS').'&nbsp;</b></div>';
	if ($params->get('total_completed')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COMPLETED').'&nbsp;</b></div>';
	if ($params->get('bytes_shared')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SHARED_DATA').'&nbsp;</b></div>';
	echo '</div><div id="row">';
	if ($params->get('number_torrents'))	echo '<div id="value-center">&nbsp;'.$tracker_stats->torrents.'&nbsp;</div>';
	if ($params->get('number_files')) 	echo '<div id="value-center">&nbsp;'.$tracker_stats->files.'&nbsp;</div>';
	if ($params->get('total_seeders')) 	echo '<div id="value-center">&nbsp;'.$tracker_stats->seeders.'&nbsp;</div>';
	if ($params->get('total_leechers')) 	echo '<div id="value-center">&nbsp;'.$tracker_stats->leechers.'&nbsp;</div>';
	if ($params->get('total_completed')) 	echo '<div id="value-center">&nbsp;'.$tracker_stats->completed.'&nbsp;</div>';
	if ($params->get('bytes_shared')) 	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($tracker_stats->shared).'&nbsp;</div>';
	echo '</div></div><br /><br />';
	echo '<div id="container">';
	echo '<div id="row">';
	if ($params->get('download_speed')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOAD_SPEED').'&nbsp;</b></div>';
	if ($params->get('upload_speed')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOAD_SPEED').'&nbsp;</b></div>';
	if ($params->get('bytes_downloaded')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED_DATA').'&nbsp;</b></div>';
	if ($params->get('bytes_uploaded')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED_DATA').'&nbsp;</b></div>';
	if ($params->get('bytes_downloaded') || $params->get('bytes_uploaded')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_TOTAL_DATA').'&nbsp;</b></div>';
	echo '</div><div id="row">';
	if ($params->get('download_speed')) echo '<div id="value-center">&nbsp;'.TrackerHelper::make_speed($tracker_stats->total_speed->download_rate).'&nbsp;</div>';
	if ($params->get('upload_speed')) echo '<div id="value-center">&nbsp;'.TrackerHelper::make_speed($tracker_stats->total_speed->upload_rate).'&nbsp;</div>';
	if ($params->get('bytes_downloaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($tracker_stats->total_transferred->user_downloaded).'&nbsp;</div>';
	if ($params->get('bytes_uploaded')) 	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($tracker_stats->total_transferred->user_uploaded).'&nbsp;</div>';
	if ($params->get('bytes_downloaded') || $params->get('bytes_uploaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($tracker_stats->total_transferred->user_downloaded + $tracker_stats->total_transferred->user_uploaded).'&nbsp;</div>';
	echo '</div></div>';

}

if (($params->get('top_downloaders') && count($tracker_stats->top_downloaders)) || ($params->get('top_uploaders') && count($tracker_stats->top_uploaders)) || 
	($params->get('top_sharers') && count($tracker_stats->top_sharers)) || ($params->get('worst_sharers') && count($tracker_stats->worst_sharers)) || 
	($params->get('top_thanked') && count($tracker_stats->top_thanked)) ||($params->get('top_thanker') && count($tracker_stats->top_thanker))) {
	if ($params->get('top_downloaders') && count($tracker_stats->top_downloaders)) {
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->top_downloaders as $top_downloaders) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$top_downloaders->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($top_downloaders->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($top_downloaders->uploaded).'&nbsp;</div>';
			if (empty($top_downloaders->countryName)) {
				$top_downloaders->default_country = TrackerHelper::getCountryDetails($params->get('defaultcountry'));
				$top_downloaders->countryName = $top_downloaders->default_country->name; 
				$top_downloaders->countryImage = $top_downloaders->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;" id="tdcountry<'.$top_downloaders->uid.'" alt="'.$top_downloaders->countryName.'" src="'.JURI::base().$top_downloaders->countryImage.'" width="32px" /></div>';
			echo '<div id="value-center">&nbsp;'.$top_downloaders->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('top_uploaders') && count($tracker_stats->top_uploaders)) {
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->top_uploaders as $top_uploaders) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$top_uploaders->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($top_uploaders->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($top_uploaders->uploaded).'&nbsp;</div>';
			if (empty($top_uploaders->countryName)) {
				$top_uploaders->default_country = TrackerHelper::getCountryDetails($params->get('defaultcountry'));
				$top_uploaders->countryName = $top_uploaders->default_country->name; 
				$top_uploaders->countryImage = $top_uploaders->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$top_uploaders->uid.'" alt="'.$top_uploaders->countryName.'" src="'.JURI::base().$top_uploaders->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$top_uploaders->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	
	if ($params->get('top_sharers') && count($tracker_stats->top_sharers)) {
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_RATIO').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->top_sharers as $top_sharers) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$top_sharers->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($top_sharers->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($top_sharers->uploaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::get_ratio($top_sharers->uploaded, $top_sharers->downloaded).'&nbsp;</div>';
			if (empty($top_sharers->countryName)) {
				$top_sharers->default_country = TrackerHelper::getCountryDetails($params->get('defaultcountry'));
				$top_sharers->countryName = $top_sharers->default_country->name; 
				$top_sharers->countryImage = $top_sharers->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$top_sharers->uid.'" alt="'.$top_sharers->countryName.'" src="'.JURI::base().$top_sharers->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$top_sharers->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('worst_sharers') && count($tracker_stats->worst_sharers)) {
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_RATIO').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->worst_sharers as $worst_sharers) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$worst_sharers->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($worst_sharers->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($worst_sharers->uploaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::get_ratio($worst_sharers->uploaded, $worst_sharers->downloaded).'&nbsp;</div>';
			if (empty($worst_sharers->countryName)) {
				$worst_sharers->default_country = TrackerHelper::getCountryDetails($params->get('defaultcountry'));
				$worst_sharers->countryName = $worst_sharers->default_country->name; 
				$worst_sharers->countryImage = $worst_sharers->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$worst_sharers->uid.'" alt="'.$worst_sharers->countryName.'" src="'.JURI::base().$worst_sharers->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$worst_sharers->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
		
	if ($params->get('top_thanked') && count($tracker_stats->top_thanked)) {
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_THANKED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->top_thanked as $top_thanked) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$top_thanked->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$top_thanked->total_thanks.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$top_thanked->uid.'" alt="'.$top_thanked->countryName.'" src="'.JURI::base().$top_thanked->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$top_thanked->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($params->get('top_thanker') && count($tracker_stats->top_thanker)) {
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_THANKER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->top_thanker as $top_thanker) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$top_thanker->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$top_thanker->thanker.'&nbsp;</div>';
			if (empty($top_thanker->countryName)) {
				$top_thanker->default_country = TrackerHelper::getCountryDetails($params->get('defaultcountry'));
				$top_thanker->countryName = $top_thanker->default_country->name;
				$top_thanker->countryImage = $top_thanker->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$top_thanker->uid.'" alt="'.$top_thanker->countryName.'" src="'.JURI::base().$top_thanker->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$top_thanker->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	
}

if (($params->get('most_active_torrents') && count($tracker_stats->most_active_torrents)) || 
	($params->get('most_seeded_torrents') && count($tracker_stats->most_seeded_torrents)) || 
	($params->get('most_leeched_torrents') && count($tracker_stats->most_leeched_torrents)) || 
	($params->get('most_completed_torrents') && count($tracker_stats->most_completed_torrents)) ||
	($params->get('most_thanked_torrents') && count($tracker_stats->top_thanked_torrents))) {
	if ($params->get('most_active_torrents') && count($tracker_stats->most_active_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->most_active_torrents as $most_active_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$most_active_torrents->fid.'">'.$most_active_torrents->name.'</a>';
			else echo $most_active_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($most_active_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($most_active_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_active_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_active_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_active_torrents->completed.'&nbsp;</div>';
			$category_params = new JParameter( $most_active_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$most_active_torrents->fid.'" alt="'.$most_active_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $most_active_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('most_seeded_torrents') && count($tracker_stats->most_seeded_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->most_seeded_torrents as $most_seeded_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$most_seeded_torrents->fid.'">'.$most_seeded_torrents->name.'</a>';
			else echo $most_seeded_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($most_seeded_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($most_seeded_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_seeded_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_seeded_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_seeded_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $most_seeded_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$most_seeded_torrents->fid.'" alt="'.$most_seeded_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $most_seeded_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('most_leeched_torrents') && count($tracker_stats->most_leeched_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->most_leeched_torrents as $most_leeched_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$most_leeched_torrents->fid.'">'.$most_leeched_torrents->name.'</a>';
			else echo $most_leeched_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($most_leeched_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($most_leeched_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_leeched_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_leeched_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_leeched_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $most_leeched_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$most_leeched_torrents->fid.'" alt="'.$most_leeched_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $most_leeched_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('most_completed_torrents') && count($tracker_stats->most_completed_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->most_completed_torrents as $most_completed_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$most_completed_torrents->fid.'">'.$most_completed_torrents->name.'</a>';
			else echo $most_completed_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($most_completed_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($most_completed_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_completed_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_completed_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$most_completed_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $most_completed_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$most_completed_torrents->fid.'" alt="'.$most_completed_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $most_completed_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	
	if ($params->get('most_thanked_torrents') && count($tracker_stats->top_thanked_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_THANKED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->top_thanked_torrents as $top_thanked_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$top_thanked_torrents->fid.'">'.$top_thanked_torrents->name.'</a>';
			else echo $top_thanked_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$top_thanked_torrents->total_thanks.'&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($top_thanked_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($top_thanked_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$top_thanked_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$top_thanked_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$top_thanked_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $top_thanked_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$top_thanked_torrents->fid.'" alt="'.$top_thanked_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $top_thanked_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}

if (($params->get('worst_active_torrents') && count($tracker_stats->worst_active_torrents)) || ($params->get('worst_seeded_torrents') && count($tracker_stats->worst_seeded_torrents)) || ($params->get('worst_leeched_torrents') && count($tracker_stats->worst_leeched_torrents)) || ($params->get('worst_completed_torrents') && count($tracker_stats->worst_completed_torrents))) {
	if ($params->get('worst_active_torrents') && count($tracker_stats->worst_active_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->worst_active_torrents as $worst_active_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$worst_active_torrents->fid.'">'.$worst_active_torrents->name.'</a>';
			else echo $worst_active_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($worst_active_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($worst_active_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_active_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_active_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_active_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $worst_active_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$worst_active_torrents->fid.'" alt="'.$worst_active_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $worst_active_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('worst_seeded_torrents') && count($tracker_stats->worst_seeded_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->worst_seeded_torrents as $worst_seeded_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$worst_seeded_torrents->fid.'">'.$worst_seeded_torrents->name.'</a>';
			else echo $worst_seeded_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($worst_seeded_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($worst_seeded_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_seeded_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_seeded_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_seeded_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $worst_seeded_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$worst_seeded_torrents->fid.'" alt="'.$worst_seeded_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $worst_seeded_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('worst_leeched_torrents') && count($tracker_stats->worst_leeched_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->worst_leeched_torrents as $worst_leeched_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$worst_leeched_torrents->fid.'">'.$worst_leeched_torrents->name.'</a>';
			else echo $worst_leeched_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($worst_leeched_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($worst_leeched_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_leeched_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_leeched_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_leeched_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $worst_leeched_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$worst_leeched_torrents->fid.'" alt="'.$worst_leeched_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $worst_leeched_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($params->get('worst_completed_torrents') && count($tracker_stats->worst_completed_torrents)) {
		echo '<br /><div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_NAME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SIZE').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_CREATED_TIME').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_SEEDERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_LEECHERS_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_TORRENT_COMPLETED_SMALL').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('JCATEGORY').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($tracker_stats->worst_completed_torrents as $worst_completed_torrents) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$worst_completed_torrents->fid.'">'.$worst_completed_torrents->name.'</a>';
			else echo $worst_completed_torrents->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($worst_completed_torrents->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($worst_completed_torrents->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_completed_torrents->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_completed_torrents->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$worst_completed_torrents->completed.'&nbsp;</div>';
			//if (empty($tracker_stats->country)) $tracker_stats->country = TrackerHelper::getCountryFlag($params->get('defaultcountry'));
			$category_params = new JParameter( $worst_completed_torrents->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$worst_completed_torrents->fid.'" alt="'.$worst_completed_torrents->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $worst_completed_torrents->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}