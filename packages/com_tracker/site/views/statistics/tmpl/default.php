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
?>

<?php if ($this->params->get('number_torrents') || $this->params->get('number_files') || $this->params->get('total_seeders') || 
		  $this->params->get('total_leechers') || $this->params->get('total_completed') || $this->params->get('bytes_shared') ||
		  $this->params->get('download_speed') || $this->params->get('upload_speed') || $this->params->get('bytes_downloaded') || $this->params->get('bytes_uploaded')) : ?>

		<!-- Statistics -->
		<ul class="nav nav-pills">
			<li class="active"><a href="#statistics" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATISTICS_TOTALS'); ?></a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="statistics">
				<div class="container-fluid">
					<div class="row-fluid">
			        	<div class="span12">
        			    	<div class="row-fluid">
								<?php if ($this->params->get('number_torrents')) ?>		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_TORRENTS');?></strong></div>
								<?php if ($this->params->get('number_files')) ?> 		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_FILES');?></strong></div>
								<?php if ($this->params->get('total_seeders')) ?> 		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_SEEDERS');?></strong></div>
								<?php if ($this->params->get('total_leechers')) ?> 		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_LEECHERS');?></strong></div>
								<?php if ($this->params->get('total_completed')) ?> 	<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_COMPLETED');?></strong></div>
								<?php if ($this->params->get('bytes_shared')) ?> 		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_SHARED_DATA');?></strong></div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
			        	<div class="span12">
        			    	<div class="row-fluid">
								<?php if ($this->params->get('number_torrents')) ?>		<div class="span2 text-center"><?php echo $this->item->torrents;?></div>
								<?php if ($this->params->get('number_files')) ?> 		<div class="span2 text-center"><?php echo $this->item->files;?></div>
								<?php if ($this->params->get('total_seeders')) ?> 		<div class="span2 text-center"><?php echo $this->item->seeders;?></div>
								<?php if ($this->params->get('total_leechers')) ?> 		<div class="span2 text-center"><?php echo $this->item->leechers;?></div>
								<?php if ($this->params->get('total_completed')) ?> 	<div class="span2 text-center"><?php echo $this->item->completed;?></div>
								<?php if ($this->params->get('bytes_shared')) ?> 		<div class="span2 text-center"><?php echo TrackerHelper::make_size($this->item->shared);?></div>
							</div>
						</div>
					</div>
					<br />
					<div class="row-fluid">
			        	<div class="span12">
        			    	<div class="row-fluid">
        			    		<div class="span1"></div>
								<?php if ($this->params->get('download_speed')) ?>		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_DOWNLOAD_SPEED');?></strong></div>
								<?php if ($this->params->get('upload_speed')) ?> 		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_UPLOAD_SPEED');?></strong></div>
								<?php if ($this->params->get('bytes_downloaded')) ?> 	<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_DOWNLOADED_DATA');?></strong></div>
								<?php if ($this->params->get('bytes_uploaded')) ?> 		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_UPLOADED_DATA');?></strong></div>
								<?php if ($this->params->get('bytes_downloaded') || 
										  $this->params->get('bytes_uploaded')) ?> 		<div class="span2 text-center"><strong><?php echo JText::_('COM_TRACKER_STATS_TOTAL_DATA');?></strong></div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
			        	<div class="span12">
        			    	<div class="row-fluid">
        			    		<div class="span1"></div>
								<?php if ($this->params->get('download_speed')) ?>		<div class="span2 text-center"><?php echo TrackerHelper::make_speed($this->item->total_speed->download_rate);?></div>
								<?php if ($this->params->get('upload_speed')) ?> 		<div class="span2 text-center"><?php echo TrackerHelper::make_speed($this->item->total_speed->upload_rate);?></div>
								<?php if ($this->params->get('bytes_downloaded')) ?> 	<div class="span2 text-center"><?php echo TrackerHelper::make_size($this->item->total_transferred->user_downloaded);?></div>
								<?php if ($this->params->get('bytes_uploaded')) ?> 		<div class="span2 text-center"><?php echo TrackerHelper::make_size($this->item->total_transferred->user_uploaded);?></div>
								<?php if ($this->params->get('bytes_downloaded') || 
										  $this->params->get('bytes_uploaded')) ?> 		<div class="span2 text-center"><?php echo TrackerHelper::make_size($this->item->total_transferred->user_downloaded + $this->item->total_transferred->user_uploaded);?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>

