<?php
/**
 * @version			2.5.11-dev
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

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=groups'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
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
				<th width="1%" class='left' nowrap><?php echo JHtml::_('grid.sort',	'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?></th>

				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>

				<th width="10%"><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_NAME', 'a.name', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_VIEW_TORRENTS', 'a.view_torrents', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_EDIT_TORRENTS', 'a.edit_torrents', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_DELETE_TORRENTS', 'a.delete_torrents', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_UPLOAD_TORRENTS', 'a.upload_torrents', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_DOWNLOAD_TORRENTS', 'a.download_torrents', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_CAN_LEECH', 'a.can_leech', $listDirn, $listOrder); ?></th>

				<?php if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') {?>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_VIEW_COMMENTS', 'a.view_comments', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_WRITE_COMMENTS', 'a.write_comments', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_EDIT_COMMENTS', 'a.edit_comments', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_DELETE_COMMENTS', 'a.delete_comments', $listDirn, $listOrder); ?></th>

				<th class='middle'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_AUTOPUBLISH_COMMENTS', 'a.autopublish_comments', $listDirn, $listOrder); ?></th>

				<?php } ?>
				
				<?php if ($params->get('torrent_multiplier')) {?>
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_DOWNLOAD_MULTIPLIER', 'a.download_multiplier', $listDirn, $listOrder); ?></th>

				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_GROUP_UPLOAD_MULTIPLIER', 'a.upload_multiplier', $listDirn, $listOrder); ?></th>

				<?php } ?>

				<th width="10%">
					<?php echo JHtml::_('grid.sort',	'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
						<?php echo JHtml::_('grid.order',	$this->items, 'filesave.png', 'groups.saveorder'); ?>
					<?php endif; ?>
				</th>

				<th width="5%"><?php echo JHtml::_('grid.sort',	'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?></th>

			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php 
					$colspan = 12;
					if ($params->get('torrent_multiplier')) $colspan+= 2;
					if ($params->get('enable_comments')) $colspan+= 5;
					else echo '<td colspan="'.$colspan.'">';
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
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td width="1%" nowrap><?php echo $item->id;?></td>

				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>

				<td>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=group.edit&id='. (int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>
				</td>
				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->view_torrents, 'groups.view_torrents', 'groups.no_view_torrents'); ?></td>
				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->edit_torrents, 'groups.edit_torrents', 'groups.no_edit_torrents'); ?></td>
				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->delete_torrents, 'groups.delete_torrents', 'groups.no_delete_torrents'); ?></td>
				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->upload_torrents, 'groups.upload_torrents', 'groups.no_upload_torrents'); ?></td>
				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->download_torrents, 'groups.download_torrents', 'groups.no_download_torrents'); ?></td>
				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->can_leech, 'groups.can_leech', 'groups.no_can_leech'); ?></td>
				<?php if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') {?>
					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->view_comments, 'groups.view_comments', 'groups.no_view_comments'); ?></td>
					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->write_comments, 'groups.write_comments', 'groups.no_write_comments'); ?></td>
					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->edit_comments, 'groups.edit_comments', 'groups.no_edit_comments'); ?></td>
					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->delete_comments, 'groups.delete_comments', 'groups.no_delete_comments'); ?></td>
					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->autopublish_comments, 'groups.autopublish_comments', 'groups.no_autopublish_comments'); ?></td>
				<?php } ?>
				<?php if ($params->get('torrent_multiplier')) {?>
					<td align="center" nowrap><?php echo $item->download_multiplier;?>x</td>
					<td align="center" nowrap><?php echo $item->upload_multiplier;?>x</td>
				<?php } ?>

				<td class="order">
					<?php if ($canChange) : ?>
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, 1 == 1, 'groups.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (1 == 1), 'groups.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, (1 == 1), 'groups.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (1 == 1), 'groups.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>

				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->state, 'groups.state', 'groups.no_state'); ?></td>
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