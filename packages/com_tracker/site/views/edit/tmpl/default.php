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

$this->editor = JFactory::getEditor();

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');

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

function check_torrent() {
	if(document.getElementById('torrent_file').value == "1") {
		document.getElementById('new_torrent_file').style.display = 'block';
	} else {
		document.getElementById('new_torrent_file').style.display = 'none';
	}
}
</script>

<div class="span10"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_FOR' );?></b><?php echo str_replace("_", " ", $this->item->name);?></div>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=torrent&id='.(int) $this->item->fid); ?>" method="post" name="edit-form" id="edit-form" class="form-validate form-horizontal" enctype="multipart/form-data" >
	<div class="control-group">
		<div class="control-label"><?php echo JText::_('COM_TRACKER_EDIT_TORRENT_FILE'); ?></div>
		<div class="controls">
			<?php
				$torrent_file = array(0 => JText::_('COM_TRACKER_EDIT_TORRENT_KEEP_DEFAULT'), 1 => JText::_('COM_TRACKER_EDIT_TORRENT_CHOOSE_NEW_FILE'));
				$options = array();
				foreach($torrent_file as $key=>$value) :
					$options[] = JHtml::_('select.option', $key, $value);
				endforeach;
				echo JHtml::_('select.genericlist', $options, 'torrent_file', 'class="inputbox" onchange="check_torrent();"', 'value', 'text', 0);
			?>
		</div>
	</div>

	<span id="torrent_file_keep" class="hide">
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' ); ?></div>
			<div class="controls">
				<?php // Removes the extension from the filename. We'll add it in the end
					$temp = explode( '.', $this->item->filename );
					$ext = array_pop( $temp );
					$this->item->filename = implode( '.', $temp );
				?>
				<input type="hidden" name="filename" value="<?php echo $this->item->filename; ?>" />
			</div>
		</div>
	</span>
			
	<span id="new_torrent_file" class="hide">
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' ); ?></div>
			<div class="controls"><input type="file" id="filename" name="filename" class="inputbox" size="50" value="" /></div>
		</div>
	</span>

	<div class="control-group">
		<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?></div>
		<div class="controls"><input type="text" id="name" name="name" class="input-xxlarge" value="<?php echo str_replace("_", " ", $this->item->name); ?>" /></div>
	</div>

	<div class="control-group">
		<div class="control-label"><?php echo JText::_( 'JCATEGORY' ); ?></div>
		<div class="controls">
			<select name="categoryID" class="inputbox">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_tracker'), 'value', 'text', $this->item->categoryID);?>
			</select>
		</div>
	</div>

	<?php if ($this->params->get('enable_licenses') == 1) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_LICENSE' ); ?></div>
			<div class="controls"><?php echo JHtml::_('select.genericlist', TrackerHelper::SelectList('licenses', 'id', 'shortname', '1'), 'licenseID', 'class="inputbox"', 'value', 'text', $this->item->licenseID); ?></div>
		</div>
	<?php else : ?>
		<input type="hidden" name="licenseID" value="<?php echo $this->item->licenseID; ?>" />
	<?php endif; ?>

	<?php if ($this->params->get('allow_upload_anonymous') == 1) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_EDIT_UPLOAD_AS_ANONYMOUS' ); ?></div>
			<div class="controls">
				<?php
					$anonymous = array(0 => JText::_('JNO'), 1 => JText::_('JYES'));
					$options = array();
					foreach($anonymous as $key=>$value) :
						$options[] = JHtml::_('select.option', $key, $value);
					endforeach;
					echo JHtml::_('select.genericlist', $options, 'uploader_anonymous', 'class="inputbox"', 'value', 'text', $this->item->uploader_anonymous);
				?>
			</div>
		</div>
	<?php else : ?>
		<input type="hidden" name="uploader_anonymous" value="<?php echo $this->item->uploader_anonymous; ?>" />
	<?php endif; ?>

	<?php if ($this->params->get('use_image_file') == 1) : ?>
		<div class="row-fluid">
			<div class="span5">
				<div class="control-group">
					<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_FILE' ); ?></div>
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
			</div>

			<div class="span5">
				<div id="image_file_file_div" class="control-group hide">
					<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_FILE' ); ?></div>
					<div class="controls"><input type="file" name="image_file_file" id="image_file_file" value="" class="inputbox" size="50" /></div>
				</div>

				<div id="image_file_link_div" class="control-group hide">
					<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_LINK' ); ?></div>
					<?php // Check if we're "editing" a link when we had a file
					if (!filter_var($this->item->image_file, FILTER_VALIDATE_URL)) $image_file_link_temp = '';
						else $image_file_link_temp = $this->item->image_file;
					?>
					<div class="controls"><input type="text" name="image_file_link" id="image_file_link" value="<?php echo $image_file_link_temp; ?>" class="inputbox" size="50" /></div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('forum_post_id') == 1) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_FORUM_POST' ); ?></div>
			<div class="controls"><input type="text" name="forum_post" id="forum_post" value="<?php echo $this->item->forum_post; ?>" class="inputbox" size="5" /></div>
		</div>
	<?php else: ?>
		<input type="hidden" name="forum_post" value="<?php echo $this->item->forum_post; ?>" />
	<?php endif; ?>

	<?php if ($this->params->get('torrent_information') == 1) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_INFO_POST' ); ?></div>
			<div class="controls"><input type="text" name="info_post" id="info_post" value="<?php echo $this->item->info_post; ?>" class="inputbox" size="5" /></div>
		</div>
	<?php else: ?>
		<input type="hidden" name="info_post" value="<?php echo $this->item->info_post; ?>" />
	<?php endif; ?>

	<?php if ($this->params->get('torrent_multiplier') == 1) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' ); ?></div>
			<div class="controls">
				<?php
					if (TrackerHelper::user_permissions('edit_torrents', $this->user->id)) :
						echo '<input type="text" style="text-align:right;" id="download_multiplier" name="download_multiplier" class="inputbox" size="10" value="'.$this->item->download_multiplier.'" />';
					else :
						echo $this->item->download_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );
						echo '<input type="hidden" name="download_multiplier" value="'.$this->item->download_multiplier.'" />';
					endif;
				?>				
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' ); ?></div>
			<div class="controls">
				<?php
					if (TrackerHelper::user_permissions('edit_torrents', $this->user->id)) :
						echo '<input type="text" style="text-align:right;" id="upload_multiplier" name="upload_multiplier" class="inputbox" size="10" value="'.$this->item->upload_multiplier.'" />';
					else :
						echo $this->item->upload_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );
						echo '<input type="hidden" name="upload_multiplier" value="'.$this->item->upload_multiplier.'" />';
					endif;
				?>
			</div>
		</div>
	<?php else : ?>
		<input type="hidden" name="download_multiplier" value="<?php echo $this->item->download_multiplier; ?>" />
		<input type="hidden" name="upload_multiplier" value="<?php echo $this->item->upload_multiplier; ?>" />
	<?php endif; ?>

	<div class="control-group">
		<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_DESCRIPTION' ); ?></div>
		<div class="controls"><?php echo $this->editor->display( 'description',  stripslashes($this->item->description) , '', '300', '60', '20', false); ?></div>
	</div>

	<?php if ($this->params->get('torrent_tags') == 1) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo JText::_( 'COM_TRACKER_TORRENT_TAGS' ); ?></div>
			<div class="controls"><input type="text" name="tags" id="tags" value="<?php echo $this->item->tags; ?>" class="input-xxlarge" /></div>
		</div>
	<?php endif; ?>

	<div style="float: right;">
		<button class="button validate" type="submit"><?php echo JText::_('JSAVE'); ?></button>
		<button class="button reset" type="reset" ><?php echo JText::_('COM_TRACKER_RESET'); ?></button>
		<button class="button cancel" type="button" onclick="history.back()"><?php echo JText::_('COM_TRACKER_CANCEL') ?></button>
	</div>

	<input type="hidden" name="fid" value="<?php echo $this->item->fid; ?>" />
	<input type="hidden" name="old_name" value="<?php echo $this->item->name; ?>" />
	<input type="hidden" name="old_filename" value="<?php echo $this->item->filename; ?>" />
	<input type="hidden" name="old_image" value="<?php echo $this->item->image_file; ?>" />
	<input type="hidden" name="info_hash" value="<?php echo bin2hex($this->item->info_hash); ?>" />
	<input type="hidden" name="option" value="com_tracker" />
	<input type="hidden" name="task" value="torrent.edited" />
	<?php echo JHtml::_('form.token'); ?>
</form>