<?php if (($this->params->get('top_downloaders') && count($this->item->top_downloaders)) || ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) || 
		 ($this->params->get('top_sharers') && count($this->item->top_sharers)) || ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) || 
		 ($this->params->get('top_thanked') && count($this->item->top_thanked)) ||($this->params->get('top_thanker') && count($this->item->top_thanker))) : ?>

	<ul class="nav nav-pills">
		<?php if ($this->params->get('top_downloaders') && count($this->item->top_downloaders)) : ?>
			<li class="active"><a href="#top_downloaders" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_DOWNLOADERS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) : ?>
			<li><a href="#top_uploaders" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_UPLOADERS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('top_sharers') && count($this->item->top_sharers)) : ?>
			<li><a href="#top_sharers" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_SHARERS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) : ?>
			<li><a href="#worst_sharers" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_WORST_SHARERS'); ?></a></li>
		<?php endif; ?>
				
		<?php if ($this->params->get('top_thanked') && count($this->item->top_thanked)) : ?>
			<li><a href="#top_thanked" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_THANKED'); ?></a></li>
		<?php endif; ?>
				
		<?php if ($this->params->get('top_thanker') && count($this->item->top_thanker)) : ?>
			<li><a href="#top_thanker" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_THANKERS'); ?></a></li>
		<?php endif; ?>
	</ul>

	<div class="tab-content">
		<?php if ($this->params->get('top_downloaders') && count($this->item->top_downloaders)) : ?>
			<!-- Top Downloaders -->
			<div class="tab-pane active" id="top_downloaders">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_UPLOADED' ); ?></th>
							<?php if ($this->params->get('enable_countries')) ?> <th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_GROUP' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->top_downloaders as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=userpanel&amp;id=".$item->uid); ?>'><?php echo ($this->params->get('top_downloaders_username')) ? $item->username : $item->name; ?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->uploaded); ?></td>
								<?php if ($this->params->get('enable_countries')) : ?>
									<?php
										if (empty($item->countryName)) :
											$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$item->countryName = $item->default_country->name; 
											$item->countryImage = $item->default_country->image;
										endif;
									?>
									<td style="white-space:nowrap; text-align:center;"><img style="vertical-align:middle;" id="tdcountry<?php echo $item->uid; ?>" alt="<?php echo $item->countryName; ?>" src="<?php echo JURI::base().$item->countryImage; ?>" width="32px" /></td>
								<?php else : echo '<td style="white-space:nowrap; text-align:center;">&nbsp;</td>'; ?>
								<?php endif; ?>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->usergroup; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('top_uploaders') && count($this->item->top_uploaders)) : ?>
			<!-- Top Uploaders -->
			<div class="tab-pane" id="top_uploaders">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_UPLOADED' ); ?></th>
							<?php if ($this->params->get('enable_countries')) ?> <th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_GROUP' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->top_uploaders as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=userpanel&amp;id=".$item->uid); ?>'><?php echo ($this->params->get('top_uploaders_username')) ? $item->username : $item->name; ?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->uploaded); ?></td>
								<?php if ($this->params->get('enable_countries')) : ?>
									<?php
										if (empty($item->countryName)) :
											$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$item->countryName = $item->default_country->name; 
											$item->countryImage = $item->default_country->image;
										endif;
									?>
									<td style="white-space:nowrap; text-align:center;"><img style="vertical-align:middle;" id="tdcountry<?php echo $item->uid; ?>" alt="<?php echo $item->countryName; ?>" src="<?php echo JURI::base().$item->countryImage; ?>" width="32px" /></td>
								<?php else : echo '<td style="white-space:nowrap; text-align:center;">&nbsp;</td>'; ?>
								<?php endif; ?>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->usergroup; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('top_sharers') && count($this->item->top_sharers)) : ?>
			<!-- Top Sharers -->
			<div class="tab-pane" id="top_sharers">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_UPLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_RATIO' ); ?></th>
							<?php if ($this->params->get('enable_countries')) ?> <th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_GROUP' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->top_sharers as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=userpanel&amp;id=".$item->uid); ?>'><?php echo ($this->params->get('top_sharers_username')) ? $item->username : $item->name; ?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->uploaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::get_ratio($item->uploaded, $item->downloaded); ?></td>
								<?php if ($this->params->get('enable_countries')) : ?>
									<?php
										if (empty($item->countryName)) :
											$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$item->countryName = $item->default_country->name; 
											$item->countryImage = $item->default_country->image;
										endif;
									?>
									<td style="white-space:nowrap; text-align:center;"><img style="vertical-align:middle;" id="tdcountry<?php echo $item->uid; ?>" alt="<?php echo $item->countryName; ?>" src="<?php echo JURI::base().$item->countryImage; ?>" width="32px" /></td>
								<?php else : echo '<td style="white-space:nowrap; text-align:center;">&nbsp;</td>'; ?>
								<?php endif; ?>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->usergroup; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('worst_sharers') && count($this->item->worst_sharers)) : ?>
			<!-- Worst Sharers -->
			<div class="tab-pane" id="worst_sharers">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_DOWNLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_UPLOADED' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_RATIO' ); ?></th>
							<?php if ($this->params->get('enable_countries')) ?> <th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_GROUP' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->worst_sharers as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=userpanel&amp;id=".$item->uid); ?>'><?php echo ($this->params->get('worst_sharers_username')) ? $item->username : $item->name; ?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->downloaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->uploaded); ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::get_ratio($item->uploaded, $item->downloaded); ?></td>
								<?php if ($this->params->get('enable_countries')) : ?>
									<?php
										if (empty($item->countryName)) :
											$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$item->countryName = $item->default_country->name; 
											$item->countryImage = $item->default_country->image;
										endif;
									?>
									<td style="white-space:nowrap; text-align:center;"><img style="vertical-align:middle;" id="tdcountry<?php echo $item->uid; ?>" alt="<?php echo $item->countryName; ?>" src="<?php echo JURI::base().$item->countryImage; ?>" width="32px" /></td>
								<?php endif; ?>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->usergroup; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('top_thanked') && count($this->item->top_thanked)) : ?>
			<!-- Top Thanked -->
			<div class="tab-pane" id="top_thanked">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_THANKED' ); ?></th>
							<?php if ($this->params->get('enable_countries')) ?> <th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_GROUP' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->top_thanked as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=userpanel&amp;id=".$item->uid); ?>'><?php echo ($this->params->get('top_thanked_username')) ? $item->username : $item->name; ?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo $item->total_thanks; ?></td>
								<?php if ($this->params->get('enable_countries')) : ?>
									<?php
										if (empty($item->countryName)) :
											$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$item->countryName = $item->default_country->name; 
											$item->countryImage = $item->default_country->image;
										endif;
									?>
									<td style="white-space:nowrap; text-align:center;"><img style="vertical-align:middle;" id="tdcountry<?php echo $item->uid; ?>" alt="<?php echo $item->countryName; ?>" src="<?php echo JURI::base().$item->countryImage; ?>" width="32px" /></td>
								<?php endif; ?>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->usergroup; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('top_thanker') && count($this->item->top_thanker)) : ?>
			<!-- Top Thanker -->
			<div class="tab-pane" id="top_thanker">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_USER' ); ?></th>
							<th style="white-space:nowrap; text-align:right;"><?php echo JText::_( 'COM_TRACKER_STATS_THANKER' ); ?></th>
							<?php if ($this->params->get('enable_countries')) ?> <th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_COUNTRY' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_GROUP' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->top_thanker as $i => $item) : ?>
							<tr>
								<td><a href='<?php echo JRoute::_("index.php?option=com_tracker&view=userpanel&amp;id=".$item->uid); ?>'><?php echo ($this->params->get('top_thanker_username')) ? $item->username : $item->name; ?></a></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo $item->thanker; ?></td>
								<?php if ($this->params->get('enable_countries')) : ?>
									<?php
										if (empty($item->countryName)) :
											$item->default_country = TrackerHelper::getCountryDetails($this->params->get('defaultcountry'));
											$item->countryName = $item->default_country->name; 
											$item->countryImage = $item->default_country->image;
										endif;
									?>
									<td style="white-space:nowrap; text-align:center;"><img style="vertical-align:middle;" id="tdcountry<?php echo $item->uid; ?>" alt="<?php echo $item->countryName; ?>" src="<?php echo JURI::base().$item->countryImage; ?>" width="32px" /></td>
								<?php endif; ?>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->usergroup; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
