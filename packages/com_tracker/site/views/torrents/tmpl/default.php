<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('bootstrap.tooltip');

// Create a shortcut for params.
$params = &$this->params;

// Get the user object.
$user = &$this->user;

if ($user->guest && $params->get('allow_guest')) :
	$user->id = $params->get('guest_user');
endif;

// Check if user is allowed to add/edit based on tracker permissinos.
$canEditState = $user->authorise('core.edit.state', 'com_tracker');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

// Show extra page text (defined in menu)
if ($params->get('menu_text')) echo '<h2>'.$this->escape($params->get('menu-anchor_title')).'</h2>';

$torrentType = array(
		JHtml::_('select.option', '1', JText::_('COM_TRACKER_SELECT_TORRENTS_WITH_PEERS') ),
		JHtml::_('select.option', '2', JText::_('COM_TRACKER_SELECT_TORRENTS_WITH_SEEDERS') ),
		JHtml::_('select.option', '3', JText::_('COM_TRACKER_SELECT_TORRENTS_ONLY_LEECHERS') ),
		JHtml::_('select.option', '4', JText::_('COM_TRACKER_SELECT_TORRENTS_DEAD') ),
);
?>
	<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<fieldset class="filters btn-toolbar">
			<?php if ($params->get('tl_search_bar')) : ?>
			<div class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="input-large" onchange="document.adminForm.submit();" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" placeholder="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" />
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter-search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>
			</div>
			<?php endif; ?>

			<div class="btn-group pull-right nowrap" style="margin-left: 5px;">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
					<?php echo $this->pagination->getLimitBox(); ?>
				</label>
			</div>

			<?php if ($params->get('tl_category_dropdown')) : ?>
			<div class="btn-group pull-right">
				<select name="filter_category_id" class="input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_tracker'), 'value', 'text', $this->state->get('filter.category_id'));?>
				</select>
			</div>
			<?php endif; ?>

			<?php if ($params->get('tl_license_dropdown') && $params->get('enable_licenses')) : ?>
			<div class="btn-group pull-right">
				<select name="filter_license_id" class="input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_TRACKER_SELECT_LICENSE');?></option>
					<?php echo JHtml::_('select.options', TrackerHelper::SelectList('licenses', 'id', 'shortname', '1'), 'value', 'text', $this->state->get('filter.license_id')); ?>
				</select>
			</div>
			<?php endif; ?>

			<?php if ($params->get('tl_torrent_status_dropdown')) : ?>
			<div class="btn-group pull-right">
				<select name="filter_torrent_status" class="input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_TRACKER_SELECT_ALL_TORRENTS');?></option>
					<?php echo JHtml::_('select.options', $torrentType, 'value', 'text', $this->state->get('filter.torrent_status'));?>
				</select>
			</div>
			<?php endif; ?>
		</fieldset>
		<div class="clr"></div>

		<?php if (empty($this->items)) : ?>
			<div class="text-center middle">
				<h2><?php echo JText::_('COM_TRACKER_NO_TORRENTS'); ?></h2>
			</div>
		<?php else : ?>
		
		<table class="category table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<?php if ($params->get('tl_name')) : ?>
						<th id="torrentlist_header_name">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_NAME', 't.name', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_alias')) : ?>
						<th id="torrentlist_header_alias">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_ALIAS', 't.alias', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('enable_torrent_type')) : ?>
						<th id="torrentlist_header_torrenttype" class="nowrap">
							<div class="text-center middle">
								<?php echo JText::_( 'COM_TRACKER_TORRENT_TYPE' ); ?>
							</div>
						</th>
					<?php endif; ?>					
					<?php if ($params->get('tl_info_hash')) : ?>
						<th id="torrentlist_header_info_hash">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_INFO_HASH', 't.info_hash', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_filename')) : ?>
						<th id="torrentlist_header_filename">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_FILENAME', 't.filename', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_category')) : ?>
						<th id="torrentlist_header_category">
							<div class="text-center middle">
								<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'c.title', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_license')) : ?>
						<th id="torrentlist_header_license">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_LICENSE', 'license', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_description')) : ?>
						<th id="torrentlist_header_description">
							<?php echo JText::_('COM_TRACKER_TORRENT_DESCRIPTION'); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_size')) : ?>
						<th id="torrentlist_header_size" class="nowrap">
							<div class="text-right">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_SIZE', 't.size', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_created_time')) : ?>
						<th id="torrentlist_header_created_time">
							<div class="text-right">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_CREATED_TIME', 't.created_time', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_seeders')) : ?>
						<th id="torrentlist_header_seeders">
							<div class="text-center middle">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_SEEDERS_SMALL', 't.seeders', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_leechers')) : ?>
						<th id="torrentlist_header_leechers">
							<div class="text-center middle">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_LEECHERS_SMALL', 't.leechers', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_completed')) : ?>
						<th id="torrentlist_header_completed">
							<div class="text-center middle">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_COMPLETED_SMALL', 't.completed', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>

					<?php if ($params->get('tl_uploader_name')) : ?>
						<th id="torrentlist_header_uploader_name">
							<div class="text-center middle">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_UPLOADER', 'uploader_name', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_uploader_username')) : ?>
						<th id="torrentlist_header_uploader_username">
							<div class="text-center middle">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_UPLOADER', 'uploader_username', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_number_files')) : ?>
						<th id="torrentlist_header_number_files">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_NUMBER_FILES', 't.number_files', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_forum_post')) : ?>
						<th id="torrentlist_header_forum_post">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_FORUM_POST', 't.forum_post', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_info_post')) : ?>
						<th id="torrentlist_header_info_post">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_INFO_POST', 't.info_post', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_download_multiplier')) : ?>
						<th id="torrentlist_header_download_multiplier">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_DOWNLOAD_MULTIPLIER', 't.download_multiplier', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_upload_multiplier')) : ?>
						<th id="torrentlist_header_upload_multiplier">
							<?php echo JHtml::_('grid.sort', 'COM_TRACKER_UPLOAD_MULTIPLIER', 't.upload_multiplier', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<?php if ($params->get('tl_torrent_thanks')) : ?>
						<th id="torrentlist_header_torrent_thanks">
							<div class="text-center middle">
								<?php echo JHtml::_('grid.sort', 'COM_TRACKER_USER_THANKS', 'thanks', $listDirn, $listOrder); ?>
							</div>
						</th>
					<?php endif; ?>
					<?php if (TrackerHelper::user_permissions('download_torrents', $this->user->id) && $params->get('tl_download_image')) : ?>
						<th id="torrentlist_header_download_torrents">
							<div class="text-center middle">
								<?php echo JText::_( 'COM_TRACKER_DOWNLOAD_IMAGE_TEXT' ); ?>
							</div>
						</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $torrent) : ?>
					<tr class="cat-list-row<?php echo $i % 2; ?>" >
						<?php if ($params->get('tl_name')) : ?>
							<td headers="torrentlist_header_name" class="list-name">
								<a href="<?php echo JRoute::_("index.php?view=torrent&id=".(int)$torrent->fid); ?>">
									<?php echo $this->escape(str_replace('_', ' ', $torrent->name)); ?>
								</a>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_alias')) : ?>
							<td headers="torrentlist_header_alias" class="list-alias">
								<a href="<?php echo JRoute::_("index.php?view=torrent&id=".(int)$torrent->fid); ?>">
									<?php echo $torrent->alias; ?>
								</a>
							</td>
						<?php endif; ?>
						<?php if ($params->get('enable_torrent_type')) : ?>
							<td headers="torrentlist_header_torrenttype" class="list-torrenttype">
								<div class="text-center middle">
									<?php echo TrackerHelper::checkTorrentType((int)$torrent->fid);?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_info_hash')) : ?>
							<td headers="torrentlist_header_info_hash" class="list-info_hash">
								<?php echo bin2hex($torrent->info_hash);?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_filename')) : ?>
							<td headers="torrentlist_header_filename" class="list-filename">
								<?php echo $torrent->filename;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_category')) : ?>
							<?php
								$category_params = new JRegistry();
								$category_params->loadString($torrent->category_params);
							?>
							<td headers="torrentlist_header_category" class="list-category">
								<div class="text-center middle">
									<?php if (is_file($_SERVER['DOCUMENT_ROOT'].JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image')) && $params->get('use_image_file')) { ?>
										<img id="image<?php echo $torrent->fid;?>" alt="<?php echo $torrent->torrent_category; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="36" />
									<?php } else echo '&nbsp;'.$torrent->torrent_category.'&nbsp;'; ?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_license')) : ?>
							<td headers="torrentlist_header_license" class="list-license">
								<?php echo $torrent->license;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_description')) : ?>
							<td headers="torrentlist_header_description" class="list-description">
								<?php echo $torrent->description;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_size')) : ?>
							<td headers="torrentlist_header_size" class="list-size" style="white-space:nowrap;">
								<div class="text-right">
									<?php echo TrackerHelper::make_size($torrent->size);?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_created_time')) : ?>
							<td headers="torrentlist_header_created_time" class="list-created_time">
								<div class="text-right">
									<?php echo date('Y.m.d', strtotime($torrent->created_time));?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_seeders')) : ?>
							<td headers="torrentlist_header_seeders" class="list-seeders" style="text-align:center;">
								<div class="text-center middle">
									<?php echo $torrent->seeders;?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_leechers')) : ?>
							<td headers="torrentlist_header_leechers" class="list-leechers" style="text-align:center;">
								<div class="text-center middle">
									<?php echo $torrent->leechers;?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_completed')) : ?>
							<td headers="torrentlist_header_completed" class="list-completed" style="text-align:center;">
								<div class="text-center middle">
									<?php echo $torrent->completed;?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_uploader_name')) : ?>
							<td headers="torrentlist_header_uploader" class="list-uploader">
								<div class="text-center">
									<?php 
										if (($params->get('allow_upload_anonymous') == 0) || ($torrent->uploader_anonymous == 0) || ($torrent->uploader == $user->id)) echo $torrent->uploader_name;
										else echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
									?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_uploader_username')) : ?>
							<td headers="torrentlist_header_uploader" class="list-uploader">
								<div class="text-center">
									<?php 
										if (($params->get('allow_upload_anonymous') == 0) || ($torrent->uploader_anonymous == 0) || ($torrent->uploader == $user->id)) echo $torrent->uploader_username;
										else echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
									?>
								</div>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_number_files')) : ?>
							<td headers="torrentlist_header_number_files" class="list-number_files">
								<?php echo $torrent->number_files;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_forum_post')) : ?>
							<td headers="torrentlist_header_forum_post" class="list-forum_post">
								<?php echo $torrent->forum_post;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_info_post')) : ?>
							<td headers="torrentlist_header_info_post" class="list-info_post">
								<?php echo $torrent->info_post;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_download_multiplier')) : ?>
							<td headers="torrentlist_header_download_multiplier" class="list-download_multiplier">
								<?php echo $torrent->download_multiplier;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_upload_multiplier')) : ?>
							<td headers="torrentlist_header_upload_multiplier" class="list-upload_multiplier">
								<?php echo $torrent->upload_multiplier;?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('tl_torrent_thanks')) : ?>
							<td headers="torrentlist_header_torrent_thanks" class="list-torrent_thanks" style="text-align:center;">
								<div class="text-center middle">
									<?php echo $torrent->thanks;?>
								</div>
							</td>
						<?php endif; ?>
						<?php if (TrackerHelper::user_permissions('download_torrents', $user->id) && $params->get('tl_download_image')) : ?>
							<td headers="torrentlist_header_download_torrents" class="list-download_torrents">
								<div class="text-center middle">
									<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=torrent.download&id='.$torrent->fid); ?>">
										<?php echo TrackerHelper::downloadArrowType($torrent->seeders, $torrent->leechers); ?>
									</a>
								</div>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php 
			if ($params->get('enable_torrent_type') && ( $params->get('enable_torrent_type_new') || $params->get('enable_torrent_type_top') ||
				$params->get('enable_torrent_type_hot') || $params->get('enable_torrent_type_semifree') || $params->get('enable_torrent_type_free') ||
				$params->get('enable_download_images_legend'))) : ?>

			<table class="category table">
					<tr>
						<td><h2><?php echo JText::_( 'COM_TRACKER_LEGEND' );?>:</h2></td>
					</tr>
					<tr>
						<?php if ($params->get('enable_torrent_type') && ( $params->get('enable_torrent_type_new') || $params->get('enable_torrent_type_top') ||
								  $params->get('enable_torrent_type_hot') || $params->get('enable_torrent_type_semifree') || $params->get('enable_torrent_type_free'))) : ?>
							<td>
								<?php if ($params->get('enable_torrent_type_new')) : ?>
								<div>
									<img src="<?php echo JURI::base().$params->get('torrent_type_new_image');?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo JText::sprintf($params->get('torrent_type_new_text'), $params->get('torrent_type_new_value')); ?>
								</div>
								<?php endif; ?>
								<?php if ($params->get('enable_torrent_type_top')) : ?>
								<div>
									<img src="<?php echo JURI::base().$params->get('torrent_type_top_image');?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo JText::sprintf($params->get('torrent_type_top_text'), $params->get('torrent_type_top_value')); ?>
								</div>
								<?php endif; ?>
								<?php if ($params->get('enable_torrent_type_hot')) : ?>
								<div>
									<img src="<?php echo JURI::base().$params->get('torrent_type_hot_image');?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo JText::sprintf($params->get('torrent_type_hot_text'), $params->get('torrent_type_hot_value')); ?>
								</div>
								<?php endif; ?>
								<?php if ($params->get('enable_torrent_type_semifree')) : ?>
								<div>
									<img src="<?php echo JURI::base().$params->get('torrent_type_semifree_image');?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo JText::sprintf($params->get('torrent_type_semifree_text'), $params->get('torrent_type_semifree_value')); ?>
								</div>
								<?php endif; ?>
								<?php if ($params->get('enable_torrent_type_free')) : ?>
								<div>
									<img src="<?php echo JURI::base().$params->get('torrent_type_free_image');?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo $params->get('torrent_type_free_text');?>
								</div>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<?php if ($params->get('enable_download_images_legend')) : ?>
							<td>
								<div>
									<img src="<?php echo JURI::base().'components/com_tracker/assets/images/download_good.png';?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo JText::_( 'COM_TRACKER_TORRENT_DOWNLOAD_GOOD' );?>
								</div>
								<div>
									<img src="<?php echo JURI::base().'components/com_tracker/assets/images/download_medium.png';?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo JText::_( 'COM_TRACKER_TORRENT_DOWNLOAD_MEDIUM' );?>
								</div>
								<div>
									<img src="<?php echo JURI::base().'components/com_tracker/assets/images/download_bad.png';?>" border="0" />
									&nbsp;-&nbsp;
									<?php echo JText::_( 'COM_TRACKER_TORRENT_DOWNLOAD_BAD' );?>
								</div>
							</td>
						<?php endif; ?>
					</tr>
			</table>
		<?php endif; ?>

		<div>
			<input type="hidden" name="filter_order" value="" />
			<input type="hidden" name="filter_order_Dir" value="" />
			<input type="hidden" name="limitstart" value="" />
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>

		<div class="pagination pagination-centered">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>

	</form>
<?php endif;