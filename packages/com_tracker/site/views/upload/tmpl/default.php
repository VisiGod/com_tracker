<?php
/**
 * @version			2.5.12-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$params = JComponentHelper::getParams( 'com_tracker' );
jimport( 'joomla.form.form' );

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addScript("http://code.jquery.com/jquery-latest.js");
$style = '.hide { display:none; }';
$doc->addStyleDeclaration( $style );

?>
<script>
$(document).ready(function(){
	$("#jform_image_type").change(function(){
		if ($(this).val() == "1" ) {
			$("#image_file_field").show();
			$("#image_file_link").hide();
		} else if ($(this).val() == "2" ) {
			$("#image_file_link").show();
			$("#image_file_field").hide();
		} else {
			$("#image_file_link").hide();
			$("#image_file_field").hide();
		}
    });
});
</script>

<style type="text/css">.toggle-editor{display:none;}</style>
<div class="upload-form">
	<form id="upload-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" enctype="multipart/form-data" class="form-validate">
		<div>
			<div>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('filename'); ?>:</span>
				<span style="width:98%;"><?php echo $this->form->getInput('filename'); ?></span>
			</div>
			<div style="clear: both;"><br /></div>
			
			<div>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('name');?>:</span>
				<span style="width:98%;"><?php echo $this->form->getInput('name'); ?></span>
			</div>
			<div style="clear: both;"><br /></div>
			
			<div>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('categoryID');?>:</span>
				<span style="width:98%;"><?php echo $this->form->getInput('categoryID'); ?></span>
				<?php if ($params->get('enable_licenses') == 1) { ?>
				<span>&nbsp;&nbsp;&nbsp;</span>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('licenseID');?>:</span>
				<span style="width:98%;"><?php echo $this->form->getInput('licenseID'); ?></span>
				<?php } ?>
				<?php if ($params->get('allow_upload_anonymous') == 1) { ?>
				<span>&nbsp;&nbsp;&nbsp;</span>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('uploader_anonymous');?>:</span>
				<span style="width:98%;"><?php echo $this->form->getInput('uploader_anonymous'); ?></span>
				<?php } ?>
			</div>
			<div style="clear: both;"><br /></div>

			<?php if ($params->get('use_image_file') == 1) { ?>
			<div>
			<?php if ($params->get('image_type') == 0) { ?>
				<div style="float: left;">
					<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_('COM_TRACKER_TORRENT_IMAGE'); ?> :</span>
					<span style="width:1%; wrap:nowrap; align:left;"><?php echo $this->form->getInput('image_type'); ?></span>
				</div>
			<?php } ?>
			
			<?php if ($params->get('image_type') == 1 || $params->get('image_type') == 0) { ?>
				<?php if ($params->get('image_type') == 0) echo '<div class="hide" id="image_file_field" style="float: left;"><span>&nbsp;&nbsp;&nbsp;</span>'; ?>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('image_file');?>:</span>
				<span style="width:1%; wrap:nowrap; align:left;"><?php echo $this->form->getInput('image_file'); ?></span>
				<?php if ($params->get('image_type') == 0) echo '</div>'; ?>
			<?php } ?>
			
			<?php if ($params->get('image_type') == 2 || $params->get('image_type') == 0) { ?>
				<?php if ($params->get('image_type') == 0) echo '<div class="hide" id="image_file_link" style="float: left;"><span>&nbsp;&nbsp;&nbsp;</span>'; ?>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('image_link');?>:</span>
				<span style="width:1%; wrap:nowrap; align:left;"><?php echo $this->form->getInput('image_link'); ?></span>
				<?php if ($params->get('image_type') == 0) echo '</div>'; ?>
			<?php } ?>

			</div>
			<div style="clear: both;"><br /></div>
			<?php } ?>
			
			<?php if ($params->get('forum_post_id') == 1 || $params->get('torrent_information') == 1) { ?>
			<div>
				<?php if ($params->get('forum_post_id') == 1) { ?>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('forum_post'); ?> :</span>
				<span style="width:1%; wrap:nowrap; align:left;"><?php echo $this->form->getInput('forum_post'); ?></span>
				<?php } ?>
				<?php if ($params->get('torrent_information') == 1) { ?>
				<?php if ($params->get('forum_post_id') == 1) echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>'; ?>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('info_post'); ?> :</span>
				<span style="width:1%; wrap:nowrap; align:left;"><?php echo $this->form->getInput('info_post'); ?></span>
				<?php } ?>
			</div>
			<div style="clear: both;"><br /></div>
			<?php } ?>

			<div>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('description'); ?> :</span>
				<span style="width:1%; wrap:nowrap; align:left;"><?php echo $this->form->getInput('description'); ?></span>
			</div>
			<div style="clear: both;"><br /></div>

			<?php if ($params->get('torrent_tags') == 1) { ?>
			<div>
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo $this->form->getLabel('tags'); ?> :</span>
				<span style="width:1%; wrap:nowrap; align:left; display:inline-block; vertical-align:top"><?php echo $this->form->getInput('tags'); ?></span>
			</div>
			<?php } ?>
		</div>

		<div style="float: right;">
			<button class="button validate" type="submit"><?php echo JText::_('COM_TRACKER_UPLOAD_TORRENT_BUTTON'); ?></button>
		</div>
 
		<input type="hidden" name="max_torrent_size" value="<?php echo $params->get('max_torrent_size'); ?>" />
		<input type="hidden" name="option" value="com_tracker" />
		<input type="hidden" name="task" value="torrent.uploaded" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
