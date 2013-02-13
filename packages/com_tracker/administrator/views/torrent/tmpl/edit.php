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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params =& JComponentHelper::getParams( 'com_tracker' );

?>

<form	action="<?php	echo JRoute::_('index.php?option=com_tracker&layout=edit&fid='.(int)	$this->item->fid);	?>"	method="post"	name="adminForm" id="user-form"	class="form-validate">
	<div class="width-100 fltlft">

		<div class="width-40 fltlft">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_TRACKER_TORRENT_BASIC_INFO'); ?></legend>
				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('name').$this->form->getInput('name'); ?></li>
					
					<li><?php echo $this->form->getLabel('alias').$this->form->getInput('alias'); ?></li>
					
					<li><?php echo $this->form->getLabel('filename').$this->form->getInput('filename'); ?></li>

					<li><?php echo $this->form->getLabel('categoryID').$this->form->getInput('categoryID'); ?></li>

					<li><?php echo $this->form->getLabel('uploader').$this->form->getInput('uploader'); ?></li>

					<?php if ($params->get('allow_upload_anonymous') == 1) { ?>
						<li><?php echo $this->form->getLabel('uploader_anonymous').$this->form->getInput('uploader_anonymous'); ?></li>
					<?php } ?>

				</ul>
			</fieldset>
		</div>
	
		<div class="width-59 fltrgt">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_TRACKER_TORRENT_EXTRA_INFO'); ?></legend>
				<ul class="adminformlist">
					<?php if ($params->get('forum_post_id') == 1) { ?>
						<li><?php echo $this->form->getLabel('forum_post').$this->form->getInput('forum_post'); ?></li>
					<?php } ?>

					<?php if ($params->get('torrent_information') == 1) { ?>
						<li><?php echo $this->form->getLabel('info_post').$this->form->getInput('info_post'); ?></li>
					<?php } ?>

					<?php if ($params->get('torrent_multiplier') == 1) { ?>
						<li><?php echo $this->form->getLabel('download_multiplier').$this->form->getInput('download_multiplier');?></li>

						<li><?php echo $this->form->getLabel('upload_multiplier').$this->form->getInput('upload_multiplier');?></li>
					<?php } ?>

					<?php if ($params->get('enable_licenses') == 1) { ?>
						<li><?php echo $this->form->getLabel('licenseID').$this->form->getInput('licenseID'); ?></li>
					<?php } ?>

					<?php if ($params->get('use_image_file') == 1) { ?>
						<li><?php echo $this->form->getLabel('image_file').$this->form->getInput('image_file'); ?></li>
					<?php } ?>

				</ul>
			</fieldset>
		</div>

	</div>

	<div class="width-100 fltrgt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKER_TORRENT_DESCRIPTION'); ?></legend>
			<ul class="adminformlist">			
				<li><?php echo $this->form->getInput('description'); ?></li>
			</ul>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>
