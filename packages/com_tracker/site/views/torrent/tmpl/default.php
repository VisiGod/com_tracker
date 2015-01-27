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
JHtml::_('behavior.modal', 'a.modalpopup');

if ($this->user->get('id') == 0) $this->item->groupID = 0;
?>
	<div class="text-center"><b><?php echo str_replace("_", " ", $this->item->name);?></b></div>

	<div><?php echo $this->item->description;?></div>

	<ul class="nav nav-pills">
		<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS'); ?></a></li>

		<li><a href="#files" data-toggle="tab"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_FILE_LIST'); ?></a></li>

		<?php if (count($this->item->peers) > 0) : ?>
			<li><a href="#peers" data-toggle="tab"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_PEER_LIST'); ?></a></li>
		<?php endif; ?>

		<?php if (count($this->item->snatchers) > 0) : ?>
			<li><a href="#snatchers" data-toggle="tab"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_SNATCHERS'); ?></a></li>
		<?php endif; ?>

		<?php if (count($this->item->hitrunners) > 0) : ?>
			<li><a href="#hit-runners" data-toggle="tab"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_HIT_RUNNER'); ?></a></li>
		<?php endif; ?>
	</ul>

	<div class="tab-content">

		<!-- Torrent Information -->
		<div class="tab-pane active" id="details">
			<div class="row-fluid">
				<div class="span8">
					<dl class="dl-horizontal">
						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' ); ?>:</b></dt>
						<dd><?php echo $this->item->name; ?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_DOWNLOAD' ); ?>:</b></dt>
						<?php if (TrackerHelper::user_permissions('download_torrents', $this->user->id)) : ?>
							<dd>
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=torrent.download&id='.$this->item->fid); ?>"><?php echo $this->item->name;?></a>
								&nbsp;&nbsp;
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=torrent.download&id='.$this->item->fid); ?>">
									<img src="<?php echo JURI::base();?>components/com_tracker/assets/images/download.gif" alt="<?php echo JText::_( 'COM_TRACKER_TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ); ?>" border="0" />
								</a>
							</dd>
						<?php else : ?>
						<?php //TODO: Check for different errors: Ratio, group blocked, user blocked, etc ?>
							<dd>
								<?php
									echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_RATIO_LOW' );
									if ($this->item->exemption_type == 2) echo $this->item->group_minimum_ratio; // shows the group minimum ratio
									else echo $this->item->user_minimum_ratio; // shows the user minimum ratio
								?>
								<br />
								<b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_SOLUTION' );?></b><br />
								<ul>
									<li><a href='<?php echo JRoute::_("index.php?view=upload"); ?>'><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_SOLUTION_UPLOAD' );?></a></li>
									<li><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_SOLUTION_DONATE' );?></li>
								</ul>
							</dd>
						<?php endif; ?>

						<dt><b><?php echo JText::_( 'JCATEGORY' ); ?>:</b></dt>
						<dd>
							<?php
								// For some stupid reason, when there isnt a category, the line is supressed...
								if (empty($this->item->category_title)) $this->item->category_title = '<br />';
								echo $this->item->category_title;
							?>
						</dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_SIZE' ); ?>:</b></dt>
						<dd><?php echo TrackerHelper::make_size($this->item->size)." (".number_format($this->item->size)." ".JText::_( 'COM_TRACKER_BYTES' ).")"; ?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_CREATED_TIME' ); ?>:</b></dt>
						<dd><?php echo $this->item->created_time; ?></dd>

						<?php if ($this->params->get('use_licenses') == 1) : ?>
							<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_LICENSE' ); ?>:</b></dt>
							<dd><?php echo $this->item->license; ?></dd>
						<?php endif; ?>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_UPLOADER' ); ?>:</b></dt>
						<dd>
							<?php
								// First we need to know if we chose to display the user 'username' or the user 'name'
								if ($this->params->get('user_in_torrent_details') == 1) $display_user = $this->item->user_name; //the name
									else $display_user = $this->item->user_username; //the username

								// If we allow to upload as anonymous and it's a guest user who is viewing the torrent, we show the uploader as anonymous
								if (($this->params->get('allow_guest') == 1) && ($this->user->id == $this->params->get('guest_user'))) : 
									echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
								// If the user who is viewing the torrent is the one who uploaded it OR the user who is viewing the torrent can edit it, we show his name/username and the edit option
								elseif ( ($this->user->id == $this->item->uploader) || TrackerHelper::user_permissions('edit_torrents', $this->user->id)):
									echo '<a href="'.JRoute::_("index.php?view=userpanel").'">'.$display_user.'</a>';
									echo '&nbsp;&nbsp;&nbsp;(<a href="'.JRoute::_("index.php?view=edit&id=".$this->item->fid).'"><b>'.JText::_('COM_TRACKER_TORRENT_DETAILS_EDIT_THIS_TORRENT').'</b></a>)';
								// If we don't allow upload as anonymous OR the uploader didn't upload as anonymous and the user viewing the torrent isn't the original uploader
								elseif (($this->params->get('allow_upload_anonymous') == 0) || ($this->item->uploader_anonymous == 0) && ($this->item->uploader <> $this->params->get('guest_user'))) :
									echo '<a href="'.JRoute::_("index.php?view=userpanel&id=".(int)$this->item->uploader).'">'.$display_user.'</a>';
								// If none of the previous situation occur, we show the uploader as anonymous
								else : 
									echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
								endif;
							?>
						</dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES' ); ?>:</b></dt>
						<dd><?php echo $this->item->number_files." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES_FILES' );?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_INFO_HASH' ); ?>:</b></dt>
						<dd><?php echo $this->item->info_hash;?></dd>

						<?php if ($this->params->get('torrent_multiplier') == 1) : ?>
							<dt><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' ); ?>:</b></dt>
							<dd><?php echo $this->item->download_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );?></dd>

							<dt><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' ); ?>:</b></dt>
							<dd><?php echo $this->item->upload_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );?></dd>
						<?php endif; ?>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_PEERS' ); ?>:</b></dt>
						<dd><?php echo $this->item->seeders." ".JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ).", ".$this->item->leechers." ".JText::_( 'COM_TRACKER_TORRENT_LEECHERS' )." = ".($this->item->seeders+$this->item->leechers)." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_PEERS_TOTAL' );?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_SNATCHERS' ); ?>:</b></dt>
						<dd><?php echo count( $this->item->snatchers )." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_SNATCHES' );?></dd>

						<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_HIT_RUNNER' ); ?>:</b></dt>
						<dd><?php echo count( $this->item->hitrunners )." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_HIT_RUNNERS' );?></dd>

						<?php if ($this->params->get('enable_thankyou') == 1) : ?>
							<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_THANKYOUS' ); ?>:</b></dt>
							<dd>
								<?php
									$totalThanks = count($this->item->thankyous);
									if ($totalThanks == 0) :
										echo JText::_( 'COM_TRACKER_TORRENT_NO_THANKS' );
									else :
										// First we need to know if we chose to display the user 'username' or the user 'name'
										if ($this->params->get('user_in_torrent_details') == 1) $display_thanker = 'thanker_name'; //the name
										else $display_thanker = 'thanker_username'; //the username

										for ($i=0; $i < $totalThanks; $i++) {
											echo "<a href='".JRoute::_('index.php?view=userpanel&id='.$this->item->thankyous[$i]->thankerid)."'><b>".$this->item->thankyous[$i]->$display_thanker."</b></a>";
											if ($i < $totalThanks - 1) echo ', ';
										}
									endif;
								?>
							</dd>
						<?php endif; ?>

						<?php if (($this->item->seeders == 0) && $this->params->get('enable_reseedrequest')) : ?>
							<dt><b><?php echo JText::_( 'COM_TRACKER_RESEED_REQUESTS' ); ?>:</b></dt>
							<dd>
								<?php
									$totalReseeds = count($this->item->reseeds);
									if ($totalReseeds == 0) :
										echo JText::_( 'COM_TRACKER_NO_RESEEDS' );
									else :
										// First we need to know if we chose to display the user 'username' or the user 'name'
										if ($this->params->get('user_in_torrent_details') == 1) $display_requester = 'requester_name'; //the name
										else $display_requester = 'requester_username'; //the username

										for ($i=0; $i < $totalReseeds; $i++) {
											echo "<a href='".JRoute::_('index.php?view=userpanel&id='.$this->item->reseeds[$i]->requesterid)."'><b>".$this->item->reseeds[$i]->$display_requester."</b></a>";
											if ($i < $totalReseeds - 1) echo ', ';
										}
									endif;
								?>
								<?php if ((TrackerHelper::checkReseedRequest($this->user->id, $this->item->fid) <> 0) && ($this->user->id <> $this->item->uploader)) : ?>
									<img src="<?php echo JURI::base();?>images/tracker/other/reseed.png" alt="<?php echo JText::_( 'COM_TRACKER_REQUEST_RESEED' ); ?>" border="0" />
									<a href="index.php?option=com_tracker&task=torrent.reseed&id=<?php echo $this->item->fid;?>">
										<?php echo JText::_( 'COM_TRACKER_REQUEST_RESEED' );?>
									</a>&nbsp;
								<?php endif; ?>
							</dd>
						<?php endif; ?>

						<?php if ($this->params->get('torrent_tags')) : ?>
							<dt><b><?php echo JText::_( 'COM_TRACKER_TORRENT_TAGS' ); ?>:</b></dt>
							<dd>
								<?php
									if (empty($this->item->tags)) :
										echo JText::_( 'COM_TRACKER_NO_TORRENT_TAGS' );
									else :
										$Tags = explode(" ", $this->item->tags);
										$Tags = preg_replace('/[^A-Za-z0-9\-\_]/', '', $Tags);
										$totalTags = count($Tags);
										for ($i=0; $i < $totalTags; $i++) {
											echo '<a href="'.JRoute::_('index.php?view=torrents&filter-search='.$Tags[$i]).'">'.$Tags[$i].'</a>';
											if ($i < $totalTags - 1) echo ', ';
										}
									endif;
								?>
							</dd>
						<?php endif; ?>
					</dl>
				</div>

				<?php if ($this->params->get('use_image_file')) : ?>
				<div class="span4">
					<?php 
						$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

						// If we dont have a link in the field
						if(!preg_match($reg_exUrl, $this->item->image_file)) :
							if (file_exists($_SERVER['DOCUMENT_ROOT'].JURI::base(true).'/images/tracker/torrent_image/'.$this->item->image_file) && !empty($this->item->image_file)) :
								$this->item->image_file = JURI::base().'images/tracker/torrent_image/'.$this->item->image_file;
							else :
								$this->item->image_file = JURI::base().$this->params->get('default_image_file');
							endif;
						endif;
					?>
					<a class="modalpopup" href="<?php echo $this->item->image_file; ?>" >
						<img style="width: <?php echo $this->params->get('image_width'); ?>px;" src="<?php echo $this->item->image_file; ?>" />
					</a>
				</div>
				<?php endif; ?>

				<?php if ($this->params->get('forum_post_id') || $this->params->get('torrent_information') || $this->params->get('enable_reporttorrent') || $this->params->get('enable_thankyou')) : ?>
					<div class="span10 center">
						<!--  Forum post ID -->
						<?php if ($this->params->get('forum_post_id') && $this->item->forum_post > 0) : ?>
							<div class="span2">
								<b><a href="<?php echo htmlspecialchars($this->params->get('forum_post_url').$this->item->forum_post);?>" target="_blank"><?php echo JText::_( 'COM_TRACKER_TORRENT_FORUM_POST' );?></a></b>
							</div>
						<?php endif; ?>

						<!-- Torrent information page -->
						<?php if ($this->params->get('torrent_information') && $this->item->info_post > 0) : ?>
							<div class="span2">
								<b><a href="<?php echo htmlspecialchars($this->params->get('info_post_url').$this->item->info_post);?>" target="_blank"><?php echo $this->params->get('info_post_description');?></a></b>
							</div>
						<?php endif; ?>

						<!-- Torrent reporting -->
						<?php if ($this->params->get('enable_reporttorrent')) : ?>
							<div class="span3">
							<?php if ((TrackerHelper::checkReportedTorrent($this->user->id, $this->item->fid) <> 0) && ($this->user->id <> $this->item->uploader)) : ?>
								<img src="<?php echo JURI::base();?>images/tracker/other/report.png" alt="<?php echo JText::_( 'COM_TRACKER_REPORT_TORRENT' ); ?>" border="0" />
								<a class="modalpopup" href="index.php?option=com_tracker&view=report&tmpl=component&id=<?php echo $this->item->fid;?>" title="Report Torrent" rel="{size: {x: 600, y: 430}, closable: true}">
									<b><?php echo JText::_( 'COM_TRACKER_REPORT_TORRENT' );?></b>
								</a>
							<?php elseif ($this->item->uploader == $this->user->id) : ?>
								<b><?php echo JText::_( 'COM_TRACKER_TORRENT_REPORT_OWN_TORRENT' );?></b>
							<?php else : ?>
								<b><?php echo JText::_( 'COM_TRACKER_TORRENT_REPORT_ALREADY_SENT' );?></b>
							<?php endif; ?>
							</div>
						<?php endif; ?>
