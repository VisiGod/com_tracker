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
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();

$app = JFactory::getApplication();
$params = JComponentHelper::getParams( 'com_tracker' );
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'group.cancel' || document.formvalidator.isValid(document.id('group-form'))) {
			Joomla.submitform(task, document.getElementById('group-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="group-form" class="form-validate form-horizontal">
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'groupTorrent', array('active' => 'group')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'groupTorrent', 'group', JText::_('COM_TRACKER_GROUP', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('wait_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('wait_time'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('peer_limit'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('peer_limit'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('torrent_limit'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('torrent_limit'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('minimum_ratio'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('minimum_ratio'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('download_multiplier'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('download_multiplier'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('upload_multiplier'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('upload_multiplier'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'groupTorrent', 'torrents', JText::_('COM_TRACKER_TORRENTS', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('view_torrents'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('view_torrents'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('edit_torrents'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('edit_torrents'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('delete_torrents'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('delete_torrents'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('upload_torrents'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('upload_torrents'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('download_torrents'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('download_torrents'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('can_leech'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('can_leech'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') {?>
			<?php echo JHtml::_('bootstrap.addTab', 'groupTorrent', 'comments', JText::_('COM_TRACKER_COMMENTS', true)); ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('view_comments'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('view_comments'); ?></div>

					<div class="control-label"><?php echo $this->form->getLabel('write_comments'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('write_comments'); ?></div>

					<div class="control-label"><?php echo $this->form->getLabel('edit_comments'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('edit_comments'); ?></div>

					<div class="control-label"><?php echo $this->form->getLabel('delete_comments'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('delete_comments'); ?></div>

					<div class="control-label"><?php echo $this->form->getLabel('autopublish_comments'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('autopublish_comments'); ?></div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php } ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>