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
$user	= JFactory::getUser();
$editor = JFactory::getEditor();
$params = JComponentHelper::getParams( 'com_tracker' );
jimport( 'joomla.form.form' );

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addScript($params->get('jquery_url'));
$style = '.hide { display:none; }';
$doc->addStyleDeclaration( $style );

?>
<script type="text/javascript">
jQuery.noConflict();

(function($) {
	$(document).ready(function(){
		function default_dropdown() {
			if ($("#default_image_type").val() == "1") $("#image_file_field").show();
			if ($("#default_image_type").val() == "2") $("#image_file_link").show();
			if ($("#default_torrent_file").val() == "0") $("#torrent_file_keep").show();
		}
		default_dropdown();
		
		$("#default_image_type").change(function(){
			if ($(this).val() == "1" ) {
				$("#image_file_field").show();
				$("#image_file_link").hide();
			}
			if ($(this).val() == "2" ) {
				$("#image_file_field").hide();
				$("#image_file_link").show();
			}
			if ($(this).val() == "0" || $(this).val() == "3" ) {
				$("#image_file_field").hide();
				$("#image_file_link").hide();
			}
	    });

		$("#default_torrent_file").change(function(){
			if ($(this).val() == "0" ) {
				$("#torrent_file_keep").show();
				$("#torrent_file_new").hide();
			}
			if ($(this).val() == "1" ) {
				$("#torrent_file_keep").hide();
				$("#torrent_file_new").show();
			}
	    });
	});
})(jQuery);
</script>

