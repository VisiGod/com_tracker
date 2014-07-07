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

jimport('joomla.application.component.helper');

if ($this->user->guest && $this->params->get('allow_guest')) :
	$this->user->id = $this->params->get('guest_user');
endif;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
// Show extra page text (defined in menu)
if ($this->params->get('menu_text')) echo '<h2>'.$this->escape($this->params->get('menu-anchor_title')).'</h2>';

$torrentType = array(
		JHtml::_('select.option', '1', JText::_('COM_TRACKER_SELECT_TORRENTS_WITH_PEERS') ),
		JHtml::_('select.option', '2', JText::_('COM_TRACKER_SELECT_TORRENTS_WITH_SEEDERS') ),
		JHtml::_('select.option', '3', JText::_('COM_TRACKER_SELECT_TORRENTS_ONLY_LEECHERS') ),
		JHtml::_('select.option', '4', JText::_('COM_TRACKER_SELECT_TORRENTS_DEAD') ),
);

?>
<form action="<?php echo JRoute::_('index.php?view=torrents'); ?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<?php if ($this->params->get('tl_search_bar')) { ?>
		<div class="filter-search fltlft" style="width:50%; float:left;">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<?php } ?>

		<?php if ($this->params->get('tl_category_dropdown')) { ?>
		<div style="float: right;">
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_tracker'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
		</div>
		<?php } ?>

		<?php if ($this->params->get('"tl_license_dropdown"')) { ?>
		<div style="float: right; margin-right: 3px;">
			<select name="filter_license_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_TRACKER_SELECT_LICENSE');?></option>
				<?php echo JHtml::_('select.options', TrackerHelper::SelectList('licenses', 'id', 'shortname', '1'), 'value', 'text', $this->state->get('filter.license_id')); ?>
			</select>
		</div>
		<?php } ?>

		<?php if ($this->params->get('tl_torrent_status_dropdown')) { ?>
		<div style="float: right;">
			<select name="filter_torrent_status" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_TRACKER_SELECT_ALL_TORRENTS');?></option>
				<?php echo JHtml::_('select.options', $torrentType, 'value', 'text', $this->state->get('filter.torrent_status'));?>
			</select>
		</div>
		<?php } ?>
	</fieldset>
	<div class="clr"></div>

	<table class="adminlist table table-hover table-striped" style="width:100%;">
		<thead>
			<tr>
				<?php if ($this->params->get('tl_name')) { ?><th width="93%" align='center'><?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_NAME', 't.name', $listDirn, $listOrder); ?></th><?php } ?>
				<?php if ($this->params->get('tl_alias')) { ?><th width="93%" align='center'><?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_ALIAS', 't.alias', $listDirn, $listOrder); ?></th><?php } ?>
				<?php if ($this->params->get('enable_torrent_type')) { ?><th width="1%">&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_info_hash')) { ?><th width="1%" align='center' nowrap><?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_INFO_HASH', 't.info_hash', $listDirn, $listOrder); ?></th><?php } ?>
				<?php if ($this->params->get('tl_filename')) { ?><th width="1%" align='center' nowrap><?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_FILENAME', 't.filename', $listDirn, $listOrder); ?></th><?php } ?>
				<?php if ($this->params->get('tl_category')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort',	'JCATEGORY', 'c.title', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_license')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_LICENSE', 'torrent_license', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_description')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JText::_( 'COM_TRACKER_TORRENT_DESCRIPTION' ); ?></th><?php } ?>
				<?php if ($this->params->get('tl_size')) { ?><th width="1%" align='center' nowrap><?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_SIZE', 't.size', $listDirn, $listOrder); ?></th><?php } ?>
				<?php if ($this->params->get('tl_created_time')) { ?><th width="1%" align='center' nowrap><?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_CREATED_TIME', 't.created_time', $listDirn, $listOrder); ?></th><?php } ?>
				<?php if ($this->params->get('tl_leechers')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_LEECHERS_SMALL', 't.leechers', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_seeders')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_SEEDERS_SMALL', 't.seeders', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_completed')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_COMPLETED_SMALL', 't.completed', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_uploader_name')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_UPLOADER', 'uploader_name', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_uploader_username')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_UPLOADER', 'uploader_username', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_number_files')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_NUMBER_FILES', 't.number_files', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_forum_post')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_FORUM_POST', 't.forum_post', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_info_post')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_TORRENT_INFO_POST', 't.info_post', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_download_multiplier')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_DOWNLOAD_MULTIPLIER', 't.download_multiplier', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if ($this->params->get('tl_upload_multiplier')) { ?><th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort', 'COM_TRACKER_UPLOAD_MULTIPLIER', 't.upload_multiplier', $listDirn, $listOrder); ?>&nbsp;</th><?php } ?>
				<?php if (TrackerHelper::user_permissions('download_torrents', $this->user->id) && $this->params->get('tl_download_image')) { ?><th width="1%" class='align'><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_IMAGE_TEXT' ); ?></th><?php } ?>
			</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $this->user->authorise('core.create',		'com_tracker');
			$canEdit	= $this->user->authorise('core.edit',			'com_tracker');
			$canCheckin	= $this->user->authorise('core.manage',		'com_tracker');
			$canChange	= $this->user->authorise('core.edit.state',	'com_tracker');

			$category_params = new JRegistry();
			$category_params->loadString($item->category_params);
			?>
			<tr class="row<?php echo $i % 2; ?>" style="width:90%;">
				<?php if ($this->params->get('tl_name')) { ?><td width="92%"><a href="<?php echo JRoute::_("index.php?view=torrent&id=".(int)$item->fid); ?>"><?php echo $this->escape(str_replace('_', ' ', $item->name)); ?></a></td><?php } ?>
				<?php if ($this->params->get('tl_alias')) { ?><td width="92%"><a href="<?php echo JRoute::_("index.php?view=torrent&id=".(int)$item->fid); ?>"><?php echo $item->alias; ?></a></td><?php } ?>
				<?php if ($this->params->get('enable_torrent_type')) {?><td width="1%" align="right" nowrap><?php echo TrackerHelper::checkTorrentType((int)$item->fid);?></td><?php } ?>
				<?php if ($this->params->get('tl_info_hash')) { ?><td width="1%" align="center" nowrap><?php echo bin2hex($item->info_hash); ?></td><?php } ?>
				<?php if ($this->params->get('tl_filename')) { ?><td width="1%" align="center" nowrap><?php echo $item->filename; ?></td><?php } ?>
				
				<?php if ($this->params->get('tl_category')) { ?>
					<td width="1%" align="center" nowrap>
						<?php if (is_file($_SERVER['DOCUMENT_ROOT'].JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image')) && $this->params->get('use_image_file')) { ?>
							<img id="image<?php echo $item->fid;?>" alt="<?php echo $item->torrent_category; ?>" src="<?php echo JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'); ?>" width="36" />
						<?php } else echo '&nbsp;'.$item->torrent_category.'&nbsp;'; ?>
					</td>
				<?php } ?>

				<?php if ($this->params->get('tl_license')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->torrent_license;?></td><?php } ?>
				<?php if ($this->params->get('tl_description')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->description;?></td><?php } ?>

				<?php if ($this->params->get('tl_size')) { ?><td width="1%" align="right" nowrap>&nbsp;<?php echo TrackerHelper::make_size($item->size);?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_created_time')) { ?><td width="1%" align="right" nowrap>&nbsp;<?php echo date('Y.m.d', strtotime($item->created_time));?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_leechers')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->leechers;?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_seeders')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->seeders;?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_completed')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->completed;?>&nbsp;</td><?php } ?>

				<?php if ($this->params->get('tl_uploader_name') || $this->params->get('tl_uploader_username')) { ?>
					<td align="right" nowrap>&nbsp;
					<?php 
						if (($this->params->get('allow_upload_anonymous') == 0) || ($item->uploader_anonymous == 0) || ($item->uploader == $this->user->id)) {
							if ($this->params->get('tl_uploader_username')) echo $item->uploader_username;
							else echo $item->uploader_name;
						}
						else echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
					?>&nbsp;
					</td>
				<?php } ?>

				<?php if ($this->params->get('tl_number_files')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->number_files;?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_forum_post')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->forum_post;?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_info_post')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->info_post;?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_download_multiplier')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->download_multiplier;?>&nbsp;</td><?php } ?>
				<?php if ($this->params->get('tl_upload_multiplier')) { ?><td width="1%" align="center" nowrap>&nbsp;<?php echo $item->upload_multiplier;?>&nbsp;</td><?php } ?>

				<?php if (TrackerHelper::user_permissions('download_torrents', $this->user->id) && $this->params->get('tl_download_image')) { ?>
					<td width="1%" align="center">
						<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=torrent.download&id='.$item->fid); ?>">
							<img src="<?php echo JURI::base();?>components/com_tracker/assets/images/download.gif" alt="<?php echo JText::_( 'TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ); ?>" border="0" />
						</a>
					</td>
				<?php } ?>

				
				<?php
				// experiment for Psylo to have number of thanks in torrent listing 
				if ($this->params->get('enable_thankyou')) {
					//if (!$item->thanks) $item->thanks = 0;
					//echo '<td width="1%" align="center">'.$item->thanks.'</td>';
				}
				?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($this->params->get('enable_torrent_type') && (
			$this->params->get('enable_torrent_type_new') ||
			$this->params->get('enable_torrent_type_top') ||
			$this->params->get('enable_torrent_type_hot') ||
			$this->params->get('enable_torrent_type_semifree') ||
			$this->params->get('enable_torrent_type_free') 
			)) { ?>
	<br />
	<div>
		<div><h2><?php echo JText::_( 'COM_TRACKER_LEGEND' );?>:</h2></div>
		<br />
		<?php if ($this->params->get('enable_torrent_type_new')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_new_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_new_text'), $this->params->get('torrent_type_new_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_top')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_top_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_top_text'), $this->params->get('torrent_type_top_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_hot')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_hot_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_hot_text'), $this->params->get('torrent_type_hot_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_semifree')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_semifree_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_semifree_text'), $this->params->get('torrent_type_semifree_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_free')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_free_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo $this->params->get('torrent_type_free_text');?>
		</div>
		<?php } ?>
		
	</div>
	<?php } ?>

	<div class="pagination">
			<?php echo $this->pagination->getLimitBox(); ?>
			<?php echo $this->pagination->getPagesCounter(); ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
	</div>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>