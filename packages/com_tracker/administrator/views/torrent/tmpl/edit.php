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


JHtml::_('jquery.framework');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
$app = JFactory::getApplication();
$params = JComponentHelper::getParams( 'com_tracker' );

$doc = JFactory::getDocument();
$style = '.hide { display:none; }';
$doc->addStyleDeclaration( $style );
/*
?>
<script type="text/javascript">
var elem = document.getElementById("security_question_1");
elem.onchange = function(){
    var hiddenDiv = document.getElementById("showMe");
    hiddenDiv.style.display = (this.value == "") ? "none":"block";
};

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
*/?>
<script type="text/javascript">
function check_dd() {
	if(document.getElementById('default_image_type').value == "1") {
		document.getElementById('image_file_field').style.display = 'block';
		document.getElementById('image_file_link').style.display = 'none';
	} else if (document.getElementById('default_image_type').value == "2"){
		document.getElementById('image_file_field').style.display = 'none';
		document.getElementById('image_file_link').style.display = 'block';
	} else {
		document.getElementById('image_file_field').style.display = 'none';
		document.getElementById('image_file_link').style.display = 'none';
	}
}

Joomla.submitbutton = function(task) {
	if (task == 'torrent.cancel' || document.formvalidator.isValid(document.id('torrent-form'))) {
		Joomla.submitform(task, document.getElementById('torrent-form'));
	}
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&fid='.(int) $this->item->fid); ?>" method="post" name="adminForm" id="torrent-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'trackerTorrent', array('active' => 'basic_info')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'basic_info', JText::_('COM_TRACKER_TORRENT_BASIC_INFO', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
			</div>
			<div class="control-group">
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
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('categoryID'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('categoryID'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('uploader'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('uploader'); ?></div>
			</div>

			<?php if ($params->get('allow_upload_anonymous') == 1) { ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('uploader_anonymous'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('uploader_anonymous'); ?></div>
			</div>
			<?php } ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if ($params->get('forum_post_id') == 1 || $params->get('torrent_information') == 1 || $params->get('torrent_multiplier') == 1 || $params->get('enable_licenses') == 1 || $params->get('use_image_file') == 1) { ?>
		<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'extra_info', JText::_('COM_TRACKER_TORRENT_EXTRA_INFO', true)); ?>
			<?php if ($params->get('forum_post_id') == 1) { ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('forum_post'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('forum_post'); ?></div>
				</div>
			<?php } ?>
			
			<?php if ($params->get('torrent_information') == 1) { ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('info_post'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('info_post'); ?></div>
				</div>
			<?php } ?>

			<?php if ($params->get('torrent_multiplier') == 1) { ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('download_multiplier'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('download_multiplier'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('upload_multiplier'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('upload_multiplier'); ?></div>
				</div>
			<?php } ?>
			
			<?php if ($params->get('enable_licenses') == 1) { ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('licenseID'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('licenseID'); ?></div>
				</div>
			<?php } ?>

			<?php if ($params->get('use_image_file') == 1) { ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('image_file'); ?></div>
					
					<div class="controls">
						<?php
							$image_type = array(0 => JText::_('COM_TRACKER_EDIT_IMAGE_KEEP_DEFAULT'), 1 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_FILE'), 2 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_LINK'), 3 => JText::_('COM_TRACKER_EDIT_IMAGE_REMOVE_PREVIOUS_IMAGE'));
							$options = array();
							foreach($image_type as $key=>$value) :
								$options[] = JHtml::_('select.option', $key, $value);
							endforeach;
							echo JHtml::_('select.genericlist', $options, 'default_image_type', 'class="inputbox" onchange="check_dd();"', 'value', 'text', 0);
							?>
					</div>
				</div>

				<span id="image_file_field" class="hide">
					<div class="control-group">
						<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_FILE' ); ?></div>
						<div class="controls"><input type="file" name="image_file" id="image_file" value="" class="inputbox" size="50" /></div>
					</div>
				</span>

				<span id="image_file_link" class="hide">
					<div class="control-group">
						<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_LINK' ); ?></div>
						<div class="controls"><input type="text" name="image_file" id="image_file" value="<?php echo $this->form->getValue('image_file'); ?>" class="inputbox" size="50" /></div>
					</div>
				</span>
			<?php } ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php } ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'description', JText::_('COM_TRACKER_TORRENT_DESCRIPTION', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if ($params->get('torrent_tags') == 1) { ?>
			<?php echo JHtml::_('bootstrap.addTab', 'trackerTorrent', 'tags', JText::_('COM_TRACKER_TORRENT_TAGS', true)); ?>
				<div class="control-label"><?php echo JText::_('COM_TRACKER_TORRENT_TAGS_EDIT'); ?></div>
				<br />
				<br />

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