<!--  name / username -->
						<!-- Torrent Thanks -->
						<?php if ($this->params->get('enable_thankyou') == 1) : ?>
							<?php if ((TrackerHelper::checkThanks($this->user->id, $this->item->fid) <> 0) && ($this->user->id <> $this->item->uploader)) : ?>
								<div class="span3">
									<img src="<?php echo JURI::base();?>images/tracker/other/thank_you.png" alt="<?php echo JText::_( 'COM_TRACKER_TORRENT_SAY_THANKYOU' ); ?>" border="0" />
									<a href="index.php?option=com_tracker&task=torrent.thanks&id=<?php echo $this->item->fid;?>">
										<b><?php echo JText::_( 'COM_TRACKER_TORRENT_SAY_THANKYOU' );?></b>
									</a>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- File List -->
		<div class="tab-pane" id="files">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' ); ?></th>
						<?php if ($this->params->get('enable_filetypes') == 1) : ?><th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_FILETYPE' ); ?></th><?php endif; ?>
						<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SIZE' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->item->torrent_files as $i => $item) : ?>
						<tr>
							<td><?php echo $item->filename; ?></td>
							<?php if ($this->params->get('enable_filetypes') == 1) : ?><td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::getFileImage($item->filename); ?></td><?php endif; ?>
							<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<?php if (count($this->item->peers) > 0) : ?>
			<!-- Peer List -->
			<div class="tab-pane" id="peers">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_PROGRESS' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?></th>
							<?php if ($this->params->get('peer_speed') == 1) : ?><th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_SPEED' ); ?></th><?php endif; ?>
							<?php if ($this->params->get('peer_speed') == 1) : ?><th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_UPLOAD_SPEED' ); ?></th><?php endif; ?>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_RATIO' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUM_TIMES' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->peers as $i => $peer) : ?>
							<?php if ($peer->num_times > 1) : ?> <!--  downloaded // left0 // uploaded -->
								<?php foreach ($this->item->peers[$i]->list as $i => $list) : ?>
								<tr>
									<?php 
										// First we need to know if we chose to display the user 'username' or the user 'name'
										if ($this->params->get('user_in_torrent_details') == 1) $display_peer = $peer->name; //the name
										else $display_peer = $peer->username; //the username
									?>
									<td><a href="<?php echo JRoute::_('index.php?view=userpanel&id='.$peer->id);?>"><?php echo $display_peer; ?></a></td>
									<td style="white-space:nowrap; text-align:center;">
										<?php
											if (empty($peer->countryName)) :
												$peer->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
												$peer->countryName = $peer->default_country->name;
												$peer->countryImage = $peer->default_country->image;
											endif;
										?>
										<img id="peercountry<?php echo $i;?>" alt="<?php echo $peer->countryName; ?>" src="<?php echo JURI::base().$peer->countryImage; ?>" width="32" />
									</td>
									<td style="white-space:nowrap; text-align:center;">
									<?php  
											$list->user_progress = number_format(100-(($list->left0*100)/$item->size), 0, ',', ' ');
											if ($list->user_progress < 33) $progress_class = "progress-danger";
											else if ($list->user_progress < 66) $progress_class = "progress-warning";
											else $progress_class = "progress-success";
									?>
										<div class="progress progress-striped active <?php echo $progress_class;?>">
											<div class="bar" style="width: <?php echo $list->user_progress;?>%;color: #000;"><?php echo $list->user_progress;?>%</div>
										</div>
									</td>
									<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($list->downloaded);?></td>
									
									<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($list->uploaded); ?></td>
									<?php if ($this->params->get('peer_speed') == 1) : ?><td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($list->down_rate).'/s'; ?></td><?php endif; ?>
									<?php if ($this->params->get('peer_speed') == 1) : ?><td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($list->up_rate).'/s'; ?></td><?php endif; ?>
									<td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_ratio($list->downloaded,$list->uploaded); ?></td>
									<td style="white-space:nowrap; text-align:center;"><?php echo $peer->num_times; ?></td>
								</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<?php 
										// First we need to know if we chose to display the user 'username' or the user 'name'
										if ($this->params->get('user_in_torrent_details') == 1) $display_peer = $peer->name; //the name
										else $display_peer = $peer->username; //the username
									?>
									<td><a href="<?php echo JRoute::_('index.php?view=userpanel&id='.$peer->id);?>"><?php echo $display_peer; ?></a></td>
									<td style="white-space:nowrap; text-align:center;">
										<?php
											if (empty($peer->countryName)) :
												$peer->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
												$peer->countryName = $peer->default_country->name;
												$peer->countryImage = $peer->default_country->image;
											endif;
										?>
										<img id="peercountry<?php echo $i;?>" alt="<?php echo $peer->countryName; ?>" src="<?php echo JURI::base().$peer->countryImage; ?>" width="32" />
									</td>
									<td style="white-space:nowrap; text-align:center;">
										<?php
											$peer->user_progress = number_format(100-(($peer->left*100)/$this->item->size), 0, ',', ' ');
											if ($peer->user_progress < 33) $progress_class = "progress-danger";
											else if ($peer->user_progress < 66) $progress_class = "progress-warning";
											else $progress_class = "progress-success";
										?>
										<div class="progress progress-striped active <?php echo $progress_class;?>">
											<div class="bar" style="width: <?php echo $peer->user_progress;?>%;color: #000;"><?php echo $peer->user_progress;?>%</div>
										</div>
									</td>
									<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($peer->downloaded); ?></td>
									<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($peer->uploaded); ?></td>
									<?php if ($this->params->get('peer_speed') == 1) : ?><td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($peer->down_rate).'/s'; ?></td><?php endif; ?>
									<?php if ($this->params->get('peer_speed') == 1) : ?><td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($peer->up_rate).'/s'; ?></td><?php endif; ?>
									<td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_ratio($peer->downloaded,$peer->uploaded); ?></td>
									<td style="white-space:nowrap; text-align:center;"><?php echo $peer->num_times; ?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if (count($this->item->snatchers) > 0) : ?>
			<!-- Snatchers List -->
			<div class="tab-pane" id="snatchers">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_RATIO' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->snatchers as $i => $snatcher) : ?>
							<tr>
								<?php 
									// First we need to know if we chose to display the user 'username' or the user 'name'
									if ($this->params->get('user_in_torrent_details') == 1) $display_snatcher = $snatcher->name; //the name
									else $display_snatcher = $snatcher->username; //the username
								?>
								<td><a href="<?php echo JRoute::_('index.php?view=userpanel&id='.$snatcher->id);?>"><?php echo $display_snatcher; ?></a></td>
								<td style="white-space:nowrap; text-align:center;">
									<?php
										if (empty($snatcher->countryName)) :
											$snatcher->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$snatcher->countryName = $snatcher->default_country->name;
											$snatcher->countryImage = $snatcher->default_country->image;
										endif;
									?>
									<img id="snatchercountry<?php echo $i;?>" alt="<?php echo $snatcher->countryName; ?>" src="<?php echo JURI::base().$snatcher->countryImage; ?>" width="32" />
								</td>
								<td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($snatcher->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($snatcher->uploaded); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_ratio($snatcher->downloaded,$snatcher->uploaded); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if (count($this->item->hitrunners) > 0) : ?>
			<!-- Hit and Runners List -->
			<div class="tab-pane" id="hit-runners">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_UPLOADED' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->hitrunners as $i => $hitrunner) : ?>
							<tr>
								<?php 
									// First we need to know if we chose to display the user 'username' or the user 'name'
									if ($this->params->get('user_in_torrent_details') == 1) $display_hitrunner = $hitrunner->name; //the name
									else $display_hitrunner = $hitrunner->username; //the username
								?>
								<td><a href="<?php echo JRoute::_('index.php?view=userpanel&id='.$hitrunner->id);?>"><?php echo $display_hitrunner; ?></a></td>
								<td style="white-space:nowrap; text-align:center;">
									<?php
										if (empty($hitrunner->countryName)) :
											$hitrunner->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$hitrunner->countryName = $hitrunner->default_country->name;
											$hitrunner->countryImage = $hitrunner->default_country->image;
										endif;
									?>
									<img id="hitrunnercountry<?php echo $i;?>" alt="<?php echo $hitrunner->countryName; ?>" src="<?php echo JURI::base().$hitrunner->countryImage; ?>" width="32" />
								</td>
								<td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($hitrunner->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo TrackerHelper::make_size($hitrunner->uploaded); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>

	<?php
	// Enable the commenting system if we have it enabled
	if ($this->params->get('enable_comments') && TrackerHelper::user_permissions('view_comments', $this->user->get('id'), 1)) :
		echo '<div>';
		TrackerHelper::comments($this->item->fid, $this->item->name);
		echo '</div>';
	endif;
?>