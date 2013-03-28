<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';
jimport('joomla.html.parameter');
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

if ($this->params->get('number_torrents') || $this->params->get('number_files') || $this->params->get('total_seeders') || 
	$this->params->get('total_leechers') || $this->params->get('total_completed') || $this->params->get('bytes_shared') ||
	$this->params->get('download_speed') || $this->params->get('upload_speed') ) {
	echo JHtml::_('tabs.start', 'top_stats', $tab_options);
	echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATISTICS_TOTALS'), 'top_stats');
	echo '<div id="container">';
	echo '<div id="row">';
	if ($this->params->get('number_torrents')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_TORRENTS').'&nbsp;</b></div>';
	if ($this->params->get('number_files')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_FILES').'&nbsp;</b></div>';
	if ($this->params->get('total_seeders')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SEEDERS').'&nbsp;</b></div>';
	if ($this->params->get('total_leechers')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_LEECHERS').'&nbsp;</b></div>';
	if ($this->params->get('total_completed')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COMPLETED').'&nbsp;</b></div>';
	if ($this->params->get('bytes_shared')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_SHARED_DATA').'&nbsp;</b></div>';
	echo '</div><div id="row">';
	if ($this->params->get('number_torrents'))	echo '<div id="value-center">&nbsp;'.$this->item->torrents.'&nbsp;</div>';
	if ($this->params->get('number_files')) 	echo '<div id="value-center">&nbsp;'.$this->item->files.'&nbsp;</div>';
	if ($this->params->get('total_seeders')) 	echo '<div id="value-center">&nbsp;'.$this->item->seeders.'&nbsp;</div>';
	if ($this->params->get('total_leechers')) 	echo '<div id="value-center">&nbsp;'.$this->item->leechers.'&nbsp;</div>';
	if ($this->params->get('total_completed')) 	echo '<div id="value-center">&nbsp;'.$this->item->completed.'&nbsp;</div>';
	if ($this->params->get('bytes_shared')) 	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($this->item->shared).'&nbsp;</div>';
	echo '</div></div><br /><br />';
	echo '<div id="container">';
	echo '<div id="row">';
	if ($this->params->get('download_speed')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOAD_SPEED').'&nbsp;</b></div>';
	if ($this->params->get('upload_speed')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOAD_SPEED').'&nbsp;</b></div>';
	if ($this->params->get('bytes_downloaded')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED_DATA').'&nbsp;</b></div>';
	if ($this->params->get('bytes_uploaded')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED_DATA').'&nbsp;</b></div>';
	if ($this->params->get('bytes_downloaded') || $this->params->get('bytes_uploaded')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_TOTAL_DATA').'&nbsp;</b></div>';
	echo '</div><div id="row">';
	if ($this->params->get('download_speed')) echo '<div id="value-center">&nbsp;'.TrackerHelper::make_speed($this->item->total_speed->download_rate).'&nbsp;</div>';
	if ($this->params->get('upload_speed')) echo '<div id="value-center">&nbsp;'.TrackerHelper::make_speed($this->item->total_speed->upload_rate).'&nbsp;</div>';
	if ($this->params->get('bytes_downloaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($this->item->total_transferred->user_downloaded).'&nbsp;</div>';
	if ($this->params->get('bytes_uploaded')) 	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($this->item->total_transferred->user_uploaded).'&nbsp;</div>';
	if ($this->params->get('bytes_downloaded') || $this->params->get('bytes_uploaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($this->item->total_transferred->user_downloaded + $this->item->total_transferred->user_uploaded).'&nbsp;</div>';
	echo '</div></div>';
	echo JHtml::_('tabs.end');

}

if (($this->params->get('top_downloaders') && count($this->item->top_downloaders)) || ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) || 
	($this->params->get('top_sharers') && count($this->item->top_sharers)) || ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) || 
	($this->params->get('top_thanked') && count($this->item->top_thanked)) ||($this->params->get('top_thanker') && count($this->item->top_thanker))) {
	echo JHtml::_('tabs.start', 'top_users', $tab_options);
	if ($this->params->get('top_downloaders') && count($this->item->top_downloaders)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_DOWNLOADERS'), 'top_downloaders');
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->top_downloaders as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$item->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;</div>';
			if (empty($item->countryName)) {
				$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
				$item->countryName = $item->default_country->name; 
				$item->countryImage = $item->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;" id="tdcountry<'.$item->uid.'" alt="'.$item->countryName.'" src="'.JURI::base().$item->countryImage.'" width="32px" /></div>';
			echo '<div id="value-center">&nbsp;'.$item->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_UPLOADERS'), 'top_uploaders');
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->top_uploaders as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$item->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;</div>';
			if (empty($item->countryName)) {
				$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
				$item->countryName = $item->default_country->name; 
				$item->countryImage = $item->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$item->uid.'" alt="'.$item->countryName.'" src="'.JURI::base().$item->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$item->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	
	if ($this->params->get('top_sharers') && count($this->item->top_sharers)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_SHARERS'), 'top_sharers');
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_RATIO').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->top_sharers as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$item->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::get_ratio($item->uploaded, $item->downloaded).'&nbsp;</div>';
			if (empty($item->countryName)) {
				$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
				$item->countryName = $item->default_country->name; 
				$item->countryImage = $item->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$item->uid.'" alt="'.$item->countryName.'" src="'.JURI::base().$item->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$item->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_WORST_SHARERS'), 'worst_sharers');
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_RATIO').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->worst_sharers as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$item->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->downloaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($item->uploaded).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.TrackerHelper::get_ratio($item->uploaded, $item->downloaded).'&nbsp;</div>';
			if (empty($item->countryName)) {
				$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
				$item->countryName = $item->default_country->name; 
				$item->countryImage = $item->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$item->uid.'" alt="'.$item->countryName.'" src="'.JURI::base().$item->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$item->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
		
	if ($this->params->get('top_thanked') && count($this->item->top_thanked)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_THANKED'), 'top_thanked');
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_THANKED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->top_thanked as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$item->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->total_thanks.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$item->uid.'" alt="'.$item->countryName.'" src="'.JURI::base().$item->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$item->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}

	if ($this->params->get('top_thanker') && count($this->item->top_thanker)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_THANKERS'), 'top_thanker');
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_THANKER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->top_thanker as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;'.$item->name.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->thanker.'&nbsp;</div>';
			if (empty($item->countryName)) {
				$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
				$item->countryName = $item->default_country->name;
				$item->countryImage = $item->default_country->image;
			}
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$item->uid.'" alt="'.$item->countryName.'" src="'.JURI::base().$item->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$item->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	
	echo JHtml::_('tabs.end');
}

if (($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) || 
	($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) || 
	($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) || 
	($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) ||
	($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents))) {
	echo JHtml::_('tabs.start', 'most_torrents', $tab_options);
	if ($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_ACTIVE_TORRENTS'), 'most_active');
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
		foreach ($this->item->most_active_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_SEEDED_TORRENTS'), 'most_seeded');
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
		foreach ($this->item->most_seeded_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_LEECHED_TORRENTS'), 'most_leeched');
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
		foreach ($this->item->most_leeched_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_COMPLETED_TORRENTS'), 'most_completed');
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
		foreach ($this->item->most_completed_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	
	if ($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_TOP_THANKED_TORRENTS'), 'most_thanked_torrents');
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
		foreach ($this->item->top_thanked_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->total_thanks.'&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	echo JHtml::_('tabs.end');
}

if (($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) || ($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) || ($this->params->get('worst_leeched_torrents') && count($this->item->worst_leeched_torrents)) || ($this->params->get('worst_completed_torrents') && count($this->item->worst_completed_torrents))) {
	echo JHtml::_('tabs.start', 'worst_torrents', $tab_options);
	if ($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_WORST_ACTIVE_TORRENTS'), 'worst_active');
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
		foreach ($this->item->worst_active_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_WORST_SEEDED_TORRENTS'), 'worst_seeded');
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
		foreach ($this->item->worst_seeded_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('worst_leeched_torrents') && count($this->item->worst_leeched_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_WORST_LEECHED_TORRENTS'), 'worst_leeched');
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
		foreach ($this->item->worst_leeched_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	if ($this->params->get('worst_completed_torrents') && count($this->item->worst_completed_torrents)) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_STATS_WORST_COMPLETED_TORRENTS'), 'worst_completed');
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
		foreach ($this->item->worst_completed_torrents as $item) {
			echo'<div id="row">';
			echo '<div id="value" style="overflow: hidden; white-space: pre-wrap;">&nbsp;';
			if (TrackerHelper::user_permissions('download_torrents', $session->get('user')->id, 1))
				echo '<a href="index.php?option=com_tracker&view=torrent&id='.$item->fid.'">'.$item->name.'</a>';
			else echo $item->name;
			echo '&nbsp;</div>';
			echo '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.date ('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
			//if (empty($item->country)) $item->country = TrackerHelper::getCountryFlag($this->params->get('defaultcountry'));
			$category_params = new JParameter( $item->cat_params );
			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	echo JHtml::_('tabs.end');
}
