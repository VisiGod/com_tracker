<?php
/**
 * @version			2.5.12-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

$user		= $this->user;
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';

$params = JComponentHelper::getParams( 'com_tracker' );
?>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=torrents'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_category" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_tracker'), 'value', 'text', $this->state->get('filter.category'));?>
			</select>
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class='left' nowrap><?php echo JHtml::_('grid.sort',	'JGRID_HEADING_ID', 'a.fid', $listDirn, $listOrder); ?></th>
				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
				<th class='left'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_NAME', 'a.name', $listDirn, $listOrder); ?></th>
				<th class='center'><?php echo JHtml::_('grid.sort',	'JCATEGORY', 'c.id', $listDirn, $listOrder); ?></th>
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_SIZE', 'a.size', $listDirn, $listOrder); ?></th>
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_UPLOADED', 'a.created_time', $listDirn, $listOrder); ?></th>
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_LEECHERS', 'a.leechers', $listDirn, $listOrder); ?></th>
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_SEEDERS', 'a.seeders', $listDirn, $listOrder); ?></th>
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_COMPLETED', 'a.completed', $listDirn, $listOrder); ?></th>
				<?php if ($params->get('torrent_multiplier')) {?>
					<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_DOWNLOAD_MULTIPLIER', 'a.download_multiplier', $listDirn, $listOrder); ?></th>
					<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_UPLOAD_MULTIPLIER', 'a.upload_multiplier', $listDirn, $listOrder); ?></th>
				<?php } ?>
				<th class='right'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_UPLOADER', 'a.uploader', $listDirn, $listOrder); ?></th>

				<th width="10%">
					<?php echo JHtml::_('grid.sort',	'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
						<?php echo JHtml::_('grid.order',	$this->items, 'filesave.png', 'group.saveorder'); ?>
					<?php endif; ?>
				</th>

				<th width="5%"><?php echo JHtml::_('grid.sort',	'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?></th>

			</tr>
		</thead>

		<tfoot>
			<tr>
				<?php 
					if ($params->get('torrent_multiplier')) echo '<td colspan="14">';
					else echo '<td colspan="12">';

					echo $this->pagination->getListFooter().'</td>';
				?>
			</tr>
		</tfoot>

		<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering	= ($listOrder == 'a.ordering');
				$canCreate	= $user->authorise('core.create',		'com_tracker');
				$canEdit	= $user->authorise('core.edit',			'com_tracker');
				$canCheckin	= $user->authorise('core.manage',		'com_tracker');
				$canChange	= $user->authorise('core.edit.state',	'com_tracker');

				$category_params = new JRegistry();
				$category_params->loadString($item->category_params);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td width="1%" nowrap><?php echo $item->fid;?></td>
				<td class="center" width="1%" nowrap><?php echo JHtml::_('grid.id', $i, $item->fid); ?></td>
				<td style="word-break: break-all;">
					<?php 
						if ($canEdit) echo "<a href=".JRoute::_('index.php?option=com_tracker&task=torrent.edit&fid='.(int) $item->fid).">".$this->escape($item->name)."</a>";
						else echo $this->escape($item->name);
					?>
				</td>

				<td width="1%" align="center" nowrap>
				<?php
					if(@is_array(getimagesize(JUri::root(true).'/'.$category_params->get('image')))) { 
						echo '<img id="image'.$item->fid.'" alt="'.$item->category.'" src="'.JUri::root(true).'/'.$category_params->get('image').'" width="'.$params->get('category_image_size').'" />';
					}
					else echo $item->category;
				?>
				</td>

				<td align="right" nowrap><?php echo TrackerHelper::make_size($item->size);?></td>
				<td align="right" nowrap><?php echo date('Y.m.d', strtotime($item->created_time));?></td>
				<td align="center" nowrap><?php echo $item->leechers;?></td>
				<td align="center" nowrap><?php echo $item->seeders;?></td>
				<td align="center" nowrap><?php echo $item->completed;?></td>

				<?php if ($params->get('torrent_multiplier')) {?>
					<td align="center" nowrap><?php echo $item->download_multiplier;?></td>
					<td align="center" nowrap><?php echo $item->upload_multiplier;?></td>
				<?php } ?>

				<td align="right" nowrap>
					<?php
						if ($canEdit) echo "<a href=".JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->uploader).">".$item->uploader."</a>";
						else echo $item->uploader;
					?>
				</td>
					
				<td class="order">
					<?php if ($canChange) : ?>
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, 1 == 1, 'torrents.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (1 == 1), 'torrents.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, (1 == 1), 'torrents.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (1 == 1), 'torrents.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>

				<?php if (isset($this->items[0]->state)) { ?>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'torrents.', $canChange, 'cb'); ?>
				</td>
				<?php } ?>
				<?php if (isset($this->items[0]->id)) { ?>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
								<?php } ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>