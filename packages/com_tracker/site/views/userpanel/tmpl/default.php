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
JHTML::_('behavior.modal', 'a.modal', array('handler' => 'ajax'));
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';

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
?>

<div style="font-size: medium; margin-left:35px; wrap:nowrap;">
	<span style="display:inline-block; vertical-align:middle"><b><?php echo JText::_( 'COM_TRACKER_DETAILS_FOR' ); ?>:</b>&nbsp;<i><?php echo $this->item->name;?></i>&nbsp;&nbsp;&nbsp;</span>
	<?php if ($this->params->get('enable_countries')) {?>
	<span style="display:inline-block; vertical-align:middle"><img id="<?php echo $this->item->country_info->name; ?>" alt="<?php echo $this->item->country_info->name; ?>" src="<?php echo JURI::base().'media/com_tracker/flags/'.$this->item->country_info->image; ?>" width="32" /></span>
	<?php } ?>
</div>
<?php echo JHtml::_('tabs.start', 'user_details_start', $tab_options); ?>
<div>
	<?php echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_USER_DETAILS'), 'user_details'); ?>
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
			<td class="row0" align="left">&nbsp;<?php echo TrackerHelper::make_size($this->item->tracker_info->uploaded + ($this->item->user_donations->credited * 1073741824))."&nbsp;(".TrackerHelper::traffic_per_day($this->item->tracker_info->uploaded, $this->item->id)."/".JText::_( 'COM_TRACKER_DAY' ).")";?></td>
		</tr>
		<tr>
			<td class="row1" align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?>:</b></td>
			<td class="row0" align="left">&nbsp;<?php echo TrackerHelper::make_size($this->item->tracker_info->downloaded)."&nbsp;(".TrackerHelper::traffic_per_day($this->item->tracker_info->downloaded, $this->item->id)."/".JText::_( 'COM_TRACKER_DAY' ).")";?></td>
		</tr>
		<tr>
			<td class="row1" align="right"><b><?php echo JText::_( 'COM_TRACKER_RATIO' ); ?>:</b></td>
			<td class="row0" align="left">&nbsp;<?php echo TrackerHelper::get_ratio((($this->item->user_donations->credited * 1073741824) + $this->item->tracker_info->uploaded),$this->item->tracker_info->downloaded);?></td>
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
			<?php }
			if ($this->session->get('user')->id == $this->item->id) {?>
			<tr>
				<td class="row1" align="right" nowrap><b><?php echo JText::_( 'COM_TRACKER_TORRENT_PASS_VERSION' ); ?>:</b></td>
				<td class="row0" align="left">
		 			<?php if ($this->item->tracker_info->torrent_pass_version) {
		 				echo '&nbsp;<b>'.$this->item->tracker_info->torrent_pass_version.'</b>&nbsp;-&nbsp;';
		 				echo '<a href="index.php?option=com_tracker&amp;task=user.resetpassversion&amp;id='.$this->item->id."\" onMouseOver=\"return overlib('Only use if you suspect that someone else knows your passkey', CAPTION, 'When to click the \'Reset Torrent Pass Version\'', BELOW, RIGHT, CAPCOLOR, '#FFFFFF', BGCOLOR, '#707070', TEXTCOLOR, '#FFFFFF', FGCOLOR, '#c2c2c2');\" onmouseout=\"return nd();\" >Reset Torrent Pass Version</a>";
		 			} else {
		 				echo '&nbsp;<b>No torrent pass version yet</b>&nbsp;-&nbsp;';
		 				echo '<a href="index.php?option=com_tracker&amp;task=user.resetpassversion&amp;id='.$this->item->id."\" onMouseOver=\"return overlib('If this is the first time you use the tracker you need to create your passkey', CAPTION, 'When to click the \'Create Torrent Pass Version\'', BELOW, RIGHT, CAPCOLOR, '#FFFFFF', BGCOLOR, '#707070', TEXTCOLOR, '#FFFFFF', FGCOLOR, '#c2c2c2');\" onmouseout=\"return nd();\" >Create Torrent Pass Version</a>";
		 			} ?>
				</td>
			</tr>
			<?php } ?>
		
		</table>
