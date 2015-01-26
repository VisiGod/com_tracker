<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// First we need to know if we chose to display the user 'username' or the user 'name'
if ($this->params->get('user_in_torrent_details') == 1) $display_user = 'name'; //the name
else $display_user = 'username'; //the username
?>
	<div style="font-size: medium; margin-left:35px; wrap:nowrap;">
		<span style="display:inline-block; vertical-align:middle"><b><?php echo JText::_( 'COM_TRACKER_DETAILS_FOR' ); ?>:</b>&nbsp;<i><?php echo $this->item->$display_user;?></i>&nbsp;&nbsp;&nbsp;</span>
		<?php
			if ($this->params->get('enable_countries')) :
				if (empty($this->item->country_info->name)) :
					$this->item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
					$this->item->country_info = new JObject;
					$this->item->country_info->name = $this->item->default_country->name; 
					$this->item->country_info->image = $this->item->default_country->image;
				endif;
				?>
				<span style="display:inline-block; vertical-align:middle"><img id="<?php echo $this->item->country_info->name; ?>" alt="<?php echo $this->item->country_info->name; ?>" src="<?php echo JURI::base().$this->item->country_info->image; ?>" width="32" /></span>
		<?php endif; ?>
	</div>

	<ul class="nav nav-pills">
		<li class="active"><a href="#user-panel" data-toggle="tab"><?php echo JText::_('COM_TRACKER_USER_DETAILS'); ?></a></li>

		<?php if ($this->item->total_snatch > 0) : ?>
			<li><a href="#user_snatch_list" data-toggle="tab"><?php echo JText::_('COM_TRACKER_SNATCH_LIST'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->item->total_uploads > 0) : ?>
			<li><a href="#user_uploaded_list" data-toggle="tab"><?php echo JText::_('COM_TRACKER_TORRENTS_UPLOADED'); ?></a></li>
		<?php endif; ?>
				
		<?php if ($this->item->total_seeds > 0) : ?>
			<li><a href="#user_seeded_list" data-toggle="tab"><?php echo JText::_('COM_TRACKER_SEEDED_TORRENTS'); ?></a></li>
		<?php endif; ?>
				
		<?php if ($this->item->total_hitandran > 0) : ?>
			<li><a href="#user_hit_and_run_list" data-toggle="tab"><?php echo JText::_('COM_TRACKER_LEECHED_AND_RAN'); ?></a></li>
		<?php endif; ?>
	</ul>


	<div class="tab-content">
		<!-- User Panel -->
		<div class="tab-pane active" id="user-panel">
			<dl class="dl-horizontal">
				<dt><b><?php echo JText::_( 'COM_TRACKER_JOIN_DATE' ); ?>:</b></dt>
				<dd><?php echo $this->item->registerDate.'&nbsp;&nbsp;-&nbsp;&nbsp;'.TrackerHelper::last_activity($this->item->registerDate, 1, 1); ?></dd>

				<dt><b><?php echo JText::_( 'COM_TRACKER_LAST_VISIT' ); ?>:</b></dt>
				<dd>
					<?php
						// Stupid bug that I can't solve. Current user already has the last_activity
						if ($this->item->lastvisitDate == '0000-00-00 00:00:00') echo JText::_( 'COM_TRACKER_LAST_SITE_VISIT_NEVER' );
						else if ($this->item->id == $this->session->get('user')->id) echo $this->item->lastvisitDate;
						else echo $this->item->lastvisitDate.'&nbsp;&nbsp;-&nbsp;&nbsp;'.TrackerHelper::last_activity($this->item->lastvisitDate, 1, 1, 1);
					?>
				</dd>

				<dt><b><?php echo JText::_( 'COM_TRACKER_LAST_TRACKER_ACTIVITY' ); ?>:</b></dt>
				<dd><?php echo $this->item->lastseen;?></dd>

				<?php if ((TrackerHelper::user_permissions('edit_torrents', $this->session->get('user')->id, 1)) || $this->session->get('user')->id == $this->item->id) : ?>
					<dt><b><?php echo JText::_( 'COM_TRACKER_LAST_IP' ); ?>:</b></dt>
					<dd><?php echo $this->item->announce->ipa;?></dd>
				<?php endif; ?>

				<dt><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?>:</b></dt>
				<dd>
					<?php
						if ($this->params->get('enable_donations') && !empty($this->item->user_donations)) $this->item->tracker_info->uploaded = ($this->item->tracker_info->uploaded + ($this->item->user_donations->credited * 1048576));
						echo TrackerHelper::make_size($this->item->tracker_info->uploaded)."&nbsp;(".TrackerHelper::traffic_per_day($this->item->tracker_info->uploaded, $this->item->id)."/".JText::_( 'COM_TRACKER_DAY' ).")";
					?>
				</dd>

				<dt><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?>:</b></dt>
				<dd><?php echo TrackerHelper::make_size($this->item->tracker_info->downloaded)."&nbsp;(".TrackerHelper::traffic_per_day($this->item->tracker_info->downloaded, $this->item->id)."/".JText::_( 'COM_TRACKER_DAY' ).")";?></dd>

				<dt><b><?php echo JText::_( 'COM_TRACKER_RATIO' ); ?>:</b></dt>
				<dd><?php echo TrackerHelper::get_ratio(($this->item->tracker_info->uploaded),$this->item->tracker_info->downloaded);?></dd>

				<dt><b><?php echo JText::_( 'COM_TRACKER_GROUP' ); ?>:</b></dt>
				<dd><?php echo ($this->item->group_info->name) ? $this->item->group_info->name : JText::_( 'COM_TRACKER_GROUP_EMPTY' ); ?></dd>

				<?php if ((TrackerHelper::user_permissions('edit_torrents', $this->session->get('user')->id, 1)) || $this->session->get('user')->id == $this->item->id) : ?>
					<?php if ($this->item->tracker_info->can_leech) : ?>
						<dt><b><?php echo JText::_( 'COM_TRACKER_WAIT_TIME' ); ?>:</b></dt>
						<dd><?php echo $this->item->tracker_info->wait_time ? JText::_( 'COM_TRACKER_WAIT_TIME_START' ).TrackerHelper::make_wait_time($this->item->tracker_info->wait_time, 1).'&nbsp;'.JText::_( 'COM_TRACKER_WAIT_TIME_END' ) : JText::_( 'COM_TRACKER_NO_WAIT_TIME' ) ;?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_PEER_LIMIT' ); ?>:</b></dt>
						<dd><?php echo $this->item->tracker_info->peer_limit ? JText::_( 'COM_TRACKER_PEER_LIMIT_START' ).$this->item->tracker_info->peer_limit.JText::_( 'COM_TRACKER_PEER_LIMIT_END' ) : JText::_( 'COM_TRACKER_NO_PEER_LIMIT' ) ;?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_LIMIT' ); ?>:</b></dt>
						<dd><?php echo $this->item->tracker_info->torrent_limit ? JText::_( 'COM_TRACKER_TORRENT_LIMIT_START' ).$this->item->tracker_info->torrent_limit.JText::_( 'COM_TRACKER_TORRENT_LIMIT_END' ) : JText::_( 'COM_TRACKER_NO_TORRENT_LIMIT' ) ;?></dd>
					<?php endif; ?>
					<?php if ($this->params->get('torrent_multiplier') == 1) : ?>
						<dt><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' ); ?>:</b></dt>
						<dd><?php echo $this->item->tracker_info->download_multiplier.'&nbsp;&nbsp;&nbsp;'.JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER_DESCRIPTION' );?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' ); ?>:</b></dt>
						<dd><?php echo $this->item->tracker_info->upload_multiplier.'&nbsp;&nbsp;&nbsp;'.JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER_DESCRIPTION' );?></dd>
					<?php endif; ?>
				<?php else : ?>
					<dt><b><?php echo JText::_( 'COM_TRACKER_USER_CAN_DOWNLOAD' ); ?>:</b></dt>
					<dd><?php echo JText::_( 'COM_TRACKER_USER_CANNOT_DOWNLOAD' ); ?></dd>
				<?php endif; ?>

				<?php if ($this->params->get('enable_donations') && ((TrackerHelper::user_permissions('edit_torrents', $this->session->get('user')->id, 1)) || $this->session->get('user')->id == $this->item->id)) : ?>
					<dt><b><?php echo JText::_( 'COM_TRACKER_DONATED' ); ?>:</b></dt>
					<dd>
						<?php
							if (!empty($this->item->user_donations->donated)) :
						 		echo number_format($this->item->user_donations->donated, 2, ',', ' ');
								echo ' ('.TrackerHelper::make_size($this->item->user_donations->credited * 1073741824).')';
							else:
								echo JText::_( 'COM_TRACKER_DONATED_NOTHING' );
							endif;
						?>
					</dd>
				<?php endif; ?>

				<?php if ($this->params->get('enable_thankyou') == 1) : ?>
					<dt><b><?php echo JText::_( 'COM_TRACKER_USER_THANKS_RECEIVED' ); ?>:</b></dt>
					<dd><?php echo $this->item->total_thanks.'&nbsp;'.JText::_( 'COM_TRACKER_USER_THANKS' ); ?></dd>

					<dt><b><?php echo JText::_( 'COM_TRACKER_USER_THANKS_GIVEN' ); ?>:</b></dt>
					<dd><?php echo $this->item->thanker.'&nbsp;'.JText::_( 'COM_TRACKER_USER_THANKS' ); ?></dd>
				<?php endif; ?>

				<?php if ($this->session->get('user')->id == $this->item->id) : ?>
					<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_PASS_VERSION' ); ?>:</b></dt>
					<dd>
						<?php if ($this->item->tracker_info->torrent_pass_version) : ?>
							<b><?php echo $this->item->tracker_info->torrent_pass_version; ?></b>&nbsp;-&nbsp;
		 					<a href='<?php echo JRoute::_("index.php?option=com_tracker&task=userpanel.resetpassversion&id=".$this->item->id); ?>'><?php echo JText::_( 'COM_TRACKER_RESET_TORRENT_PASS' );?></a>
		 				<?php else : ?>
				 			<b><?php echo JText::_( 'COM_TRACKER_NO_TORRENT_PASS' ); ?></b>&nbsp;-&nbsp;
			 				<a href='<?php echo JRoute::_("index.php?option=com_tracker&task=userpanel.resetpassversion&id=".$this->item->id); ?>'><?php echo JText::_( 'COM_TRACKER_CREATE_TORRENT_PASS' );?></a>
			 			<?php endif; ?>
					</dd>
				<?php endif; ?>
			</dl>
		</div>

		<!-- Snatch List -->
		<?php if ($this->item->total_snatch > 0) : ?>
			<div class="tab-pane" id="user_snatch_list">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?></th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($this->item->user_snatches as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $item->name));?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->uploaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>


		<!-- Total Uploads -->
		<?php if ($this->item->total_uploads > 0) : ?>
			<div class="tab-pane" id="user_uploaded_list">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?></th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($this->item->user_uploads as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $item->name));?></a></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<!-- Total Seeds -->
		<?php if ($this->item->total_seeds > 0) : ?>
			<div class="tab-pane" id="user_seeded_list">
				<table class="table table-striped">
						<thead>
							<tr>
								<th><?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?></th>
								<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?></th>
								<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?></th>
								<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($this->item->user_seeds as $i => $item) : ?>
								<tr>
									<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $item->name));?></a></td>
									<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
									<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
									<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
			</div>
		<?php endif; ?>

		<!-- Hit and Run -->
		<?php if ($this->item->total_hitandran > 0) : ?>
			<div class="tab-pane" id="user_hit_and_run_list">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS' ); ?></th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($this->item->user_hitruns as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo htmlspecialchars(str_replace("_", " ", $item->name));?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->uploaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

	</div>
	<div class="clr"></div>
