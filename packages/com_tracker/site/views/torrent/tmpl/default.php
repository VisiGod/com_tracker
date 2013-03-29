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

jimport( 'joomla.html.html.tabs' );
$user	= JFactory::getUser();
$params =& JComponentHelper::getParams( 'com_tracker' );

if ($user->get('id') == 0) $this->item->groupID = 0;

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
<div class="row1" align="left" style="font-size: medium;">
	<b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_FOR' );?> </b>
	<?php echo str_replace("_", " ", $this->item->name);?>
</div>
<?php echo JHtml::_('tabs.start', 'torrent_details_start', $tab_options); ?>
<div>
	<?php echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_TORRENT_DETAILS'), 'torrent_details'); ?>
	<table
		style="cellpadding: 0; cellspacing: 2; border: 0; width: 99%; align: center;">
		<!-- Torrent Information -->
		<tr>
			<td colspan="4">
				<table style="width: 100%;">
					<?php if (TrackerHelper::user_permissions('download_torrents', $user->id)) { ?>
					<tr>
						<td valign="middle" style="height: 16px; width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_DOWNLOAD' );?>
						</b>&nbsp;</td>
						<td style="width: 99%;" colspan="2" nowrap>
							<a href="index.php?option=com_tracker&amp;task=torrent.download&amp;id=<?php echo $this->item->fid;?>"><?php echo $this->item->name;?></a>
							&nbsp;&nbsp;
							<a href="<?php echo JRoute::_("index.php?option=com_tracker&task=torrent.download&id=".$this->item->fid); ?>">
							<img src="<?php echo JURI::base();?>components/com_tracker/assets/images/download.gif" alt="<?php echo JText::_( 'COM_TRACKER_TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ); ?>" border="0" />
						</a>
						</td>
					</tr>
					<tr>
						<td style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_INFO_HASH' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo bin2hex($this->item->info_hash); ?>
						</td>
					</tr>
					<?php } else {?>
					<tr>
						<td valign="middle" style="height: 16px; width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' );?>
						</b>&nbsp;</td>
						<td style="width: 99%;" colspan="2" nowrap><?php echo $this->item->name;?></td>
					</tr>
					<?php } ?>
					<tr>
						<td style="width: 1%;" align="right" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DESCRIPTION' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->description;?>
						</td>
					</tr>
					<tr>
						<td style="width: 1%;" align="right"><b><?php echo JText::_( 'JCATEGORY' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->category_title;?>
						</td>
					</tr>
					<tr>
						<td style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_SIZE' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo TrackerHelper::make_size($this->item->size)."	( ".number_format($this->item->size)." ".JText::_( 'COM_TRACKER_BYTES' )." )";?>
						</td>
					</tr>
					<tr>
						<td style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_CREATED_TIME' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->created_time;?>
						</td>
					</tr>
					<?php if ($params->get('use_licenses') == 1) {?>
					<tr>
						<td style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_LICENSE' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->license;?>
						</td>
					</tr>
					<?php }?>
					<tr>
						<td nowrap style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_UPLOADER' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php
						//TODO: Torrent Details always shows anonymous
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
						<td nowrap style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->number_files." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES_FILES' );?>
						</td>
					</tr>
					<?php if ($params->get('torrent_multiplier') == 1) {?>
					<tr>
						<td nowrap style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->download_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );?>
						</td>
					</tr>
					<tr>
						<td nowrap style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' );?>
						</b>&nbsp;</td>
						<td style="width: 98%;" colspan="2"><?php echo $this->item->upload_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td nowrap style="width: 1%;" align="right" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_PEERS' );?>
						</b>&nbsp;</td>
						<td width="98%" valign="top" colspan="2"><?php echo $this->item->seeders." ".JText::_( 'COM_TRACKER_TORRENT_SEEDERS' ).", ".$this->item->leechers." ".JText::_( 'COM_TRACKER_TORRENT_LEECHERS' )." = ".($this->item->seeders+$this->item->leechers)." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_PEERS_TOTAL' );?>
						</td>
					</tr>
					<tr>
						<td nowrap style="width: 1%;" align="right" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_SNATCHERS' );?>
						</b>&nbsp;</td>
						<td width="98%" valign="top" colspan="2"><?php echo count( $this->item->snatchers )." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_SNATCHES' );?>
						</td>
					</tr>
					<tr>
						<td nowrap style="width: 1%;" align="right" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_HIT_RUNNER' );?></b>&nbsp;</td>
						<td width="98%" valign="top" colspan="2"><?php echo count( $this->item->hitrunners )." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_HIT_RUNNERS' );?>
						</td>
					</tr>
					<!-- Torrent Thanks -->
					<?php if ($params->get('enable_thankyou') == 1) { ?>
					<tr>
						<td nowrap style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_THANKYOUS' );?></b>&nbsp;</td>
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
						<?php if ((TrackerHelper::checkThanks($user->id, $this->item->fid) <> 0) && ($user->id <> $this->item->uploader)) { ?>
						<td nowrap style="width: 1%;" align="right" colspan="2">&nbsp;
							<a href='index.php?option=com_tracker&amp;task=torrent.thanks&amp;id=<?php echo $this->item->fid;?>'>
								<?php echo JText::_( 'COM_TRACKER_TORRENT_SAY_THANKYOU' );?>
							</a>&nbsp;
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
					
					<!-- Reseed request -->
					<?php if (($this->item->seeders == 0) && $params->get('enable_reseedrequest')) { ?>
					<tr>
						<td nowrap style="width: 1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_RESEED_REQUESTS' );?></b>&nbsp;</td>
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
						<td nowrap style="width: 1%;" align="right">&nbsp;
							<a href='index.php?option=com_tracker&amp;task=torrent.reseed&amp;id=<?php echo $this->item->fid;?>'>
								<?php echo JText::_( 'COM_TRACKER_REQUEST_RESEED' );?>
							</a>&nbsp;
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
					
				</table>
			</td>
			<!-- Image file - STILL TO IMPLEMENT -->
			<?php if ($params->get('use_image_file') && is_readable('torrents/'.$this->item->fid.'_'.$this->item->image_file)) { ?>
			<td valign="middle">
				<a href="<?php echo JURI::base().'torrents/'.$this->item->fid.'_'.$this->item->image_file; ?>" class="modal">
					<img src="<?php echo JURI::base().'torrents/thumb_'.$this->item->fid.'_'.$this->item->image_file; ?>" alt="<?php echo $this->item->image_file; ?>" />
				</a>
			</td>
			<?php } ?>
		</tr>

		<?php if (($this->item->forum_post && $params->get('forum_post_id')) || ($this->item->info_post && $params->get('torrent_information')) ||
				 ($params->get('enable_reporttorrent'))
			 	 ) { ?>
		<tr>
			<!--  Forum post ID -->
			<?php if ($this->item->forum_post && $params->get('forum_post_id')) { ?>
				<td align="center" class="row1" width="50%">
					<b><a href="<?php echo htmlspecialchars($params->get('forum_post_url').$this->item->forum_post);?>" target="_blank"><?php echo JText::_( 'COM_TRACKER_TORRENT_FORUM_POST' );?></a></b>
				</td>
			<?php } ?>
			<!-- Torrent information page -->
			<?php if ($this->item->info_post && $params->get('torrent_information')) { ?>
				<td align="center" class="row1" width="50%">
					<b><a href="<?php echo htmlspecialchars($params->get('info_post_url').$this->item->info_post);?>" target="_blank"><?php echo $params->get('info_post_description');?></a></b>
				</td>
			<?php } ?>
			<!-- Torrent reporting -->
			<?php if ($params->get('enable_reporttorrent')) { ?>
				<?php if ((TrackerHelper::checkReportedTorrent($user->id, $this->item->fid) <> 0) && ($user->id <> $this->item->uploader)) { ?>
					<td align="center" class="row1" width="50%">
						<b>
							<a href="index.php?option=com_tracker&view=report&id=<?php echo $this->item->fid;?>&tmpl=component" class="modal" title="Report Torrent" rel="{handler: 'iframe', size: {x: 800, y: 600}}">
								<?php echo JText::_( 'COM_TRACKER_REPORT_TORRENT' );?>
							</a>
						</b>
					</td>
				<?php } else { ?>
					<td align="center" class="row1" width="50%">
						<b><?php echo JText::_( 'COM_TRACKER_REPORT_ALREADY_SENT' );?></b>
					</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<?php } ?>
	</table>

	<?php echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_TORRENT_DETAILS_FILE_LIST'), 'torrent_files'); ?>
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
						<td nowrap>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_TORRENT_FILETYPE' );?>&nbsp;</b></td>
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
						<td nowrap align="right">&nbsp;<?php TrackerHelper::getFileImage($this->torrent_file->filename); ?>&nbsp;</td>
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
	<?php
		if (count($this->item->peers) > 0) { // Peer List
		echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_TORRENT_DETAILS_PEER_LIST'), 'torrent_peers');
	?>
	<table
		style="cellpadding: 0; cellspacing: 0; border: 0; width: 99%; align: center;">
		<tr class="row1">
			<td nowrap>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_USER' );?> </b>
			</td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_COUNTRY' );?>
			</b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_PROGRESS' );?>
			</b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' );?>
			</b></td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' );?>
			</b></td>
			<?php if ($params->get('peer_speed') == 1) { ?>
			<td width="10%" align="center"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_SPEED' );?>
			</b></td>
			<td width="10%" align="center"><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_SPEED' );?>
			</b></td>
			<?php } ?>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_RATIO' );?>
			</b></td>
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
			<td width="10%" nowrap align="left">
				<?php
					$user_progress = number_format(100-(($this->peer->left*100)/$this->item->size), 2, ',', ' ');
					echo "&nbsp;".TrackerHelper::get_percent_completed_image($user_progress)."&nbsp;".$user_progress."&nbsp;%";	
				?>
			</td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->downloaded); ?>
			</td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->uploaded); ?>
			</td>
			<?php if ($params->get('peer_speed') == 1) { ?>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->down_rate).'/s'; ?>
			</td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->peer->up_rate).'/s'; ?>
			</td>
			<?php } ?>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_ratio($this->peer->downloaded,$this->peer->uploaded); ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
			}
		?>
	</table>
	<?php
		}
		if (count($this->item->snatchers) > 0) { // Snatchers List
			echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_TORRENT_DETAILS_SNATCHERS'), 'torrent_seeders');
			?>
	<table
		style="cellpadding: 0; cellspacing: 0; border: 0; width: 99%; align: center;">
		<tr class="row1">
			<td>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_USER' );?> </b>
			</td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_COUNTRY' );?>
			</b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' );?>
			</b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' );?>
			</b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_RATIO' );?>
			</b></td>
		</tr>
		<tr>
			<td colspan="5"><hr /></td>
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
			<img id="snatchercountry<?php echo $i;?>" alt="<?php echo $this->snatcher->countryname; ?>" src="<?php echo JURI::base().$this->snatcher->countryimage; ?>" width="32" />
			</td>
			<td width="10%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->snatcher->downloaded); ?>
			</td>
			<td width="10%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_size($this->snatcher->uploaded); ?>
			</td>
			<td width="10%" nowrap align="right">&nbsp;<?php echo TrackerHelper::make_ratio($this->snatcher->downloaded,$this->snatcher->uploaded); ?>&nbsp;
			</td>
		</tr>
		<?php
					$k = 1 - $k;
					}
				?>
	</table>
	<?php
		}
		if (count($this->item->hitrunners) > 0) { // Hit and Runners List
			echo JHtml::_('tabs.panel', JText::_('COM_TRACKER_TORRENT_DETAILS_HIT_RUNNER'), 'torrent_runners');
			?>
	<table
		style="cellpadding: 0; cellspacing: 0; border: 0; width: 99%; align: center;">
		<tr class="row1">
			<td nowrap>&nbsp;<b><?php echo JText::_( 'COM_TRACKER_USER' );?> </b>
			</td>
			<td width="10%" nowrap align="center"><b><?php echo JText::_( 'COM_TRACKER_COUNTRY' );?>
			</b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOADED' );?>
			</b></td>
			<td width="10%" nowrap align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOADED' );?>
			</b></td>
		</tr>
		<tr>
			<td colspan="5"><hr /></td>
		</tr>
		<?php
					$k = 0;
					for ($i=0, $n=count( $this->item->hitrunners ); $i < $n; $i++) {
					$this->hitrunner =& $this->item->hitrunners[$i];
					?>
		<tr class="<?php echo "hitrunner$k"; ?>">
			<td style="wrap: nowrap">
				<!-- USER --> <?php
							if ($params->get('allow_guest') && ($params->get('guest_user') == $user->id)) echo $this->hitrunner->name;
							else echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->hitrunner->id."'>".$this->hitrunner->name."</a>";
						?>
			</td>
			<td width="10%" nowrap align="center"><?php
								if (empty($this->hitrunner->countryname)) {
									$this->hitrunner->countryname = $this->item->default_country[0]->name;
									$this->hitrunner->countryimage = $this->item->default_country[0]->image;
								}
							?> <img id="hitrunnercountry<?php echo $i;?>"
				alt="<?php echo $this->hitrunner->countryname; ?>"
				src="<?php echo JURI::base().$this->hitrunner->countryimage; ?>"
				width="32" />
			</td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->hitrunner->downloaded); ?>
			</td>
			<td width="10%" nowrap align="right"><?php echo TrackerHelper::make_size($this->hitrunner->uploaded); ?>
			</td>
		</tr>
		<?php
					$k = 1 - $k;
					}
				?>
	</table>
	<?php
		}
		echo JHtml::_('tabs.end');

		if ($params->get('enable_comments') && TrackerHelper::user_permissions('view_comments', $user->get('id'), 1)) {
			TrackerHelper::comments($this->item->fid, $this->item->name);
		}

