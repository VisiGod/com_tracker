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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = JComponentHelper::getParams( 'com_tracker' );

$doc = JFactory::getDocument();
$doc->addScript("http://code.jquery.com/jquery-latest.js");
$style = '.hide { display:none; }';
$doc->addStyleDeclaration( $style );

?>
<script>
$(document).ready(function(){
	function default_dropdown() {
		if ($("#default_image_type").val() == "1") $("#image_file_field").show();
		if ($("#default_image_type").val() == "2") $("#image_file_link").show();
	}
	default_dropdown();
	
	$("#default_image_type").change(function(){
		if ($(this).val() == "0" ) {
			$("#image_file_field").hide();
			$("#image_file_link").hide();
		}
		if ($(this).val() == "1" ) {
			$("#image_file_field").show();
			$("#image_file_link").hide();
		}
		if ($(this).val() == "2" ) {
			$("#image_file_link").show();
			$("#image_file_field").hide();
		}
    });
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&fid='.(int) $this->item->fid); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="user-form" class="form-validate">
	<div class="width-100 fltlft">
		<div class="width-32 fltlft">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_TRACKER_TORRENT_BASIC_INFO'); ?></legend>
				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('name').$this->form->getInput('name'); ?></li>
					
					<li><?php echo $this->form->getLabel('alias').$this->form->getInput('alias'); ?></li>
					
					<li><?php
							echo $this->form->getLabel('filename');
							// Removes the extension from the filename. We'll add it in the end
							$temp = explode( '.', $this->form->getValue('filename') );
							$ext = array_pop( $temp );
							$this->form->setValue('filename', null, implode( '.', $temp ));
							echo $this->form->getInput('filename');
						?>
					</li>

					<li><?php echo $this->form->getLabel('categoryID').$this->form->getInput('categoryID'); ?></li>

					<li><?php echo $this->form->getLabel('uploader').$this->form->getInput('uploader'); ?></li>

					<?php if ($params->get('allow_upload_anonymous') == 1) { ?>
						<li><?php echo $this->form->getLabel('uploader_anonymous').$this->form->getInput('uploader_anonymous'); ?></li>
					<?php } ?>

				</ul>
			</fieldset>
		</div>
	
		<div class="width-67 fltrgt">
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
						<li><?php
								echo $this->form->getLabel('image_file');
								
								$image_type = array(0 => JText::_('COM_TRACKER_EDIT_IMAGE_KEEP_DEFAULT'), 1 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_FILE'), 2 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_LINK'), 3 => JText::_('COM_TRACKER_EDIT_IMAGE_REMOVE_PREVIOUS_IMAGE'));
								$options = array();
								foreach($image_type as $key=>$value) :
									$options[] = JHTML::_('select.option', $key, $value);
								endforeach;
								echo JHTML::_('select.genericlist', $options, 'default_image_type', 'class="inputbox"', 'value', 'text', 0);
								
								if ($params->get('image_type') == 1 || $params->get('image_type') == 0) {
									if ($params->get('image_type') == 0) echo '<span class="hide" id="image_file_field">&nbsp;&nbsp;&nbsp;';
										echo '<span style="float: left;padding-top: 6px;">'.JText::_( 'COM_TRACKER_TORRENT_IMAGE_FILE' ).':</span>';
										echo '<div style="float:left;"><input type="file" name="image_file" id="image_file" value="" class="inputbox" size="50" /></div>';
										echo '<div class="clear"></div>';
										if ($params->get('image_type') == 0) echo '</span>';
									}
								
									if ($params->get('image_type') == 2 || $params->get('image_type') == 0) {
										if ($params->get('image_type') == 0) echo '<span class="hide" id="image_file_link">&nbsp;&nbsp;&nbsp;';
										echo '<span style="float: left;padding-top: 6px;">'.JText::_( 'COM_TRACKER_TORRENT_IMAGE_LINK' ).':</span>';
										echo '<div style="float:left;"><input type="text" name="image_file" id="image_file" value="'.$this->form->getValue('image_file').'" class="inputbox" size="50" /></div>';
										echo '<div class="clear"></div>';
										if ($params->get('image_type') == 0) echo '</span>';
									} ?>
						</li>
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

	<?php if ($params->get('torrent_tags') == 1) { ?>
	<div class="width-100 fltrgt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKER_TORRENT_TAGS_EDIT'); ?></legend>
			<ul class="adminformlist">			
				<li><?php echo $this->form->getInput('tags'); ?></li>
			</ul>
		</fieldset>
	</div>
	<?php } ?>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="old_filename" value="<?php echo $this->form->getValue('filename'); ?>" />
	<input type="hidden" name="fid" value="<?php echo $this->form->getValue('fid'); ?>" />
	<input type="hidden" name="info_hash" value="<?php echo bin2hex($this->form->getValue('info_hash')); ?>" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>
