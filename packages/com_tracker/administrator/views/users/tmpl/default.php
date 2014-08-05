<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$component = JComponentHelper::getComponent('com_tracker');
$params = $component->params;

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_tracker');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_tracker&task=users.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'userList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=users'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>

		<table class="table table-striped" id="userList">
			<thead>
				<tr>
                <th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

				<th class='nowrap left'>
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>
				
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_NAME', 'u.name', $listDirn, $listOrder); ?>
				</th>

				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_USERNAME', 'u.username', $listDirn, $listOrder); ?>
				</th>

				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_EMAIL', 'u.email', $listDirn, $listOrder); ?>
				</th>

				<?php if ($params->get('enable_countries')) { ?>
					<th width="10%" class="center nowrap">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_USER_COUNTRY', 'a.countryID', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>

				<th width="10%" class="center nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_TRACKER_USER_DOWNLOADED', 'a.downloaded', $listDirn, $listOrder); ?>
				</th>

				<th width="10%" class="center nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_TRACKER_USER_UPLOADED', 'a.uploaded', $listDirn, $listOrder); ?>
				</th>

				<th width="10%" class="center nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_TRACKER_USER_RATIO', 'ratio', $listDirn, $listOrder); ?>
				</th>

				<?php if ($params->get('enable_donations')) { ?>
					<th width="10%" class="center nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_TRACKER_USER_DONATED', 'donated', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>
				
				<th width="10%" class="center nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_TRACKER_USER_GROUP', 'a.groupID', $listDirn, $listOrder); ?>
				</th>

				<?php if ($params->get('torrent_multiplier')) { ?>
					<th width="10%" class="center">
						<?php echo JHtml::_('grid.sort', 'COM_TRACKER_DOWNLOAD_MULTIPLIER', 'a.download_multiplier', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="center">
						<?php echo JHtml::_('grid.sort', 'COM_TRACKER_UPLOAD_MULTIPLIER', 'a.upload_multiplier', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>						

				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_TRACKER_USER_CAN_LEECH', 'a.can_leech', $listDirn, $listOrder); ?>
				</th>

				<?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>

				</tr>
			</thead>
			<tfoot>
                <?php 
                if(isset($this->items[0])) {
                    $colspan = count(get_object_vars($this->items[0]));
                } else {
                    $colspan = 14;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_tracker');
                $canEdit	= $user->authorise('core.edit',			'com_tracker');
                $canCheckin	= $user->authorise('core.manage',		'com_tracker');
                $canChange	= $user->authorise('core.edit.state',	'com_tracker');
                
				?>
				<tr class="row<?php echo $i % 2; ?>">

	                <td class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
                    
					<td><?php echo $item->id; ?></td>

					<td class="nowrap">
					<?php
						if ($canEdit) echo "<a href=".JRoute::_('index.php?option=com_tracker&task=user.edit&id='.(int) $item->id).">".$this->escape($item->name)."</a>";
						else echo $item->name;
					?>
					</td>
				
					<td><?php echo $item->username; ?></td>
				
					<td><?php echo $item->email; ?></td>
				
					<?php if ($params->get('enable_countries')) { ?>
						<td class="center" nowrap>
							<?php if ($item->countryName) {?>
								<div class="icon"><?php echo '<img id="flag'.$item->countryImage.'" alt="'.$item->countryName.'" src="'.JUri::root().$item->countryImage.'" width="36" />'; ?></div>
							<?php } else echo JText::_( 'COM_TRACKER_NONE' );?>
						</td>
					<?php } ?>
				
					<td class="center nowrap">
						<?php echo TrackerHelper::make_size($item->downloaded); ?>
					</td>

					<td class="center nowrap">
						<?php
							if ($params->get('enable_donations') && $item->credited > 0) {
								echo TrackerHelper::make_size($item->uploaded + ($item->credited * 1073741824));
								echo ' - ('.($item->credited).')';
							} else echo TrackerHelper::make_size($item->uploaded);
						?>
					</td>
				
					<td class="center nowrap">
						<?php
							if ($params->get('enable_donations') &&  $item->credited > 0) echo TrackerHelper::make_ratio($item->downloaded,($item->uploaded + ($item->credited * 1073741824)));
							else echo TrackerHelper::make_ratio($item->downloaded,$item->uploaded);
						?>
					</td>

					<?php if ($params->get('enable_donations')) { ?>
						<td class="center nowrap">
							<?php 
								if ($item->donated > 0) echo '$'.$item->donated;
								else echo JText::_( 'COM_TRACKER_NOTHING' );;
							?>
						</td>
					<?php } ?>
					
					<td class="center">
						<?php echo $item->group_name; ?>
					</td>
				
					<?php if ($params->get('torrent_multiplier')) {?>
						<td class="center">
							<?php echo $item->download_multiplier; ?>
						</td>
						<td class="center">
							<?php echo $item->upload_multiplier; ?>
						</td>
					<?php } ?>

					<td class="center">
						<?php echo JHtml::_('grid.boolean', $i, $item->can_leech, 'users.leech', 'users.unleech'); ?>
					</td>

					<td class="nowrap center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'users.', $canChange, 'cb'); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