/*
// Something to work on when base is done
		if ($this->params->get('use_comments') && TrackerHelper::user_permissions('view_comments', $user->get('id'), 1) && count($this->item->comments) > 0) {
			?>
			<br />
			<table style="cellpadding:0; cellspacing:0; border:0; width:100%; align:center;"> <!-- Torrent Comments -->
				<tr class="row1">
					<td width="100%" colspan="2" align="center">
						<b>Comments</b>
					</td>
				</tr>
				<?php
				for ($i=0, $n=count( $this->item->comments ); $i < $n; $i++) {
					$this->comments =& $this->item->comments[$i];
				?>
				<tr class="row1">
					<td width="1%" nowrap>
						&nbsp;<b><?php echo JText::_( 'COM_TRACKER_LABEL_USER' );?></b>:&nbsp;
						<?php
							if ($params->get('allow_guest') && ($params->get('guest_user') == $user->id)) echo $this->comments->username;
							else echo "<a href='index.php?option=com_tracker&amp;view=userpanel&amp;id=".$this->comments->userid."'>".$this->comments->username."</a>";
						?>
					<br />
						&nbsp;<b><?php echo JText::_( 'COM_TRACKER_TORRENTS_ADDED' );?></b>:&nbsp;
						<?php echo $this->comments->commentdate; ?>
					</td>
					<td width="99%" nowrap align="left" style="padding:0.2em; padding-bottom: 1em;">
						<?php echo $this->comments->description; ?>
					</td>
				</tr>
			<?php
			}
			echo "<br>comment_only_leecher = ".$params->get('comment_only_leecher');
			echo "<br>this->item->isleecher = ".$this->item->isleecher."<br>";
			if (TrackerHelper::user_permissions('write_comments', $user->get('id'), 1)) {
				//if (($params->get('comment_only_leecher') && $this->item->isleecher)) {
				?>
					<tr class="row1">
						<td width="100%" colspan="2" align="right">
							<a class="modal" href="index.php?option=com_tracker&view=comment&tmpl=component&id=<?php echo (int)$this->item->fid;?>&name=<?php echo base64_encode($this->item->name);?>">Comment</a>
						</td>
					</tr>
				<?php
				//}
			}
			?>
			</table>
		<?php }
*/
		?>
</div>
