<?php
/**
 * @version			2.5.0
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

$params =& JComponentHelper::getParams( 'com_tracker' );
?>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=users'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_group" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_TRACKER_SELECT_GROUP');?></option>
				<?php echo JHtml::_('select.options', TrackerHelper::getGroups(), 'value', 'text', $this->state->get('filter.group'));?>
			</select>
			<select name="filter_country" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_TRACKER_SELECT_COUNTRY');?></option>
				<?php echo JHtml::_('select.options', TrackerHelper::getUsedCountries(), 'value', 'text', $this->state->get('filter.country'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class='left'><?php echo JHtml::_('grid.sort',	'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?></th>

				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>

				<th class='left'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_NAME', 'u.name', $listDirn, $listOrder); ?></th>
				
				<th class='left'><?php echo JHtml::_('grid.sort',	'JGLOBAL_USERNAME', 'u.username', $listDirn, $listOrder); ?></th>

				<th class='left'><?php echo JHtml::_('grid.sort',	'JGLOBAL_EMAIL', 'u.email', $listDirn, $listOrder); ?></th>

				<?php if ($params->get('enable_countries')) { ?>
					<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_COUNTRY', 'a.countryID', $listDirn, $listOrder); ?></th>
				<?php } ?>

				<th class='right'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_DOWNLOADED', 'a.downloaded', $listDirn, $listOrder); ?></th>

				<th class='right'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_UPLOADED', 'a.uploaded', $listDirn, $listOrder); ?></th>

				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_RATIO', 'ratio', $listDirn, $listOrder); ?></th>

				<?php if ($params->get('enable_donations')) { ?>
					<th class='right'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_DONATED', 'donated', $listDirn, $listOrder); ?></th>
				<?php } ?>

				<th class='right'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_GROUP', 'a.groupID', $listDirn, $listOrder); ?></th>

			<?php if ($params->get('torrent_multiplier')) {?>
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_DOWNLOAD_MULTIPLIER', 'a.download_multiplier', $listDirn, $listOrder); ?></th>
				
				<th class='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_UPLOAD_MULTIPLIER', 'a.upload_multiplier', $listDirn, $listOrder); ?></th>
			<?php } ?>

				<th width="5%"><?php echo JHtml::_('grid.sort',	'COM_TRACKER_USER_CAN_LEECH', 'a.can_leech', $listDirn, $listOrder); ?></th>

				<th width="5%"><?php echo JHtml::_('grid.sort', 'JENABLED', 'u.block', $listDirn, $listOrder); ?></th>
				

			</tr>
		</thead>
		<tfoot>
			<tr>
			<?php 
				$colspan = 15;
				if ($params->get('enable_countries')) $colspan += 1;
				if ($params->get('enable_donations')) $colspan += 1;
				if ($params->get('torrent_multiplier')) $colspan += 2;
			?>
				<td colspan="<?php echo $colspan; ?>">
			
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
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

				<td class="center" width="1%" nowrap><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>

				<td>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=user.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>
				</td>

				<td>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=user.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->username); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->username); ?>
				<?php endif; ?>
				</td>

				<td nowrap><?php echo $item->email;?></td>

				<?php if ($params->get('enable_countries')) { ?>
					<td align="center" nowrap>
						<?php if ($item->countryName) {?>
							<div class="icon"><?php echo '<img id="flag'.$item->countryImage.'" alt="'.$item->countryName.'" src="'.JUri::root().$item->countryImage.'" width="36" />'; ?></div>
						<?php } else echo JText::_( 'COM_TRACKER_NONE' );?>
					</td>
				<?php } ?>

				<td align="right" nowrap><?php echo TrackerHelper::make_size($item->downloaded); ?></td>

				<td align="right" nowrap>
					<?php
						if ($params->get('enable_donations') && $item->credited > 0) {
								echo TrackerHelper::make_size($item->uploaded + ($item->credited * 1073741824));
								echo ' - ('.($item->credited).')';
						} else echo TrackerHelper::make_size($item->uploaded);
					?>
				</td>

				<td align="center" nowrap>
					<?php
						if ($params->get('enable_donations') &&  $item->credited > 0) echo TrackerHelper::make_ratio($item->downloaded,($item->uploaded + ($item->credited * 1073741824)));
						else echo TrackerHelper::make_ratio($item->downloaded,$item->uploaded);
           ?>
				</td>

				<?php if ($params->get('enable_donations')) { ?>
					<td align="center" nowrap>
						<?php 
							if ($item->donated > 0) echo '$'.$item->donated;
							else echo JText::_( 'COM_TRACKER_NOTHING' );;
						?>
					</td>
				<?php } ?>

				<td align="center" nowrap><?php echo $item->group_name;?></td>

				<?php if ($params->get('torrent_multiplier')) {?>
				<td align="center" nowrap><?php echo $item->download_multiplier.'x';?></td>

				<td align="center" nowrap><?php echo $item->upload_multiplier.'x';?></td>
				<?php } ?>

				<td align="center" nowrap>
					<?php echo JHtml::_('grid.boolean', $i, $item->can_leech, 'users.leech', 'users.unleech'); ?>
				</td>
				
				<td class="center"><?php echo JHtml::_('grid.boolean', $i, !$item->block); ?></td>
				
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