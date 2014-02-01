<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

JHTML::_('behavior.modal', 'a.modal', array('handler' => 'ajax'));
$session	= JFactory::getSession();

$doc = JFactory::getDocument();
$doc->addScript("http://code.jquery.com/jquery-1.9.1.js");
$doc->addScript("http://code.jquery.com/ui/1.10.2/jquery-ui.js");
$doc->addStyleSheet("http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css");
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
$doc->addStyleDeclaration( $style );
$tabs_jquery = '
<script>
$( "#tabs-stats" ).tabs();
$( "#tabs-users" ).tabs();
$( "#tabs-best" ).tabs();
$( "#tabs-worst" ).tabs();
</script>
';
?>
<script type="text/javascript">
jQuery.noConflict();
(function($) {
	$(function() {
		$( "#tabs-stats" ).tabs();
		$( "#tabs-users" ).tabs();
		$( "#tabs-best" ).tabs();
		$( "#tabs-worst" ).tabs();

// (function($, undefined) {
// 	$(function() { // onload
// 		var base_set = ($('base').length != 0);
// 		var current_location = window.location.href.split('#')[0];
	
// 		function init_tabs(tabsid) {
// 			if (base_set) {
// 				$('#' tabsid ' > ul a').each(function() {
// 					var link_hash = $(this).attr('href');
// 					if (link_hash[0] === '#') {
// 						$(this).attr('href', current_location link_hash);
// 					}
// 				});
// 			}
	
// 			$('#' tabsid).tabs();
// 		}
	
// 		init_tabs('tabs-stats');
// 		init_tabs('tabs-users');
// 		init_tabs('tabs-best');
// 		init_tabs('tabs-worst');
	});
})(jQuery);
</script>
<?php 
if ($this->params->get('number_torrents') || $this->params->get('number_files') || $this->params->get('total_seeders') || 
	$this->params->get('total_leechers') || $this->params->get('total_completed') || $this->params->get('bytes_shared') ||
	$this->params->get('download_speed') || $this->params->get('upload_speed') ) {

?>
<div id="tabs-stats">
	<ul>
		<li><a href="#statistics"><?php echo JText::_('COM_TRACKER_STATISTICS_TOTALS'); ?></a></li>
	</ul>
<?php
	echo '<div id="statistics">';
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
	if ($this->params->get('download_speed') && $this->params->get('peer_speed')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOAD_SPEED').'&nbsp;</b></div>';
	if ($this->params->get('upload_speed') && $this->params->get('peer_speed')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOAD_SPEED').'&nbsp;</b></div>';
	if ($this->params->get('bytes_downloaded')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_DOWNLOADED_DATA').'&nbsp;</b></div>';
	if ($this->params->get('bytes_uploaded')) 	echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_UPLOADED_DATA').'&nbsp;</b></div>';
	if ($this->params->get('bytes_downloaded') || $this->params->get('bytes_uploaded')) echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_TOTAL_DATA').'&nbsp;</b></div>';
	echo '</div><div id="row">';
	if ($this->params->get('download_speed') && $this->params->get('peer_speed')) echo '<div id="value-center">&nbsp;'.TrackerHelper::make_speed($this->item->total_speed->download_rate).'&nbsp;</div>';
	if ($this->params->get('upload_speed') && $this->params->get('peer_speed')) echo '<div id="value-center">&nbsp;'.TrackerHelper::make_speed($this->item->total_speed->upload_rate).'&nbsp;</div>';
	if ($this->params->get('bytes_downloaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($this->item->total_transferred->user_downloaded).'&nbsp;</div>';
	if ($this->params->get('bytes_uploaded')) 	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($this->item->total_transferred->user_uploaded).'&nbsp;</div>';
	if ($this->params->get('bytes_downloaded') || $this->params->get('bytes_uploaded'))	echo '<div id="value-center">&nbsp;'.TrackerHelper::make_size($this->item->total_transferred->user_downloaded + $this->item->total_transferred->user_uploaded).'&nbsp;</div>';
	echo '</div></div></div>';
}
?>
</div>
<?php 

if (($this->params->get('top_downloaders') && count($this->item->top_downloaders)) || ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) || 
	($this->params->get('top_sharers') && count($this->item->top_sharers)) || ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) || 
	($this->params->get('top_thanked') && count($this->item->top_thanked)) ||($this->params->get('top_thanker') && count($this->item->top_thanker))) {
?>
<br />
<div id="tabs-users">
	<ul>
		<?php if ($this->params->get('top_downloaders') && count($this->item->top_downloaders)) { ?><li><a href="#top-downloaders"><?php echo JText::_('COM_TRACKER_STATS_TOP_DOWNLOADERS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) { ?><li><a href="#top-uploaders"><?php echo JText::_('COM_TRACKER_STATS_TOP_UPLOADERS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('top_sharers') && count($this->item->top_sharers)) { ?><li><a href="#top-sharers"><?php echo JText::_('COM_TRACKER_STATS_TOP_SHARERS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) { ?><li><a href="#worst-sharers"><?php echo JText::_('COM_TRACKER_STATS_WORST_SHARERS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('top_thanked') && count($this->item->top_thanked)) { ?><li><a href="#top-thanked"><?php echo JText::_('COM_TRACKER_STATS_TOP_THANKED'); ?></a></li><?php } ?>
		<?php if ($this->params->get('top_thanker') && count($this->item->top_thanker)) { ?><li><a href="#top-thanker"><?php echo JText::_('COM_TRACKER_STATS_TOP_THANKERS'); ?></a></li><?php } ?>
	</ul>
	<?php if ($this->params->get('top_downloaders') && count($this->item->top_downloaders)) { ?>
		<div id="top-downloaders">
			<div id="container">
				<div id="row">
					<div id="value-center"><b>&nbsp;<?php echo JText::_('COM_TRACKER_STATS_USER'); ?>&nbsp;</b></div>
					<div id="value-center"><b>&nbsp;<?php echo JText::_('COM_TRACKER_STATS_DOWNLOADED'); ?>&nbsp;</b></div>
					<div id="value-center"><b>&nbsp;<?php echo JText::_('COM_TRACKER_STATS_UPLOADED'); ?>&nbsp;</b></div>
					<div id="value-center"><b>&nbsp;<?php echo JText::_('COM_TRACKER_STATS_COUNTRY'); ?>&nbsp;</b></div>
					<div id="value-center"><b>&nbsp;<?php echo JText::_('COM_TRACKER_STATS_GROUP'); ?>&nbsp;</b></div>
				</div>
		<?php foreach ($this->item->top_downloaders as $item) { ?>
				<div id="row">
				<div id="value-left">&nbsp;<a href="index.php?option=com_tracker&view=userpanel&id=<?php echo $item->uid; ?>"><?php echo $item->name; ?></a>&nbsp;</div>
				<div id="value-center">&nbsp;<?php echo TrackerHelper::make_size($item->downloaded); ?>&nbsp;</div>
				<div id="value-center">&nbsp;<?php echo TrackerHelper::make_size($item->uploaded); ?>&nbsp;</div>
		<?php 	if (empty($item->countryName)) {
					$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
					$item->countryName = $item->default_country->name; 
					$item->countryImage = $item->default_country->image;
				}
		?>
				<div id="value-center">&nbsp;<img style="vertical-align:middle;" id="tdcountry<?php echo $item->uid; ?>" alt="<?php echo $item->countryName; ?>" src="<?php echo JURI::base().$item->countryImage; ?>" width="32px" /></div>
				<div id="value-center">&nbsp;<?php echo $item->usergroup; ?>&nbsp;</div>
				</div>
		<?php } ?>
			</div>
		</div>
	<?php } ?>
	<?php if ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) {
		echo '<div id="top-uploaders">';
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
			echo '<div id="value-left">&nbsp;<a href="index.php?option=com_tracker&view=userpanel&id='.$item->uid.'">'.$item->name.'</a>&nbsp;</div>';
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
		echo '</div></div>';
	}
	
	if ($this->params->get('top_sharers') && count($this->item->top_sharers)) {
		echo '<div id="top-sharers">';
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
			echo '<div id="value-left">&nbsp;<a href="index.php?option=com_tracker&view=userpanel&id='.$item->uid.'">'.$item->name.'</a>&nbsp;</div>';
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
		echo '</div></div>';
	}
	if ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) {
		echo '<div id="worst-sharers">';
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
			echo '<div id="value-left">&nbsp;<a href="index.php?option=com_tracker&view=userpanel&id='.$item->uid.'">'.$item->name.'</a>&nbsp;</div>';
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
		echo '</div></div>';
	}

	if ($this->params->get('top_thanked') && count($this->item->top_thanked)) {
		echo '<div id="top-thanked">';
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_THANKED').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->top_thanked as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;<a href="index.php?option=com_tracker&view=userpanel&id='.$item->uid.'">'.$item->name.'</a>&nbsp;</div>';
			echo '<div id="value-center">&nbsp;'.$item->total_thanks.'&nbsp;</div>';
			echo '<div id="value-center">&nbsp;<img style="vertical-align:middle;"  id="tdcountry<'.$item->uid.'" alt="'.$item->countryName.'" src="'.JURI::base().$item->countryImage.'" width="32" /></div>';
			echo '<div id="value-center">&nbsp;'.$item->usergroup.'&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}

	if ($this->params->get('top_thanker') && count($this->item->top_thanker)) {
		echo '<div id="top-thanker">';
		echo '<div id="container">';
		echo '<div id="row">';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_USER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_THANKER').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_COUNTRY').'&nbsp;</b></div>';
		echo '<div id="value-center"><b>&nbsp;'.JText::_('COM_TRACKER_STATS_GROUP').'&nbsp;</b></div>';
		echo '</div>';
		foreach ($this->item->top_thanker as $item) {
			echo'<div id="row">';
			echo '<div id="value-left">&nbsp;<a href="index.php?option=com_tracker&view=userpanel&id='.$item->uid.'">'.$item->name.'</a>&nbsp;</div>';
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
		echo '</div></div>';
	}
	
?>
</div>
<?php 
}

if (($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) || 
	($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) || 
	($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) || 
	($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) ||
	($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents))) {
?>
<br />
<div id="tabs-best">
	<ul>
		<?php if ($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) { ?><li><a href="#most_active"><?php echo JText::_('COM_TRACKER_STATS_TOP_ACTIVE_TORRENTS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) { ?><li><a href="#most_seeded"><?php echo JText::_('COM_TRACKER_STATS_TOP_SEEDED_TORRENTS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) { ?><li><a href="#most_leeched"><?php echo JText::_('COM_TRACKER_STATS_TOP_LEECHED_TORRENTS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) { ?><li><a href="#most_completed"><?php echo JText::_('COM_TRACKER_STATS_TOP_COMPLETED_TORRENTS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents)) { ?><li><a href="#most_thanked"><?php echo JText::_('COM_TRACKER_STATS_TOP_THANKED_TORRENTS'); ?></a></li><?php } ?>
	</ul>
<?php
	if ($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) {
		echo '<div id="most_active">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
	if ($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) {
		echo '<div id="most_seeded">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
	if ($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) {
		echo '<div id="most_leeched">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
	if ($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) {
		echo '<div id="most_completed">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
	
	if ($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents)) {
		echo '<div id="most_thanked">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
?>
</div>
<?php 
}

if (($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) || 
	($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) || 
	($this->params->get('worst_leeched_torrents') && count($this->item->worst_leeched_torrents)) || 
	($this->params->get('worst_completed_torrents') && count($this->item->worst_completed_torrents))) {
?>
<br />
<div id="tabs-worst">
	<ul>
		<?php if ($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) { ?><li><a href="#worst_active"><?php echo JText::_('COM_TRACKER_STATS_WORST_ACTIVE_TORRENTS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) { ?><li><a href="#worst_seeded"><?php echo JText::_('COM_TRACKER_STATS_WORST_SEEDED_TORRENTS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('most_leeched_torrents') && count($this->item->worst_leeched_torrents)) { ?><li><a href="#worst_leeched"><?php echo JText::_('COM_TRACKER_STATS_WORST_LEECHED_TORRENTS'); ?></a></li><?php } ?>
		<?php if ($this->params->get('worst_leeched_torrents') && count($this->item->worst_completed_torrents)) { ?><li><a href="#worst_completed"><?php echo JText::_('COM_TRACKER_STATS_WORST_COMPLETED_TORRENTS'); ?></a></li><?php } ?>
	</ul>
<?php
	if ($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) {
		echo '<div id="worst_active">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
	if ($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) {
		echo '<div id="worst_seeded">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
	if ($this->params->get('worst_leeched_torrents') && count($this->item->worst_leeched_torrents)) {
		echo '<div id="worst_leeched">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
	if ($this->params->get('worst_completed_torrents') && count($this->item->worst_completed_torrents)) {
		echo '<div id="worst_completed">';
		echo '<div id="container">';
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

			$category_params = new JRegistry();
			$category_params->loadString($item->cat_params);

			echo '<div id="value-center">&nbsp;';
			if (is_file($_SERVER['DOCUMENT_ROOT'].DS.JUri::root(true).$category_params->get('image'))) {
				echo '<img style="vertical-align:middle;"  id="tacatimage'.$item->fid.'" alt="'.$item->cat_title.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />';
			} else echo $item->cat_title;
			echo '&nbsp;</div>';
			echo '</div>';
		}
		echo '</div></div>';
	}
?>
</div>
<?php 	
}
