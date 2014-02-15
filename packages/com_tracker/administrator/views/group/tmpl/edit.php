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

$params = JComponentHelper::getParams( 'com_tracker' );
?>
<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="group-form" class="form-validate">
	<div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php	echo JText::_('COM_TRACKER_GROUP');	?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('name'); ?><?php echo $this->form->getInput('name'); ?></li>

				<li><?php echo $this->form->getLabel('wait_time'); ?><?php echo $this->form->getInput('wait_time'); ?></li>

				<li><?php echo $this->form->getLabel('peer_limit'); ?><?php echo $this->form->getInput('peer_limit'); ?></li>

				<li><?php echo $this->form->getLabel('torrent_limit'); ?><?php echo $this->form->getInput('torrent_limit'); ?></li>

				<li><?php echo $this->form->getLabel('minimum_ratio'); ?><?php echo $this->form->getInput('minimum_ratio'); ?></li>

				<li><?php echo $this->form->getLabel('download_multiplier'); ?><?php echo $this->form->getInput('download_multiplier'); ?></li>

				<li><?php echo $this->form->getLabel('upload_multiplier'); ?><?php echo $this->form->getInput('upload_multiplier'); ?></li>
			</ul>
		</fieldset>
	</div>

	<div class="width-30 fltlft">
		<fieldset class="adminform">
			<legend><?php	echo JText::_('COM_TRACKER_TORRENTS');	?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('view_torrents'); ?><?php echo $this->form->getInput('view_torrents'); ?></li>

				<li><?php echo $this->form->getLabel('edit_torrents'); ?><?php echo $this->form->getInput('edit_torrents'); ?></li>

				<li><?php echo $this->form->getLabel('delete_torrents'); ?><?php echo $this->form->getInput('delete_torrents'); ?></li>

				<li><?php echo $this->form->getLabel('upload_torrents'); ?><?php echo $this->form->getInput('upload_torrents'); ?></li>

				<li><?php echo $this->form->getLabel('download_torrents'); ?><?php echo $this->form->getInput('download_torrents'); ?></li>

				<li><?php echo $this->form->getLabel('can_leech'); ?><?php echo $this->form->getInput('can_leech'); ?></li>
			</ul>
		</fieldset>
	</div>
	
	<?php if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') {?>
	<div class="width-30 fltlft">
		<fieldset class="adminform">
			<legend><?php	echo JText::_('COM_TRACKER_COMMENTS');	?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('view_comments'); ?><?php echo $this->form->getInput('view_comments'); ?></li>

				<li><?php echo $this->form->getLabel('write_comments'); ?><?php echo $this->form->getInput('write_comments'); ?></li>

				<li><?php echo $this->form->getLabel('edit_comments'); ?><?php echo $this->form->getInput('edit_comments'); ?></li>

				<li><?php echo $this->form->getLabel('delete_comments'); ?><?php echo $this->form->getInput('delete_comments'); ?></li>

				<li><?php echo $this->form->getLabel('autopublish_comments'); ?><?php echo $this->form->getInput('autopublish_comments'); ?></li>
			</ul>
		</fieldset>
	</div>
	<?php } ?>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>