<?php
	if ($this->item->total_snatch) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_SNATCH_LIST'), 'user_snatch_list');
?>
	<table class="adminform" style="width:100%;"> <!-- Snatched Torrents -->
		<tr class="row1">
			<th class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
			<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?>&nbsp;</th>
			<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?>&nbsp;</th>
			<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
			<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
			<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
		</tr>
			<?php
				$k = 0;
				for ($i=0, $n=count( $this->item->user_snatches ); $i < $n; $i++) {
					$this->item_snatched =& $this->item->user_snatches[$i];
					?>
					<tr class="<?php echo "user_snatched".$k; ?>">
						<td> <?php echo '<a href="index.php?option=com_tracker&amp;view=torrent&amp;id='.$this->item_snatched->fid.'" >'.htmlspecialchars(str_replace("_", " ", $this->item_snatched->name)).'</a>'; ?> </td>
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
<?php
	}
	if ($this->item->total_uploads) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_TORRENTS_UPLOADED'), 'user_uploaded_list');
	?>
		<table class="adminform"> <!-- UPLOADED TORRENTS -->
			<tr class="row1">
				<th class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
			</tr>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->item->user_uploads ); $i < $n; $i++) {
			$this->item_uploaded =& $this->item->user_uploads[$i];
			?>
			<tr class="<?php echo "user_snatched$k"; ?>">
				<td> <?php echo '<a href="index.php?option=com_tracker&amp;view=torrent&amp;id='.$this->item_uploaded->fid.'" >'.htmlspecialchars(str_replace("_", " ", $this->item_uploaded->name)).'</a>'; ?> </td>
				<td width="5%" nowrap align="right"><?php echo $this->item_uploaded->completed; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_uploaded->seeders; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_uploaded->leechers; ?>&nbsp;</td>
			</tr>
			<?php
				$k = 1 - $k;
			}
			?>
		</table>
	<?php
	}
	if ($this->item->total_seeds) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_SEEDED_TORRENTS'), 'user_seeded_list');
	?>
		<table class="adminform"> <!-- SEEDED TORRENTS -->
			<tr class="row1">
				<th class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
			</tr>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->item->user_seeds ); $i < $n; $i++) {
			$this->item_seeding =& $this->item->user_seeds[$i];
			?>
			<tr class="<?php echo "user_seeds$k"; ?>">
				<td> <?php echo '<a href="index.php?option=com_tracker&amp;view=torrent&amp;id='.$this->item_seeding->fid.'" >'.htmlspecialchars(str_replace("_", " ", $this->item_seeding->name)).'</a>'; ?> </td>
				<td width="5%" nowrap align="right"><?php echo $this->item_seeding->completed; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_seeding->seeders; ?>&nbsp;</td>
				<td width="5%" nowrap align="right"><?php echo $this->item_seeding->leechers; ?>&nbsp;</td>
			</tr>
			<?php
			$k = 1 - $k;
			}
			?>
		</table>
	<?php
	}
	if ($this->item->total_hitandran) {
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_LEECHED_AND_RAN'), 'user_hit_and_run_list');
	?>
		<table class="adminform"> <!-- HIT & RUN TORRENTS -->
			<tr class="row1">
				<th width="75%" class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?>&nbsp;</th>
				<th width="5%" nowrap class="title">&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?>&nbsp;</th>
			</tr>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->item->user_hitruns ); $i < $n; $i++) {
			$this->item_hitrun =& $this->item->user_hitruns[$i];
			?>
			<tr class="<?php echo "user_hitrun$k"; ?>">
				<td width="75%"> <?php echo '<a href="index.php?option=com_tracker&amp;view=torrent&amp;id='.$this->item_hitrun->fid.'" >'.htmlspecialchars(str_replace("_", " ", $this->item_hitrun->name)).'</a>'; ?> </td>
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
	<?php
	}
	echo JHtml::_('tabs.end');
?>
</div>
<div class="clr"></div>