<?php endif; ?>

<?php if (($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) || ($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) || 
		 ($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) || ($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) ||
		 ($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents))) : ?>

	<ul class="nav nav-pills">
		<?php if ($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) : ?>
			<li class="active"><a href="#most_active_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_ACTIVE_TORRENTS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) : ?>
			<li><a href="#most_seeded_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_SEEDED_TORRENTS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) : ?>
			<li><a href="#most_leeched_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_LEECHED_TORRENTS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) : ?>
			<li><a href="#most_completed_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_COMPLETED_TORRENTS'); ?></a></li>
		<?php endif; ?>
				
		<?php if ($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents)) : ?>
			<li><a href="#most_thanked_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_TOP_THANKED_TORRENTS'); ?></a></li>
		<?php endif; ?>
	</ul>

	<div class="tab-content">
		<?php if ($this->params->get('most_active_torrents') && count($this->item->most_active_torrents)) : ?>
			<!-- Most Active Torrents -->
			<div class="tab-pane active" id="most_active_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->most_active_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('most_seeded_torrents') && count($this->item->most_seeded_torrents)) : ?>
			<!-- Most Seeded Torrents -->
			<div class="tab-pane" id="most_seeded_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->most_seeded_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('most_leeched_torrents') && count($this->item->most_leeched_torrents)) : ?>
			<!-- Most Leeched Torrents -->
			<div class="tab-pane" id="most_leeched_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->most_leeched_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('most_completed_torrents') && count($this->item->most_completed_torrents)) : ?>
			<!-- Most Completed Torrents -->
			<div class="tab-pane" id="most_completed_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->most_completed_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('most_thanked_torrents') && count($this->item->top_thanked_torrents)) : ?>
			<!-- Most Thanked Torrents -->
			<div class="tab-pane" id="most_thanked_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_THANKED' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->top_thanked_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->total_thanks; ?></td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
