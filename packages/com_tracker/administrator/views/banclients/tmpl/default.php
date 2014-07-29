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
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_tracker&task=banclients.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'banclientList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=banclients'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>
		<div class="filter-select fltrt">
			<label>
				<b><?php echo JText::_('COM_TRACKER_BANCLIENT_YOU_CAN_CHECK');?>&nbsp;<a href="https://wiki.theory.org/BitTorrentSpecification#peer_id" target="_blank"><?php echo JText::_('COM_TRACKER_BANCLIENT_HERE');?></a></b>
			</label>
		</div>
	
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

		<table class="table table-striped" id="banclientList">
			<thead>
				<tr>
                <th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

				<th class='nowrap left'>
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>

				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANCLIENT_PEER_ID', 'a.peer_id', $listDirn, $listOrder); ?>
				</th>

				<th>
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANCLIENT_PEER_DESCRIPTION', 'a.peer_description', $listDirn, $listOrder); ?>
				</th>

				<th>
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANCLIENT_COMMENT', 'a.comment', $listDirn, $listOrder); ?>
				</th>

				<th class="nowrap">
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_FIELD_CREATED_BY_LABEL', 'a.created_user_id', $listDirn, $listOrder); ?>
				</th>

				<th class="nowrap">
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_CREATED_DATE', 'a.created_time', $listDirn, $listOrder); ?>
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
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 8;
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

				<td>
				<?php
					if ($canEdit) echo "<a href=".JRoute::_('index.php?option=com_tracker&task=banclient.edit&id='.(int) $item->id).">".$this->escape($item->peer_id)."</a>";
					else echo $item->peer_id;
				?>
				</td>
				
				<td><?php echo $item->peer_description;?></td>

				<td><?php echo $item->comment;?></td>

				<td><?php echo $item->username;?></td>

				<td><?php echo $item->created_time;?></td>

				<?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'banclients.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>

				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<?php /*endif; */?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>