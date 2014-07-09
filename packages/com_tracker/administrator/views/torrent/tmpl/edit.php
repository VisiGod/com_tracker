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

$doc = JFactory::getDocument();
$style = '.hide { display:none; }';
$doc->addStyleDeclaration( $style );

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'torrent.cancel' || document.formvalidator.isValid(document.id('torrent-form'))) {
			Joomla.submitform(task, document.getElementById('torrent-form'));
		}
	}
</script>

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

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&fid='.(int) $this->item->fid); ?>" method="post" name="adminForm" id="torrent-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'trackerTorrent', array('active' => 'basic_info')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'basic_info', JText::_('COM_TRACKER_TORRENT_BASIC_INFO', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>

				<div class="control-label">
					<?php
						echo $this->form->getLabel('filename');
						// Removes the extension from the filename. We'll add it in the end
						$temp = explode( '.', $this->form->getValue('filename') );
						$ext = array_pop( $temp );
						$this->form->setValue('filename', null, implode( '.', $temp ));
					?>
				</div>
				<div class="controls"><?php echo $this->form->getInput('filename'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('categoryID'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('categoryID'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('uploader'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('uploader'); ?></div>

				<?php if ($params->get('allow_upload_anonymous') == 1) { ?>
					<div class="control-label"><?php echo $this->form->getLabel('uploader_anonymous'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('uploader_anonymous'); ?></div>
				<?php } ?>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'extra_info', JText::_('COM_TRACKER_TORRENT_EXTRA_INFO', true)); ?>
			<div class="control-group">
				<?php if ($params->get('forum_post_id') == 1) { ?>
					<div class="control-label"><?php echo $this->form->getLabel('forum_post'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('forum_post'); ?></div>
				<?php } ?>
			
				<?php if ($params->get('torrent_information') == 1) { ?>
					<div class="control-label"><?php echo $this->form->getLabel('info_post'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('info_post'); ?></div>
				<?php } ?>

				<?php if ($params->get('torrent_multiplier') == 1) { ?>
					<div class="control-label"><?php echo $this->form->getLabel('download_multiplier'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('download_multiplier'); ?></div>

					<div class="control-label"><?php echo $this->form->getLabel('upload_multiplier'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('upload_multiplier'); ?></div>
				<?php } ?>
			
				<?php if ($params->get('enable_licenses') == 1) { ?>
					<div class="control-label"><?php echo $this->form->getLabel('licenseID'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('licenseID'); ?></div>
				<?php } ?>

				<?php if ($params->get('use_image_file') == 1) { ?>
					<div class="control-label"><?php echo $this->form->getLabel('image_file'); ?></div>
					<div class="controls">
						<?php
							$image_type = array(0 => JText::_('COM_TRACKER_EDIT_IMAGE_KEEP_DEFAULT'), 1 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_FILE'), 2 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_LINK'), 3 => JText::_('COM_TRACKER_EDIT_IMAGE_REMOVE_PREVIOUS_IMAGE'));
							$options = array();
							foreach($image_type as $key=>$value) :
								$options[] = JHtml::_('select.option', $key, $value);
							endforeach;
							echo JHtml::_('select.genericlist', $options, 'default_image_type', 'class="inputbox"', 'value', 'text', 0);

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
							}
						?>
					</div>
				<?php } ?>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'description', JText::_('COM_TRACKER_TORRENT_DESCRIPTION', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if ($params->get('torrent_tags') == 1) { ?>
			<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'tags', JText::_('COM_TRACKER_TORRENT_TAGS_EDIT', true)); ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('tags'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('tags'); ?></div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php } ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="old_filename" value="<?php echo $this->form->getValue('filename'); ?>" />
	<input type="hidden" name="fid" value="<?php echo $this->form->getValue('fid'); ?>" />
	<input type="hidden" name="info_hash" value="<?php echo bin2hex($this->form->getValue('info_hash')); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>