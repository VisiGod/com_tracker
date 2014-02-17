<?php
/**
 * @version			2.5.12-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
//JHTML::_('behavior.modal', 'a.modal', array('handler' => 'ajax'));
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

$doc = JFactory::getDocument();
$doc->addScript("http://code.jquery.com/jquery-1.9.1.js");
$doc->addScript("http://code.jquery.com/ui/1.10.2/jquery-ui.js");
$doc->addStyleSheet("http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css");

?>
<script type="text/javascript">
jQuery.noConflict();
(function($, undefined) {
		$(function() { // onload
			var base_set = ($('base').length != 0);
			var current_location = window.location.href.split('#')[0];
		
			function init_tabs(tabsid) {
				if (base_set) {
					$('#' + tabsid + ' > ul a').each(function() {
						var link_hash = $(this).attr('href');
						if (link_hash[0] === '#') {
							$(this).attr('href', current_location + link_hash);
						}
					});
				}
				$('#' + tabsid).tabs();
			}
			init_tabs('tabs');
		});
})(jQuery);
</script>

<div style="font-size: medium; margin-left:35px; wrap:nowrap;">
	<span style="display:inline-block; vertical-align:middle"><b><?php echo JText::_( 'COM_TRACKER_DETAILS_FOR' ); ?>:</b>&nbsp;<i><?php echo $this->item->name;?></i>&nbsp;&nbsp;&nbsp;</span>
	<?php if ($this->params->get('enable_countries')) {
			if (empty($this->item->country_info->name)) {
				$this->item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
				$this->item->country_info->name = $this->item->default_country->name; 
				$this->item->country_info->image = $this->item->default_country->image;
			}
	?>
	<span style="display:inline-block; vertical-align:middle"><img id="<?php echo $this->item->country_info->name; ?>" alt="<?php echo $this->item->country_info->name; ?>" src="<?php echo JURI::base().$this->item->country_info->image; ?>" width="32" /></span>
	<?php } ?>
</div>

<div id="tabs">
	<ul>
		<li><a href="#user-panel"><?php echo JText::_('COM_TRACKER_USER_DETAILS'); ?></a></li>
		<?php if ($this->item->total_snatch > 0) { ?>
			<li><a href="#user_snatch_list"><?php echo JText::_('COM_TRACKER_SNATCH_LIST'); ?></a></li>
		<?php } ?>
		<?php if ($this->item->total_uploads > 0) { ?>
			<li><a href="#user_uploaded_list"><?php echo JText::_('COM_TRACKER_TORRENTS_UPLOADED'); ?></a></li>
		<?php } ?>
		<?php if ($this->item->total_seeds > 0) { ?>
			<li><a href="#user_seeded_list"><?php echo JText::_('COM_TRACKER_SEEDED_TORRENTS'); ?></a></li>
		<?php } ?>
		<?php if ($this->item->total_hitandran > 0) { ?>
			<li><a href="#user_hit_and_run_list"><?php echo JText::_('COM_TRACKER_LEECHED_AND_RAN'); ?></a></li>
		<?php } ?>
	</ul>

	<div id="user-panel">
		<table class="adminlist" style="width:100%;">
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_JOIN_DATE' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->registerDate.'&nbsp;&nbsp;-&nbsp;&nbsp;'.TrackerHelper::last_activity($this->item->registerDate, 1, 1); ?></td>
			</tr>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_LAST_VISIT' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">
					<?php
					// Stupid bug that I can't solve. Current user already has the last_activity
					if ($this->item->lastvisitDate == '0000-00-00 00:00:00') echo '&nbsp;'.JText::_( 'COM_TRACKER_LAST_SITE_VISIT_NEVER' );
					else if ($this->item->id == $this->session->get('user')->id) echo "&nbsp;".$this->item->lastvisitDate;
					else echo "&nbsp;".$this->item->lastvisitDate.'&nbsp;&nbsp;-&nbsp;&nbsp;'.TrackerHelper::last_activity($this->item->lastvisitDate, 1, 1, 1);
					?>
				</td>
			</tr>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_LAST_TRACKER_ACTIVITY' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->lastseen;?></td>
			</tr>
			<?php if ((TrackerHelper::user_permissions('edit_torrents', $this->session->get('user')->id, 1)) || $this->session->get('user')->id == $this->item->id){ ?>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_LAST_IP' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->announce->ipa;?></td>
			</tr>
			<?php } ?>
			<tr>
				<td class="row1" align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?>:</b></td>
				<td class="row0" align="left">
					<?php
						if ($this->params->get('enable_donations')) $this->item->tracker_info->uploaded = ($this->item->tracker_info->uploaded + ($this->item->user_donations->credited * 1048576));
						echo '&nbsp;'.TrackerHelper::make_size($this->item->tracker_info->uploaded)."&nbsp;(".TrackerHelper::traffic_per_day($this->item->tracker_info->uploaded, $this->item->id)."/".JText::_( 'COM_TRACKER_DAY' ).")";
					?>
				</td>
			</tr>
			<tr>
				<td class="row1" align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?>:</b></td>
				<td class="row0" align="left">&nbsp;<?php echo TrackerHelper::make_size($this->item->tracker_info->downloaded)."&nbsp;(".TrackerHelper::traffic_per_day($this->item->tracker_info->downloaded, $this->item->id)."/".JText::_( 'COM_TRACKER_DAY' ).")";?></td>
			</tr>
			<tr>
				<td class="row1" align="right"><b><?php echo JText::_( 'COM_TRACKER_RATIO' ); ?>:</b></td>
				<td class="row0" align="left">&nbsp;<?php echo TrackerHelper::get_ratio(($this->item->tracker_info->uploaded),$this->item->tracker_info->downloaded);?></td>
			</tr>
			<tr>
				<td class="row1" align="right"><b><?php echo JText::_( 'COM_TRACKER_GROUP' ); ?>:</b></td>
				<td class="row0" align="left">&nbsp;<?php echo ($this->item->group_info->name) ? $this->item->group_info->name : JText::_( 'COM_TRACKER_GROUP_EMPTY' ); ?></td>
			</tr>
			<?php if ((TrackerHelper::user_permissions('edit_torrents', $this->session->get('user')->id, 1)) || $this->session->get('user')->id == $this->item->id){ ?>
				<?php if ($this->item->tracker_info->can_leech) { ?>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_WAIT_TIME' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->tracker_info->wait_time ? JText::_( 'COM_TRACKER_WAIT_TIME_START' ).TrackerHelper::make_wait_time($this->item->tracker_info->wait_time, 1).'&nbsp;'.JText::_( 'COM_TRACKER_WAIT_TIME_END' ) : JText::_( 'COM_TRACKER_NO_WAIT_TIME' )  ;?></td>
			</tr>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_PEER_LIMIT' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->tracker_info->peer_limit ? JText::_( 'COM_TRACKER_PEER_LIMIT_START' ).$this->item->tracker_info->peer_limit.JText::_( 'COM_TRACKER_PEER_LIMIT_END' ) : JText::_( 'COM_TRACKER_NO_PEER_LIMIT' )  ;?></td>
			</tr>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_LIMIT' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->tracker_info->torrent_limit ? JText::_( 'COM_TRACKER_TORRENT_LIMIT_START' ).$this->item->tracker_info->torrent_limit.JText::_( 'COM_TRACKER_TORRENT_LIMIT_END' ) : JText::_( 'COM_TRACKER_NO_TORRENT_LIMIT' )  ;?></td>
			</tr>
			<?php if ($this->params->get('torrent_multiplier') == 1) { ?>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->tracker_info->download_multiplier.'   '.JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER_DESCRIPTION' );?></td>
			</tr>
			<tr>
				<td class="row1" width="1%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' ); ?>:</b></td>
				<td class="row0" width="98%" nowrap align="left">&nbsp;<?php echo $this->item->tracker_info->upload_multiplier.'   '.JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER_DESCRIPTION' );?></td>
			</tr>
			<?php }
			} else { ?>
			<tr>
				<td class="row1" align="right"><b><?php echo '&nbsp;'.JText::_( 'COM_TRACKER_USER_CAN_DOWNLOAD' ); ?>:</b></td>
				<td class="row0" align="left"><?php echo '&nbsp;'.JText::_( 'COM_TRACKER_USER_CANNOT_DOWNLOAD' ); ?></td>
			</tr>
			<?php }
			}
			if ($this->params->get('enable_donations')) {
				if (((TrackerHelper::user_permissions('edit_torrents', $this->session->get('user')->id, 1)) || $this->session->get('user')->id == $this->item->id) && $this->item->user_donations->donated) { ?>
				<tr>
					<td class="row1" align="right"><b><?php echo JText::_( 'COM_TRACKER_DONATED' ); ?>:</b></td>
					<td class="row0" align="left">
						<?php
							echo '&nbsp;$'.number_format($this->item->user_donations->donated, 2, ',', ' ');
							echo ' ('.TrackerHelper::make_size($this->item->user_donations->credited * 1073741824).')';
						?>
					</td>
				</tr>
			<?php } ?>
			<!-- Show the Thanks related info -->
			<?php if ($this->params->get('enable_thankyou') == 1) { ?>
			<tr>
				<td class="row1" align="right"><b><?php echo '&nbsp;'.JText::_( 'COM_TRACKER_USER_THANKS_RECEIVED' ); ?>:</b></td>
				<td class="row0" align="left"><?php echo '&nbsp;'.$this->item->total_thanks.'&nbsp;'.JText::_( 'COM_TRACKER_USER_THANKS' ); ?></td>
			</tr>
			<tr>
				<td class="row1" align="right"><b><?php echo '&nbsp;'.JText::_( 'COM_TRACKER_USER_THANKS_GIVEN' ); ?>:</b></td>
				<td class="row0" align="left"><?php echo '&nbsp;'.$this->item->thanker.'&nbsp;'.JText::_( 'COM_TRACKER_USER_THANKS' ); ?></td>
			</tr>
			<?php } ?>
			
			<?php }
			if ($this->session->get('user')->id == $this->item->id) {?>
			<tr>
				<td class="row1" align="right" nowrap><b><?php echo JText::_( 'COM_TRACKER_TORRENT_PASS_VERSION' ); ?>:</b></td>
				<td class="row0" align="left">
		 			<?php if ($this->item->tracker_info->torrent_pass_version) { ?>
		 				&nbsp;<b><?php echo $this->item->tracker_info->torrent_pass_version; ?></b>&nbsp;-&nbsp;
		 				<a href='<?php echo JRoute::_("index.php?option=com_tracker&task=user.resetpassversion&id=".$this->item->id); ?>'><?php echo JText::_( 'COM_TRACKER_RESET_TORRENT_PASS' );?></a>
		 			<?php 
		 			} else { ?>
		 				&nbsp;<b>No torrent pass version yet</b>&nbsp;-&nbsp;
		 				<a href='<?php echo JRoute::_("index.php?option=com_tracker&task=user.resetpassversion&id=".$this->item->id); ?>'><?php echo JText::_( 'COM_TRACKER_CREATE_TORRENT_PASS' );?></a>
		 			<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</table>
	</div>

	<?php if ($this->item->total_snatch > 0) { ?>
	<div id="user_snatch_list">
		<table class="adminform" style="width:100%;"> <!-- Snatched Torrents -->
			<tr class="row1">
				<th>&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
			</tr>
				<?php
					$k = 0;
					for ($i=0, $n=count( $this->item->user_snatches ); $i < $n; $i++) {
						$this->item_snatched =& $this->item->user_snatches[$i];
				?>
					<tr class="<?php echo "user_snatched".$k; ?>">
						<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$this->item_snatched->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $this->item_snatched->name));?></a></td>
						<td width="5%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->item_snatched->uploaded); ?>&nbsp;</td>
						<td width="5%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->item_snatched->downloaded); ?>&nbsp;</td>
						<td width="5%" nowrap align="right">&nbsp;<?php echo $this->item_snatched->completed; ?>&nbsp;</td>
						<td width="5%" nowrap align="right">&nbsp;<?php echo $this->item_snatched->seeders; ?>&nbsp;</td>
						<td width="5%" nowrap align="right">&nbsp;<?php echo $this->item_snatched->leechers; ?>&nbsp;</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
			?>
	</table>
	</div>
	<?php } ?>

	<?php if ($this->item->total_uploads > 0) { ?>
	<div id="user_uploaded_list"> <!-- UPLOADED TORRENTS -->
		<table class="adminform">
			<tr class="row1">
				<th >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
			</tr>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->item->user_uploads ); $i < $n; $i++) {
			$this->item_uploaded =& $this->item->user_uploads[$i];
			?>
			<tr class="<?php echo "user_snatched$k"; ?>">
				<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$this->item_uploaded->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $this->item_uploaded->name));?></a></td>
				<td width="5%" nowrap align="right"><?php echo $this->item_uploaded->completed; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_uploaded->seeders; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_uploaded->leechers; ?>&nbsp;</td>
			</tr>
			<?php
				$k = 1 - $k;
			}
			?>
		</table>
	</div>
	<?php } ?>

	<?php if ($this->item->total_seeds > 0) { ?>
	<div id="user_seeded_list"> <!-- SEEDED TORRENTS -->
		<table class="adminform">
			<tr class="row1">
				<th >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
			</tr>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->item->user_seeds ); $i < $n; $i++) {
			$this->item_seeding =& $this->item->user_seeds[$i];
			?>
			<tr class="<?php echo "user_seeds$k"; ?>">
				<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$this->item_seeding->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $this->item_seeding->name));?></a></td>
				<td width="5%" nowrap align="right"><?php echo $this->item_seeding->completed; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_seeding->seeders; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_seeding->leechers; ?>&nbsp;</td>
			</tr>
			<?php
			$k = 1 - $k;
			}
			?>
		</table>
	</div>
	<?php } ?>
	<?php if ($this->item->total_hitandran > 0) { ?>
	<div id="user_hit_and_run_list"> <!-- HIT & RUN TORRENTS -->
		<table class="adminform">
			<tr class="row1">
				<th width="75%" >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
				<th width="5%" nowrap >&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
			</tr>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->item->user_hitruns ); $i < $n; $i++) {
			$this->item_hitrun =& $this->item->user_hitruns[$i];
			?>
			<tr class="<?php echo "user_hitrun$k"; ?>">
				<td width="75%"><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$this->item_hitrun->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $this->item_hitrun->name));?></a></td>
				<td width="5%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->item_hitrun->uploaded); ?>&nbsp;</td>
				<td width="5%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->item_hitrun->downloaded); ?>&nbsp;</td>
				<td width="5%" nowrap align="right">&nbsp;<?php echo $this->item_hitrun->completed; ?>&nbsp;</td>
				<td width="5%" nowrap align="right">&nbsp;<?php echo $this->item_hitrun->seeders; ?>&nbsp;</td>
				<td width="5%" nowrap align="right">&nbsp;<?php echo $this->item_hitrun->leechers; ?>&nbsp;</td>
			</tr>
			<?php
			$k = 1 - $k;
			}
			?>
		</table>
	</div>
	<?php } ?>
</div>
<div class="clr"></div>
