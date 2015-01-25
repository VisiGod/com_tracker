<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$style = '.hide { display:none; }';
$doc->addStyleDeclaration( $style );
?>
<script type="text/javascript">
function check_dd() {
	if(document.getElementById('default_image_type').value == "1") {
		document.getElementById('image_file_file_div').style.display = 'block';
		document.getElementById('image_file_link_div').style.display = 'none';
	} else if (document.getElementById('default_image_type').value == "2"){
		document.getElementById('image_file_file_div').style.display = 'none';
		document.getElementById('image_file_link_div').style.display = 'block';
	} else {
		document.getElementById('image_file_file_div').style.display = 'none';
		document.getElementById('image_file_link_div').style.display = 'none';
	}
}
</script>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="upload-form" id="upload-form" class="form-validate form-horizontal" enctype="multipart/form-data" >
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('filename'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('filename'); ?></div>
		</div>

		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
		</div>

		<div class="row-fluid">
			<div class="span5">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('categoryID'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('categoryID'); ?></div>
				</div>
			</div>

			<?php if ($this->params->get('enable_licenses')) : ?>
				<div class="span5">
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('licenseID'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('licenseID'); ?></div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ($this->params->get('allow_upload_anonymous') == 1) : ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('uploader_anonymous'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('uploader_anonymous'); ?></div>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('use_image_file') == 1) : ?>
			<div class="row-fluid">
				<div class="span5">
					<div class="control-group">
						<!--  Check for choosen option in the backend -->
						<?php if ($this->params->get('image_type') == 1) : ?>
							<div class="control-label"><?php echo $this->form->getLabel('image_file'); ?></div>
							<div class="controls"><input type="file" name="image_file_file" id="image_file_file" value="" class="inputbox" size="50" /></div>
						<?php elseif ($this->params->get('image_type') == 2) : ?>
							<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_LINK' ); ?></div>
							<div class="controls"><input type="text" name="image_file_link" id="image_file_link" value="<?php echo $this->form->getValue('image_file'); ?>" class="inputbox" size="50" /></div>
						<?php else : ?>
							<div class="control-label"><?php echo $this->form->getLabel('image_file'); ?></div>
							<div class="controls">
								<?php
									$image_type = array(0 => JText::_('COM_TRACKER_TORRENT_IMAGE_CHOOSE_OPTION'), 1 => JText::_('COM_TRACKER_TORRENT_IMAGE_CHOOSE_FILE'), 2 => JText::_('COM_TRACKER_TORRENT_IMAGE_CHOOSE_LINK'));
									$options = array();
									foreach($image_type as $key=>$value) :
										$options[] = JHtml::_('select.option', $key, $value);
									endforeach;
									echo JHtml::_('select.genericlist', $options, 'default_image_type', 'class="inputbox" onchange="check_dd();"', 'value', 'text', 0);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="span5">
					<div id="image_file_file_div" class="control-group hide">
						<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_FILE' ); ?></div>
						<div class="controls"><input type="file" name="image_file_file" id="image_file_file" value="" class="inputbox" size="50" /></div>
					</div>

					<div id="image_file_link_div" class="control-group hide">
						<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_LINK' ); ?></div>
						<div class="controls"><input type="text" name="image_file_link" id="image_file_link" value="<?php echo $this->form->getValue('image_file'); ?>" class="inputbox" size="50" /></div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('forum_post_id') || $this->params->get('torrent_information')) : ?>
			<div class="row-fluid">
				<?php if ($this->params->get('forum_post_id')) : ?>
					<div class="span5">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('forum_post'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('forum_post'); ?></div>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($this->params->get('torrent_information')) : ?>
					<div class="span5">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('info_post'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('info_post'); ?></div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
				
		<?php if ($this->params->get('torrent_tags')) : ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tags'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('tags'); ?></div>
			</div>
		<?php endif; ?>

		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
		</div>

	<div style="float: right;">
		<button class="button validate" type="submit"><?php echo JText::_('COM_TRACKER_UPLOAD_TORRENT_BUTTON'); ?></button>
	</div>

	<input type="hidden" name="max_torrent_size" value="<?php echo $this->params->get('max_torrent_size'); ?>" />
	<input type="hidden" name="option" value="com_tracker" />
	<input type="hidden" name="task" value="torrent.uploaded" />
	<?php echo JHtml::_('form.token'); ?>
</form>