<div class="row1" align="left" style="font-size: medium;"><br/><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_FOR' );?></b><?php echo str_replace("_", " ", $this->item->name);?><br/><br/><br/></div>
<div>
	<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=torrent&id='.(int) $this->item->fid); ?>" method="post" enctype="multipart/form-data" name="torrent-edit" id="torrent-edit">
		<div style="float: left;">
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_('COM_TRACKER_EDIT_TORRENT_FILE'); ?>:</span>
			<?php
				$torrent_file = array(0 => JText::_('COM_TRACKER_EDIT_TORRENT_KEEP_DEFAULT'), 1 => JText::_('COM_TRACKER_EDIT_TORRENT_CHOOSE_NEW_FILE'));
				$torrent_options = array();
				foreach($torrent_file as $key=>$value) :
					$torrent_options[] = JHTML::_('select.option', $key, $value);
				endforeach;
			?>
			<span style="width:1%; wrap:nowrap; align:left;"><?php echo JHTML::_('select.genericlist', $torrent_options, 'default_torrent_file', 'class="inputbox"', 'value', 'text', 0); ?></span>

			<div style="clear: both;"></div><br />

			<div class="hide" id="torrent_file_keep" style="float: left;">
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' ); ?>:</span>
				<span style="width:1%;">
					<?php // Removes the extension from the filename. We'll add it in the end
						$temp = explode( '.', $this->item->filename );
						$ext = array_pop( $temp );
						$this->item->filename = implode( '.', $temp );
					?>
					<input type="text" id="filename" name="filename" class="inputbox" size="50" value="<?php echo $this->item->filename; ?>" />
				</span>
			</div>

			<div class="hide" id="torrent_file_new" style="float: left;">
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' ); ?>:</span>
				<span style="width:98%;"><input type="file" id="filename" name="filename" class="inputbox" size="50" value="" /></span>
			</div>
		</div>
		<div style="clear: both;"><br /></div>

		<div>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' ); ?>:</span>
			<span style="width:1%;"><input type="text" id="name" name="name" class="inputbox" size="90" value="<?php echo str_replace("_", " ", $this->item->name); ?>" /></span>
		</div>
		<div style="clear: both;"><br /></div>

		<div>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'JCATEGORY' );?>:</span>
			<span style="width:1%;">
				<select name="categoryID" class="inputbox">
					<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_tracker'), 'value', 'text', $this->item->categoryID);?>
				</select>
			</span>
		</div>
		<div style="clear: both;"><br /></div>

		<div>
			<?php if ($params->get('enable_licenses') == 1) { ?>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_LICENSE' );?>:</span>
			<span style="width:1%;">
				<?php echo JHTML::_('select.genericlist', TrackerHelper::SelectList('licenses', 'id', 'shortname', '1'), 'licenseID', 'class="inputbox"', 'value', 'text', $this->item->licenseID); ?>
			</span>
			<?php } else { ?>
			<input type="hidden" name="licenseID" value="<?php echo $this->item->licenseID; ?>" />
			<?php } ?>

			<?php if ($params->get('allow_upload_anonymous') == 1) { ?>
			<span>&nbsp;&nbsp;&nbsp;</span>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_EDIT_UPLOAD_AS_ANONYMOUS' );?>:</span>
			<span style="width:1%;">
				<?php
					$anonymous = array(0 => JText::_('JNO'), 1 => JText::_('JYES'));
					$options = array();
					foreach($anonymous as $key=>$value) :
						$options[] = JHTML::_('select.option', $key, $value);
					endforeach;
					echo JHTML::_('select.genericlist', $options, 'uploader_anonymous', 'class="inputbox"', 'value', 'text', $this->item->uploader_anonymous);
				?>
			</span>
			<?php } else { ?>
			<input type="hidden" name="uploader_anonymous" value="<?php echo $this->item->uploader_anonymous; ?>" />
			<?php } ?>
		</div>
		<div style="clear: both;"><br /></div>

		<?php if ($params->get('use_image_file') == 1) { ?>
		<div>
		<?php if ($params->get('image_type') == 0) { ?>
			<div style="float: left;">
				<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_('COM_TRACKER_TORRENT_IMAGE_FILE'); ?>:</span>
				<?php
					$image_type = array(0 => JText::_('COM_TRACKER_EDIT_IMAGE_KEEP_DEFAULT'), 1 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_FILE'), 2 => JText::_('COM_TRACKER_EDIT_IMAGE_CHOOSE_NEW_LINK'), 3 => JText::_('COM_TRACKER_EDIT_IMAGE_REMOVE_PREVIOUS_IMAGE'));
					$options = array();
					foreach($image_type as $key=>$value) :
						$options[] = JHTML::_('select.option', $key, $value);
					endforeach;
				?>
				<span style="width:1%; wrap:nowrap; align:left;"><?php echo JHTML::_('select.genericlist', $options, 'default_image_type', 'class="inputbox"', 'value', 'text', 0); ?></span>
			</div>
		<?php } ?>
			<div style="clear: both;"></div>

		<?php if ($params->get('image_type') == 1 || $params->get('image_type') == 0) { ?>
			<?php if ($params->get('image_type') == 0) echo '<div class="hide" id="image_file_field" style="float: left;"><br />'; ?>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_FILE' ); ?>:</span>
			<span style="width:1%;"><input type="file" name="image_file" id="image_file" value="<?php echo $this->item->image_file; ?>" class="inputbox" size="60" /></span>
			<?php if ($params->get('image_type') == 0) echo '</div>'; ?>
		<?php } ?>

		<?php if ($params->get('image_type') == 2 || $params->get('image_type') == 0) { ?>
			<?php if ($params->get('image_type') == 0) echo '<div class="hide" id="image_file_link" style="float: left;"><br />'; ?>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_IMAGE_LINK' ); ?>:</span>
			<span style="width:1%;"><input type="text" name="image_file" id="image_file" value="<?php echo $this->item->image_file; ?>" class="inputbox" size="60" /></span>
			<?php if ($params->get('image_type') == 0) echo '</div>'; ?>
		<?php } ?>
		</div>
		<div style="clear: both;"><br /></div>
		<?php } else { ?>
		<input type="hidden" name="image_file" value="<?php echo $this->item->image_file; ?>" />
		<?php } ?>

		<?php if ($params->get('forum_post_id') == 1 || $params->get('torrent_information') == 1) { ?>
		<div>
			<?php if ($params->get('forum_post_id') == 1) { ?>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_FORUM_POST' ); ?> :</span>
			<span style="width:1%;"><input type="text" name="forum_post" id="forum_post" value="<?php echo $this->item->forum_post; ?>" class="inputbox" size="5" /></span>
			<?php } ?>
			<?php if ($params->get('torrent_information') == 1) { ?>
			<?php if ($params->get('forum_post_id') == 1) echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>'; ?>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_INFO_POST' ); ?> :</span>
			<span style="width:1%;"><input type="text" name="info_post" id="info_post" value="<?php echo $this->item->info_post; ?>" class="inputbox" size="5" /></span>
			<?php } ?>
		</div>
		<div style="clear: both;"><br /></div>
		<?php } else { ?>
		<input type="hidden" name="forum_post" value="<?php echo $this->item->forum_post; ?>" />
		<input type="hidden" name="info_post" value="<?php echo $this->item->info_post; ?>" />
		<?php } ?>

		<?php if ($params->get('torrent_multiplier') == 1) {?>
		<div>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' ); ?>:</span>
			<span style="width:1%; wrap:nowrap; align:left;">
			<?php
				if (TrackerHelper::user_permissions('edit_torrents', $user->id))
					echo '<input type="text" style="text-align:right;" id="download_multiplier" name="download_multiplier" class="inputbox" size="10" value="'.$this->item->download_multiplier.'" />';
				else {
					echo $this->item->download_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );
					echo '<input type="hidden" name="download_multiplier" value="'.$this->item->download_multiplier.'" />';
				}
			?>
			</span>
			<span>&nbsp;&nbsp;&nbsp;</span>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' ); ?>:</span>
			<span style="width:1%; wrap:nowrap; align:left;">
			<?php
				if (TrackerHelper::user_permissions('edit_torrents', $user->id))
					echo '<input type="text" style="text-align:right;" id="upload_multiplier" name="upload_multiplier" class="inputbox" size="10" value="'.$this->item->upload_multiplier.'" />';
				else {
					echo $this->item->upload_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );
					echo '<input type="hidden" name="upload_multiplier" value="'.$this->item->upload_multiplier.'" />';
				}
			?>
			</span>
		</div>
		<div style="clear: both;"><br /></div>
		<?php } else { ?>
		<input type="hidden" name="download_multiplier" value="<?php echo $this->item->download_multiplier; ?>" />
		<input type="hidden" name="upload_multiplier" value="<?php echo $this->item->upload_multiplier; ?>" />
		<?php } ?>			

		<div>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_DESCRIPTION' ); ?>:</span>
			<span style="width:1%; wrap:nowrap; align:left;"><?php echo $editor->display( 'description',  stripslashes($this->item->description) , '100%', '300', '60', '20', false); ?></span>
		</div>
		<div style="clear: both;"><br /></div>

		<?php if ($params->get('torrent_tags') == 1) { ?>
		<div>
			<span style="width:1%; wrap:nowrap; align:right;"><?php echo JText::_( 'COM_TRACKER_TORRENT_TAGS' ); ?>:</span>
			<span style="width:1%; wrap:nowrap; align:left;">
				<input type="text" name="tags" id="tags" value="<?php echo $this->item->tags; ?>" class="textarea" size="100" />
			</span>
		</div>
		<div style="clear: both;"><br /></div>
		<?php } ?>

		<div style="float: right;">
			<button class="button validate" type="submit"><?php echo JText::_('JSAVE'); ?></button>
			<button class="button reset" type="reset" ><?php echo JText::_('COM_TRACKER_RESET'); ?></button>
			<button class="button cancel" type="button" onclick="history.back()"><?php echo JText::_('COM_TRACKER_CANCEL') ?></button>
		</div>

		<input type="hidden" name="fid" value="<?php echo $this->item->fid; ?>" />
		<input type="hidden" name="old_filename" value="<?php echo $this->item->filename; ?>" />
		<input type="hidden" name="info_hash" value="<?php echo bin2hex($this->item->info_hash); ?>" />
		<input type="hidden" name="option" value="com_tracker" />
		<input type="hidden" name="task" value="torrent.edited" />
		<?php echo JHtml::_('form.token'); ?>

	</form>
</div>