<?php endif; ?>

<?php if (($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) || ($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) || 
		 ($this->params->get('worst_leeched_torrents') && count($this->item->worst_leeched_torrents)) || ($this->params->get('worst_completed_torrents') && count($this->item->worst_completed_torrents))) : ?>

	<ul class="nav nav-pills">
		<?php if ($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) : ?>
			<li class="active"><a href="#worst_active_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_WORST_ACTIVE_TORRENTS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) : ?>
			<li><a href="#worst_seeded_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_WORST_SEEDED_TORRENTS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('worst_leeched_torrents') && count($this->item->worst_leeched_torrents)) : ?>
			<li><a href="#worst_leeched_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_WORST_LEECHED_TORRENTS'); ?></a></li>
		<?php endif; ?>

		<?php if ($this->params->get('worst_completed_torrents') && count($this->item->worst_completed_torrents)) : ?>
			<li><a href="#worst_completed_torrents" data-toggle="tab"><?php echo JText::_('COM_TRACKER_STATS_WORST_COMPLETED_TORRENTS'); ?></a></li>
		<?php endif; ?>
	</ul>

	<div class="tab-content">
		<?php if ($this->params->get('worst_active_torrents') && count($this->item->worst_active_torrents)) : ?>
			<!-- Worst Active Torrents -->
			<div class="tab-pane active" id=worst_active_torrents>
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->worst_active_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('worst_seeded_torrents') && count($this->item->worst_seeded_torrents)) : ?>
			<!-- Worst Seeded Torrents -->
			<div class="tab-pane" id="worst_seeded_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->worst_seeded_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('worst_leeched_torrents') && count($this->item->worst_leeched_torrents)) : ?>
			<!-- Worst Leeched Torrents -->
			<div class="tab-pane" id="worst_leeched_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->worst_leeched_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('worst_completed_torrents') && count($this->item->worst_completed_torrents)) : ?>
			<!-- Worst Completed Torrents -->
			<div class="tab-pane" id="worst_completed_torrents">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_TRACKER_STATS_NAME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_SIZE' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_STATS_CREATED_TIME' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_SEEDERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LEECHERS_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'COM_TRACKER_TORRENT_COMPLETED_SMALL' ); ?></th>
							<th style="white-space:nowrap; text-align:center;"><?php echo JText::_( 'JCATEGORY' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->item->worst_completed_torrents as $i => $item) : ?>
							<tr>
								<td>
									<?php if (TrackerHelper::user_permissions('download_torrents', $this->session->get('user')->id, 1)) : ?>
										<a href='<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&amp;id=".$item->fid); ?>'><?php echo $item->name; ?></a>
									<?php else : ?>
										<?php echo $item->name; ?>
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap; text-align:right;"><?php echo TrackerHelper::make_size($item->size); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo date ('Y.m.d', strtotime($item->created_time)); ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->seeders; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->leechers; ?></td>
								<td style="white-space:nowrap; text-align:center;"><?php echo $item->completed; ?></td>
								<?php 
									$category_params = new JRegistry();
									$category_params->loadString($item->cat_params);
								?>
								<td style="white-space:nowrap; text-align:center;">
									<?php if (@is_array(getimagesize(JUri::root(false).DIRECTORY_SEPARATOR.$category_params->get('image')))) : ?>
										 <img id="image<?php echo $item->fid; ?>" alt="<?php echo $item->cat_title; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="<?php echo $this->params->get('category_image_size'); ?>" />
									<?php else : ?>
										<?php echo $item->cat_title; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
<?php endif; ?>