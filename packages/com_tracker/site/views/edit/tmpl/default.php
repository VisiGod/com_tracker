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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tracker.php';
$user	= JFactory::getUser();
$editor = JFactory::getEditor();
$params =& JComponentHelper::getParams( 'com_tracker' );
?>

<div class="row1" align="left" style="font-size: medium;"><br/><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_FOR' );?></b><?php echo str_replace("_", " ", $this->item->name);?><br/><br/><br/></div>
<div>
<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=torrent&id='.(int) $this->item->fid); ?>" method="post" name="torrent-edit" id="torrent-edit">

	<table style="cellpadding:0; cellspacing:2; border:0; width:99%; align:center;">
		<tr>
			<td class="row1" valign="middle" style="height: 16px; width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_NAME' );?></b>&nbsp;</td>
			<td class="row0" style="width:1%;" nowrap><input type="text" id="name" name="name" class="inputbox" size="90" value="<?php echo str_replace("_", " ", $this->item->name); ?>" /></td>
		</tr>
		<tr>
			<td class="row1" valign="middle" style="height: 16px; width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_FILENAME' );?></b>&nbsp;</td>
			<td class="row0" style="width:1%;" nowrap><input type="text" id="filename" name="filename" class="inputbox" size="90" value="<?php echo $this->item->filename; ?>" /></td>
		</tr>
		<tr>
			<td class="row1" style="width:1%;" align="right" valign="top"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DESCRIPTION' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;"><?php echo $editor->display( 'description',  stripslashes($this->item->description) , '565', '300', '60', '20', false); ?></td>
		</tr>
		<tr>
			<td class="row1" style="width:1%;" align="right"><b><?php echo JText::_( 'JCATEGORY' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;">
				<select name="categoryID" class="inputbox">
					<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_tracker'), 'value', 'text', $this->item->categoryID);?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1" style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_SIZE' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;"><?php echo TrackerHelper::make_size($this->item->size)."	( ".number_format($this->item->size)." ".JText::_( 'COM_TRACKER_BYTES' ).")";?></td>
		</tr>
		<tr>
			<td class="row1" style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_CREATED_TIME' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;"><?php echo $this->item->created_time;?></td>
		</tr>
		<?php if ($params->get('use_licenses') == 1) {?>
		<tr>
			<td class="row1" style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_LICENSE' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;"><?php echo JHTML::_('select.genericlist', TrackerHelper::SelectList('tracker_licenses', 'id', 'shortname', 'state'), 'licenseID', 'class="inputbox"', 'value', 'text', $this->item->license); ?></td>
		</tr>
		<?php }?>
		<tr>
			<td class="row1" nowrap style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_UPLOADER' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;">
				<?php 
					echo $this->item->uploader;
					if ($params->get('allow_upload_anonymous') == 1) {
						echo '&nbsp;&nbsp;||&nbsp;&nbsp;<b>'.JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' ).'</b>&nbsp;&nbsp;'.JHTML::_('select.booleanlist', 'uploader_anonymous', 'class="inputbox"', $this->item->uploader_anonymous, 'Yes', 'No');
					}
				?>
			</td>
		</tr>
		<tr>
			<td class="row1" nowrap style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;"><?php echo $this->item->number_files." ".JText::_( 'COM_TRACKER_TORRENT_DETAILS_NUMBER_OF_FILES' );?></td>
		</tr>
		<?php if ($params->get('torrent_multiplier') == 1) {?>
		<tr>
			<td class="row1" nowrap style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_DOWNLOAD_MULTIPLIER' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;">
				<?php
					if (TrackerHelper::user_permissions('edit_torrents', $user->id))
						echo '<input type="text" style="text-align:right;" id="download_multiplier" name="download_multiplier" class="inputbox" size="10" value="'.$this->item->download_multiplier.'" />';
					else echo $this->item->download_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );
				?>
			</td>
		</tr>
		<tr>
			<td class="row1" nowrap style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_UPLOAD_MULTIPLIER' );?></b>&nbsp;</td>
			<td class="row0" style="width:98%;">
				<?php
					if (TrackerHelper::user_permissions('edit_torrents', $user->id))
						echo '<input type="text" style="text-align:right;" id="upload_multiplier" name="upload_multiplier" class="inputbox" size="10" value="'.$this->item->upload_multiplier.'" />';
					else echo $this->item->upload_multiplier." ".JText::_( 'COM_TRACKER_TORRENT_TIMES' );
				?>
			</td>
		</tr>
		<?php } ?>
		<?php if ($params->get('forum_post_id')) { ?>
		<tr>
				<td class="row1" nowrap style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_FORUM_POST' );?></b>&nbsp;</td>
				<td class="row0" style="width:98%;"><input type="text" style="text-align:right;" id="forum_post" name="forum_post" class="inputbox" size="10" value="<?php echo $this->item->forum_post; ?>" /></td>
		</tr>
		<?php } ?>
		<?php if ($params->get('torrent_information')) { ?>
		<tr>
				<td class="row1" nowrap style="width:1%;" align="right"><b><?php echo JText::_( 'COM_TRACKER_TORRENT_INFO_POST' );?></b>&nbsp;</td>
				<td class="row0" style="width:98%;"><input type="text" style="text-align:right;" id="info_post" name="info_post" class="inputbox" size="10" value="<?php echo $this->item->info_post; ?>" /></td>
		</tr>
		<?php } ?>
	</table>
	<br />

	<div style="float: right;">
		<button class="button validate" type="submit"><?php echo JText::_('JSAVE'); ?></button>
		<button class="button reset" type="reset" ><?php echo JText::_('COM_TRACKER_RESET'); ?></button>
		<button class="button cancel" type="button" onclick="history.back()"><?php echo JText::_('COM_TRACKER_CANCEL') ?></button>
	</div>

	<input type="hidden" name="fid" value="<?php echo $this->item->fid; ?>" />
	<input type="hidden" name="option" value="com_tracker" />
	<input type="hidden" name="task" value="torrent.edited" />
	<?php echo JHtml::_('form.token'); ?>

</form>
</div>
