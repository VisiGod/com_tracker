<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$params =& JComponentHelper::getParams( 'com_tracker' );

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>
<style type="text/css">.toggle-editor{display:none;}</style>
<div class="upload-form">
	<form id="upload-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" enctype="multipart/form-data" class="form-validate">
		<table>
			<tr>
				<td width="1%" nowrap align="right"><?php echo $this->form->getLabel('filename');?>:</td>
				<td width="98%"><?php echo $this->form->getInput('filename'); ?></td>
			</tr>
			<tr>
				<td width="1%" nowrap align="right"><?php echo $this->form->getLabel('name');?>:</td>
				<td width="98%"><?php echo $this->form->getInput('name'); ?></td>
			</tr>
			<tr>
				<td width="1%" nowrap align="right"><?php echo $this->form->getLabel('categoryID');?>:</td>
				<td width="98%"><?php echo $this->form->getInput('categoryID'); ?>
				<?php if ($params->get('enable_licenses') == 1) {
					echo $this->form->getLabel('licenseID').':&nbsp;'.$this->form->getInput('licenseID');
				} ?>
				<?php if ($params->get('allow_upload_anonymous') == 1) {
					echo $this->form->getLabel('uploader_anonymous').':&nbsp;'.$this->form->getInput('uploader_anonymous');
				} ?>
				</td>
			</tr>

			<?php if ($params->get('use_image_file') == 1 && $params->get('image_width') > 0) { ?>
			<tr>
				<td width="1%" nowrap align="right"><?php echo $this->form->getLabel('image_file');?>:</td>
				<td width="98%"><?php echo $this->form->getInput('image_file'); ?></td>
			</tr>
			<?php } ?>

			<?php if (($params->get('forum_post_id') == 1) && ($params->get('torrent_information') == 1)) { ?>
			<tr>
				<td width="1%" nowrap align="right"><?php echo $this->form->getLabel('forum_post');?>:</td>
				<td width="98%"><?php 
					echo $this->form->getInput('forum_post');
					echo $this->form->getLabel('info_post');
					echo $this->form->getInput('info_post'); ?>
				</td>
			<?php } elseif (($params->get('forum_post_id') == 1) && ($params->get('torrent_information') == 0)) { ?>
				<td width="1%" nowrap align="right"><?php echo $this->form->getLabel('forum_id');?>:</td>
				<td width="98%"><?php echo $this->form->getInput('forum_id');?></td>
			<?php } elseif (($params->get('forum_post_id') == 0) && ($params->get('torrent_information') == 1)) { ?>
				<td width="1%" nowrap align="right"><?php echo $this->form->getLabel('info_id');?></td>
				<td width="98%"><?php echo $this->form->getInput('info_id'); ?></td>
			<?php } ?>
			</tr>

			<tr>
				<td width="1%" nowrap align="right" valign="top"><?php echo $this->form->getLabel('description');?>:</td>
				<td width="98%"><?php echo $this->form->getInput('description'); ?></td>
			</tr>
		</table>

		<div style="float: right;">
			<button class="button validate" type="submit"><?php echo JText::_('COM_TRACKER_UPLOAD_TORRENT_BUTTON'); ?></button>
		</div>

		<input type="hidden" name="max_torrent_size" value="<?php echo $params->get('max_torrent_size'); ?>" />
		<input type="hidden" name="option" value="com_tracker" />
		<input type="hidden" name="task" value="torrent.uploaded" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
