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
JHTML::_('behavior.modal');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';
$params =& JComponentHelper::getParams( 'com_tracker' );

$doc =& JFactory::getDocument();
$doc->addScript("http://code.jquery.com/jquery-1.9.1.js");
$doc->addScript("http://code.jquery.com/ui/1.10.2/jquery-ui.js");
$doc->addStyleSheet("http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css");

$user	= JFactory::getUser();
if ($user->get('id') == 0) $this->item->groupID = 0;
?>
<script type="text/javascript">
jQuery.noConflict();
(function($) {
	$(function() {
		$( "#tabs" ).tabs();
	});
})(jQuery);

jQuery(document).ready(function(){
    jQuery(".row1").fadeIn(2000);

});

jQuery(document).ready(function(){
    jQuery("#title").animate({width:'100%'},1000);

});
</script>


<div id="title" style="width:0%; font-family: 'Lobster Two','Helvetica',arial,serif; text-align:center; text-shadow: 1px 1px 0 #FFFFFF; font-size:30px;" >
	<p class="title info"><?php echo str_replace("_", " ", $this->item->name);?></p>
</div>

<div class="row1" style="display:none; align:left; font-size: medium;">
	<?php echo $this->item->description;?>
</div>

<div id="tabs">
	<ul>
		<li><a href="#torrent-details"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS'); ?></a></li>
		<li><a href="#file-list"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_FILE_LIST'); ?></a></li>
		<?php if (count($this->item->peers) > 0) { ?><li><a href="#peer-list"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_PEER_LIST'); ?></a></li><?php } ?>
		<?php if (count($this->item->snatchers) > 0) { ?><li><a href="#snatchers"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_SNATCHERS'); ?></a></li><?php } ?>
		<?php if (count($this->item->hitrunners) > 0) { ?><li><a href="#hit-runners"><?php echo JText::_('COM_TRACKER_TORRENT_DETAILS_HIT_RUNNER'); ?></a></li><?php } ?>
	</ul>

	<div id="torrent-details"> <!-- Torrent Information -->
	<table style="cellpadding: 0; cellspacing: 2; border: 0; width: 99%; align: center;">
		<tr>
			<td colspan="4">
				<table style="width: 100%;">
					<tr>
						<td valign="middle" style="height: 16px; width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' );?></b>&nbsp;</td>
						<td style="width: 99%;" colspan="2" nowrap><?php echo $this->item->name;?></td>
					</tr>
					<?php if (TrackerHelper::user_permissions('download_torrents', $user->id)) { ?>
					<tr class="success">
						<td valign="middle" style="height: 16px; width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_DOWNLOAD' );?></b>&nbsp;</td>
						<td style="width: 99%;" colspan="2" nowrap>
							<a href="index.php?option=com_tracker&amp;task=torrent.download&amp;id=<?php echo $this->item->fid;?>"><?php echo $this->item->name;?></a>
							&nbsp;&nbsp;
							<a href="<?php echo JRoute::_("index.php?option=com_tracker&task=torrent.download&id=".$this->item->fid); ?>">
								<img src="<?php echo JURI::base();?>components/com_tracker/assets/images/download.gif" alt="<?php echo JText::_( 'COM_TRACKER_TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ); ?>" border="0" />
							</a>
						</td>
					</tr>
					<tr>
						<td style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_INFO_HASH' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo bin2hex($this->item->info_hash); ?>
						</td>
					</tr>
					<?php } else { ?>
					<tr class="error">
						<td valign="middle" style="height: 16px; width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_DOWNLOAD' );?></b>&nbsp;</td>
						<td style="width: 99%;" colspan="2" nowrap>
							<?php
								echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_RATIO_LOW' )&nbsp;;
								if ($this->item->exemption_type == 2) echo $this->item->group_minimum_ratio; // shows the group minimum ratio
								else echo $this->item->user_minimum_ratio; // shows the user minimum ratio
							?>
							
							<br />
							<b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_SOLUTION' );?></b><br />
								<ul>
									<li><a href="index.php?option=com_tracker&view=upload"><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_SOLUTION_UPLOAD' );?></a></li>
									<li><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NO_DOWNLOAD_SOLUTION_DONATE' );?></li>
								</ul>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td style="width: 1%;" align="left"><b><?php echo JText::_( 'JCATEGORY' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->category_title;?>
						</td>
					</tr>
					<tr>
						<td style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_SIZE' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo TrackerHelper::make_size($this->item->size)."	( ".number_format($this->item->size)." ".JText::_( 'COM_TRACKER_BYTES' )." )";?>
						</td>
					</tr>
					<tr>
						<td style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_CREATED_TIME' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->created_time;?>
						</td>
					</tr>
					<?php if ($params->get('use_licenses') == 1) {?>
					<tr>
						<td style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_LICENSE' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->license;?>
						</td>
					</tr>
					<?php }?>
					<tr>
						<td nowrap style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_UPLOADER' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php
						if ( ($params->get('allow_guest') == 1) && ($user->id == $params->get('guest_user')) ) {
							echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
						} elseif ($user->id == $this->item->uploader) {
							echo "<a href='index.php?option=com_tracker&amp;view=userpanel'>[".$this->item->uname."]</a>";
						} elseif (($params->get('allow_upload_anonymous') == 0) || ($this->item->uploader_anonymous == 0) && ($this->item->uploader <> $params->get('guest_user'))) {
							echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->item->uploader."'>[".$this->item->uname."]</a>";									
						} else echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
						
						// Show torrent edit
						if ((TrackerHelper::user_permissions('edit_torrents', $user->id) || ($user->id == $this->item->uploader)) ) echo "&nbsp;&nbsp;&nbsp;(<a href='index.php?option=com_tracker&amp;view=edit&amp;id=".$this->item->fid."'><b>".JText::_( 'COM_TRACKER_TORRENT_DETAILS_EDIT_THIS_TORRENT' )."</b></a>)";
							?>
						</td>
					</tr>
					<tr>
						<td nowrap style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->number_files." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES_FILES' );?>
						</td>
					</tr>
					<?php if ($params->get('torrent_multiplier') == 1) {?>
					<tr>
						<td nowrap style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->download_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );?>
						</td>
					</tr>
					<tr>
						<td nowrap style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->upload_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td nowrap style="width: 1%;" align="left" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_PEERS' );?>
						</b>&nbsp;</td>
						<td width="98%" valign="top" colspan="2"><?php echo $this->item->seeders." ".JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ).", ".$this->item->leechers." ".JText::_( 'COM_TRACKER_TORRENT_LEECHERS' )." = ".($this->item->seeders+$this->item->leechers)." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_PEERS_TOTAL' );?>
						</td>
					</tr>
					<tr>
						<td nowrap style="width: 1%;" align="left" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_SNATCHERS' );?>
						</b>&nbsp;</td>
						<td width="98%" valign="top" colspan="2"><?php echo count( $this->item->snatchers )." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_SNATCHES' );?>
						</td>
					</tr>
					<tr>
						<td nowrap style="width: 1%;" align="left" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_HIT_RUNNER' );?></b>&nbsp;</td>
						<td width="98%" valign="top" colspan="2"><?php echo count( $this->item->hitrunners )." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_HIT_RUNNERS' );?>
						</td>
					</tr>
					<!-- Torrent Thanks -->
					<?php if ($params->get('enable_thankyou') == 1) { ?>
					<tr>
						<td nowrap valign="top" style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_THANKYOUS' );?></b>&nbsp;</td>
						<td width="98%" valign="top" style="wrap">
						<?php
							$totalThanks = count($this->item->thankyous);
							if ($totalThanks == 0) echo JText::_( 'COM_TRACKER_TORRENT_NO_THANKS' );
							else {
								for ($i=0; $i < $totalThanks; $i++) {
									echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->item->thankyous[$i]->thankerid."'>[".$this->item->thankyous[$i]->thanker."]</a>";
									if ($i < $totalThanks - 1) echo ', ';
								}
							}
							
						?>
						</td>
					</tr>
					<?php } ?>
					
					<!-- Reseed request -->
					<?php if (($this->item->seeders == 0) && $params->get('enable_reseedrequest')) { ?>
					<tr>
						<td nowrap style="width: 1%;" align="left"><b><?php echo JText::_( 'COM_TRACKER_RESEED_REQUESTS' );?></b>&nbsp;</td>
						<td width="98%" valign="top" style="wrap">
						<?php
							$totalReseeds = count($this->item->reseeds);
							if ($totalReseeds == 0) echo JText::_( 'COM_TRACKER_NO_RESEEDS' );
							else {
								for ($i=0; $i < $totalReseeds; $i++) {
									echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->item->reseeds[$i]->requester."'>[".$this->item->reseeds[$i]->requester."]</a>";
									if ($i < $totalReseeds - 1) echo ', ';
								}
							}
							
						?>
						</td>
						<?php if ((TrackerHelper::checkReseedRequest($user->id, $this->item->fid) <> 0) && ($user->id <> $this->item->uploader)) { ?>
						<td nowrap style="width: 1%;" align="left">&nbsp;
							<img src="<?php echo JURI::base();?>images/tracker/other/reseed.png" alt="<?php echo JText::_( 'COM_TRACKER_REQUEST_RESEED' ); ?>" border="0" />
							<a href='index.php?option=com_tracker&amp;task=torrent.reseed&amp;id=<?php echo $this->item->fid;?>'>
								<?php echo JText::_( 'COM_TRACKER_REQUEST_RESEED' );?>
							</a>&nbsp;
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</table>
			</td>
			<?php if ($params->get('use_image_file') && !empty($this->item->image_file)) { ?>
			<?php 
				$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
				
				// If we have a link in the field
				if(!preg_match($reg_exUrl, $this->item->image_file)) {
					$this->item->image_file = JURI::base().'images/tracker/torrent_image/'.$this->item->image_file;
				}
			?>
			<td valign="middle">
				<a href="<?php echo $this->item->image_file; ?>" class="modal" >
					<img style="width: <?php echo $params->get('image_width'); ?>px; position: relative;" src="<?php echo $this->item->image_file; ?>" />
				</a>
			</td>
			<?php } ?>
		</tr>
	</table>
		<?php if ($params->get('forum_post_id') || $params->get('torrent_information') || $params->get('enable_reporttorrent') || $params->get('enable_thankyou')) { ?>
		<div style="text-align: center; width: 100%;">
			<!--  Forum post ID -->
			<?php if ($params->get('forum_post_id') && $this->item->forum_post > 0) { ?>
				<div style="display: inline-block; width: 25%;" class="row1">
					<b><a href="<?php echo htmlspecialchars($params->get('forum_post_url').$this->item->forum_post);?>" target="_blank"><?php echo JText::_( 'COM_TRACKER_TORRENT_FORUM_POST' );?></a></b>
				</div>
			<?php } ?>
			<!-- Torrent information page -->
			<?php if ($params->get('torrent_information') && $this->item->info_post > 0) { ?>
				<div style="display: inline-block; width: 25%;" class="row1">
					<b><a href="<?php echo htmlspecialchars($params->get('info_post_url').$this->item->info_post);?>" target="_blank"><?php echo $params->get('info_post_description');?></a></b>
				</div>
			<?php } ?>
			<!-- Torrent reporting -->
			<?php if ($params->get('enable_reporttorrent')) { ?>
				<?php if ((TrackerHelper::checkReportedTorrent($user->id, $this->item->fid) <> 0) && ($user->id <> $this->item->uploader)) { ?>
					<div style="display: inline-block; width: 25%;" class="row1">
						<img src="<?php echo JURI::base();?>images/tracker/other/report.png" alt="<?php echo JText::_( 'COM_TRACKER_REPORT_TORRENT' ); ?>" border="0" />
						<b>
							<a href="index.php?option=com_tracker&view=report&id=<?php echo $this->item->fid;?>&tmpl=component" class="modal" title="Report Torrent" rel="{handler: 'iframe', size: {x: 800, y: 600}}">
								<?php echo JText::_( 'COM_TRACKER_REPORT_TORRENT' );?>
							</a>
						</b>
					</div>
				<?php } else if ($this->item->uploader == $user->id) { ?>
					<div style="display: inline-block; width: 25%;" class="row1">
						<b><?php echo JText::_( 'COM_TRACKER_TORRENT_REPORT_OWN_TORRENT' );?></b>
					</div>
				<?php } else { ?>
					<div style="display: inline-block; width: 25%;" class="row1">
						<b><?php echo JText::_( 'COM_TRACKER_TORRENT_REPORT_ALREADY_SENT' );?></b>
					</div>
				<?php } ?>
			<?php } ?>
			<!-- Torrent Thanks -->
			<?php if ($params->get('enable_thankyou') == 1) { ?>
				<?php if ((TrackerHelper::checkThanks($user->id, $this->item->fid) <> 0) && ($user->id <> $this->item->uploader)) { ?>
					<div style="display: inline-block; width: 25%;" class="row1">
						<img src="<?php echo JURI::base();?>images/tracker/other/thank_you.png" alt="<?php echo JText::_( 'COM_TRACKER_TORRENT_SAY_THANKYOU' ); ?>" border="0" />
						<a href='index.php?option=com_tracker&amp;task=torrent.thanks&amp;id=<?php echo $this->item->fid;?>'>
							<?php echo JText::_( 'COM_TRACKER_TORRENT_SAY_THANKYOU' );?>
						</a>&nbsp;
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<div style="clear: both;height: 1px;">&nbsp;</div>
		<?php } ?>
	</div>
	
	<div id="file-list">
	<table
		style="cellpadding: 0; cellspacing: 2; border: 0; width: 99%; align: center;">
		<!-- File List -->
		<tr>
			<td style="width: 70%;" valign="top">
				<table
					style="cellpadding: 0; cellspacing: 0; border: 0; width: 100%;">
					<tr class="row1">
						<td>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' );?>&nbsp;</b></td>
						<?php if ($params->get('enable_filetypes') == 1) { ?>
						<td nowrap align="center">&nbsp;<b><?php echo JText::_( 'COM_TRACKER_TORRENT_FILETYPE' );?>&nbsp;</b></td>
						<?php } ?>
						<td align="right">&nbsp;<b><?php echo JText::_( 'COM_TRACKER_TORRENT_SIZE' );?>&nbsp;</b></td>
					</tr>
					<tr>
					<?php if ($params->get('enable_filetypes') == 1) { ?>
						<td colspan="3"><hr /></td>
					<?php } else { ?>
						<td colspan="2"><hr /></td>
					<?php } ?>
					</tr>
					<?php
					$k = 0;
					for ($i=0, $n=count( $this->item->torrent_files ); $i < $n; $i++) {
						$this->torrent_file =& $this->item->torrent_files[$i];
						?>
					<tr class="<?php echo "torrent_file".$k; ?>">
						<td width="90%"><?php echo htmlspecialchars($this->torrent_file->filename); ?></td>
						<?php if ($params->get('enable_filetypes') == 1) { ?>
						<td nowrap align="center">&nbsp;<?php TrackerHelper::getFileImage($this->torrent_file->filename); ?>&nbsp;</td>
						<?php } ?>
						<td nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->torrent_file->size); ?>&nbsp;</td>
					</tr>
					<?php
						$k = 1 - $k;
					}
				?>
				</table>
			</td>
		</tr>
	</table>
	</div>

	<?php if (count($this->item->peers) > 0) { // Peer List ?>
	<div id="peer-list">
	<table style="cellpadding: 0; cellspacing: 0; border: 0; width: 99%; align: center;">
		<tr class="row1">
			<td nowrap>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_USER' );?></b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_COUNTRY' );?></b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_PROGRESS' );?></b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' );?></b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' );?></b></td>
			<?php if ($params->get('peer_speed') == 1) { ?>
				<td width="10%" align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_SPEED' );?></b></td>
				<td width="10%" align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_SPEED' );?></b></td>
			<?php } ?>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_RATIO' );?></b></td>
		</tr>
		<?php
			$k = 0;
			for ($i=0, $n=count( $this->item->peers ); $i < $n; $i++) {
			$this->peer	=& $this->item->peers[$i];
		?>
		<tr class="<?php echo "peer$k"; ?>">
			<td style="wrap: nowrap"><?php echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->peer->id."'>".$this->peer->name."</a>"; ?>
			</td>
			<td width="10%" nowrap align="center"><?php
					if (empty($this->peer->countryname)) {
						$this->peer->countryname = $this->item->default_country[0]->name;
						$this->peer->countryimage = $this->item->default_country[0]->image;
					}
				?> <img id="peercountry<?php echo $i;?>"
				alt="<?php echo $this->peer->countryname; ?>"
				src="<?php echo JURI::base().$this->peer->countryimage; ?>"
				width="32" />
			</td>
			<td width="10%" nowrap align="center">
				<?php
					$user_progress = number_format(100-(($this->peer->left*100)/$this->item->size), 2, ',', ' ');
					echo "&nbsp;".TrackerHelper::get_percent_completed_image($user_progress)."&nbsp;".$user_progress."&nbsp;%";	
				?>
			</td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->downloaded); ?></td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->uploaded); ?></td>
			<?php if ($params->get('peer_speed') == 1) { ?>
				<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->down_rate).'/s'; ?></td>
				<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->up_rate).'/s'; ?></td>
			<?php } ?>
			<td width="10%" nowrap align="center"><?php echo TrackerHelper::make_ratio($this->peer->downloaded,$this->peer->uploaded); ?></td>
		</tr>
		<?php
			$k = 1 - $k;
			}
		?>
	</table>
	</div>
	<?php } ?>

	<?php if (count($this->item->snatchers) > 0) { // Snatchers List ?>
	<div id="snatchers">
	<table
		style="cellpadding: 0; cellspacing: 0; border: 0; width: 99%; align: center;">
		<tr class="row1">
			<td>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_USER' );?></b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_COUNTRY' );?></b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' );?></b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' );?></b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_RATIO' );?></b></td>
		</tr>
		<?php
					$k = 0;
					for ($i=0, $n=count( $this->item->snatchers ); $i < $n; $i++) {
					$this->snatcher =& $this->item->snatchers[$i];
					?>
		<tr class="<?php echo "snatcher$k"; ?>">
			<td style="wrap: nowrap">&nbsp; <!-- USER --> <?php echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->snatcher->id."'>".$this->snatcher->name."</a>";?>
			</td>
			<td width="10%" nowrap align="center">
			<?php
				if (empty($this->snatcher->countryname)) {
					$this->snatcher->countryname = $this->item->default_country[0]->name;
					$this->snatcher->countryimage = $this->item->default_country[0]->image;
				}
			?>
			<img id="snatchercountry<?php echo $i;?>" alt="<?php echo $this->snatcher->countryname; ?>" src="<?php echo JURI::base().$this->snatcher->countryimage; ?>" width="32" /></td>
			<td width="10%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->snatcher->downloaded); ?></td>
			<td width="10%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->snatcher->uploaded); ?></td>
			<td width="10%" nowrap align="center">&nbsp;<?php echo TrackerHelper::make_ratio($this->snatcher->downloaded,$this->snatcher->uploaded); ?>&nbsp;</td>
		</tr>
		<?php
					$k = 1 - $k;
					}
				?>
	</table>
	</div>
	<?php } ?>

	<?php if (count($this->item->hitrunners) > 0) { // Hit and Runners List ?>
	<div id="hit-runners">
	<table
		style="cellpadding: 0; cellspacing: 0; border: 0; width: 99%; align: center;">
		<tr class="row1">
			<td nowrap>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_USER' );?></b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_COUNTRY' );?></b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' );?></b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' );?></b></td>
		</tr>
		<?php
					$k = 0;
					for ($i=0, $n=count( $this->item->hitrunners ); $i < $n; $i++) {
					$this->hitrunner =& $this->item->hitrunners[$i];
					?>
		<tr class="<?php echo "hitrunner$k"; ?>">
			<td style="wrap: nowrap"> <!-- USER -->
			<?php
				if ($params->get('allow_guest') && ($params->get('guest_user') == $user->id)) echo $this->hitrunner->name;
				else echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->hitrunner->id."'>".$this->hitrunner->name."</a>";
			?>
			</td>
			<td width="10%" nowrap align="center">
				<?php
					if (empty($this->hitrunner->countryname)) {
						$this->hitrunner->countryname = $this->item->default_country[0]->name;
						$this->hitrunner->countryimage = $this->item->default_country[0]->image;
					}
				?>
				<img id="hitrunnercountry<?php echo $i;?>" alt="<?php echo $this->hitrunner->countryname; ?>" src="<?php echo JURI::base().$this->hitrunner->countryimage; ?>" width="32" />
			</td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->hitrunner->downloaded); ?></td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->hitrunner->uploaded); ?></td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</table>
	</div>
	<?php } ?>

</div>

<?php
// Enable the commenting system if we have it enabled
	if ($params->get('enable_comments') && TrackerHelper::user_permissions('view_comments', $user->get('id'), 1)) {
		TrackerHelper::comments($this->item->fid, $this->item->name);
	}
?>

