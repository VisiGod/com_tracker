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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();

$app = JFactory::getApplication();
$params = JComponentHelper::getParams( 'com_tracker' );
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if (task == 'rss.cancel' || document.formvalidator.isValid(document.id('rss-form'))) {
		Joomla.submitform(task, document.getElementById('rss-form'));
	}
}

Joomla.check_rss_auth = function() {
	if(document.getElementById('jform_rss_authentication').value == ("2")) {
		document.getElementById('authentication_groups').style.display = 'block';
	} else {
		document.getElementById('authentication_groups').style.display = 'none';
	}
}

Joomla.check_rss_type = function() {
	if(document.getElementById('jform_rss_type').value == "1") {
		document.getElementById('rss_type_category').style.display = 'block';
		document.getElementById('rss_type_license').style.display = 'none';
	} else if (document.getElementById('jform_rss_type').value == "2"){
		document.getElementById('rss_type_category').style.display = 'none';
		document.getElementById('rss_type_license').style.display = 'block';
	} else {
		document.getElementById('rss_type_category').style.display = 'none';
		document.getElementById('rss_type_license').style.display = 'none';
	}	
}

jQuery.noConflict();
(function($) {
$(document).ready( function() {
	$('#ClickName, #ClickDescription, #ClickLink, #ClickCategory, '+
	  '#ClickSize, #ClickUpload, #ClickUploader, #ClickLicense, '+
	  '#ClickSeeders, #ClickLeechers, #ClickCompleted').click(function() {
		$("#jform_item_description").insertAtCaret($(this).text());
			return false
		});
	});
})(jQuery);

(function($) {
		$.fn.insertAtCaret = function (myValue) {
			return this.each(function(){
					//IE support
					if (document.selection) {
							this.focus();
							sel = document.selection.createRange();
							sel.text = myValue;
							this.focus();
					}
					//MOZILLA / NETSCAPE support
					else if (this.selectionStart || this.selectionStart == '0') {
							var startPos = this.selectionStart;
							var endPos = this.selectionEnd;
							var scrollTop = this.scrollTop;
							this.value = this.value.substring(0, startPos)+ myValue+ this.value.substring(endPos,this.value.length);
							this.focus();
							this.selectionStart = startPos + myValue.length;
							this.selectionEnd = startPos + myValue.length;
							this.scrollTop = scrollTop;
					} else {
							this.value += myValue;
							this.focus();
					}
			});
		};
})(jQuery);
</script>
<style type="text/css" media="Screen">
	#ClickName, #ClickDescription,#ClickLink, 
	#ClickCategory, #ClickSize, #ClickUpload, 
	#ClickUploader, #ClickLicense, #ClickSeeders, 
	#ClickLeechers, #ClickCompleted	{
		cursor:pointer;
	}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="rss-form" class="form-validate form-horizontal">
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'rss', array('active' => 'channel')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'rss', 'channel', JText::_('COM_TRACKER_RSS_CHANNEL', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('channel_title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('channel_title'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('channel_description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('channel_description'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('rss_authentication'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('rss_authentication');?></div>
			</div>

			<!-- RSS group authentication -->
			<div class="control-group hide" id="authentication_groups">
				<div class="control-label"><?php echo $this->form->getLabel('rss_authentication_group'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('rss_authentication_group'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('rss_type'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('rss_type'); ?></div>
			</div>

			<!-- RSS by category -->
			<div class="control-group hide" id="rss_type_category">
				<div class="control-label"><?php echo $this->form->getLabel('rss_type_category'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('rss_type_category'); ?></div>
			</div>

			<!-- RSS by license type -->
			<div class="control-group hide" id="rss_type_license">
				<div class="control-label"><?php echo $this->form->getLabel('rss_type_license'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('rss_type_license'); ?></div>
			</div>

		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'rss', 'items', JText::_('COM_TRACKER_RSS_ITEMS', true)); ?>
			<div class="row">
				<div class="span5 offset1" id="rss_params">
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('item_count'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('item_count'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('item_title'); ?></div>
						<div class="controls"><textarea name="jform[item_title]" id="jform_item_title" class="txtDropTarget" cols="85" rows="1"><?php echo $this->form->getValue('item_title'); ?></textarea></div>
					</div>
					<div class="control-label"><?php echo $this->form->getLabel('item_description'); ?></div>
					<div class="controls"><textarea name="jform[item_description]" id="jform_item_description" class="txtDropTarget" cols="85" rows="19"><?php echo $this->form->getValue('item_description'); ?></textarea></div>
				</div>

				<div class="span5">
					<div class="control-group" id="field_list">
						<legend><?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER'); ?></legend>
						<?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_DESCRIPTION_PRE').JText::_('COM_TRACKER_RSS_ITEM_DESCRIPTION').JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_DESCRIPTION_POST'); ?>
						<br />
						<ul>
							<li><span id="ClickName">{name}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_NAME'); ?></span></li>
							<li><span id="ClickDescription">{description}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_DESCRIPTION'); ?></span></li>
							<li><span id="ClickLink">{link}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_LINK'); ?></span></li>
							<li><span id="ClickCategory">{category}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_CATEGORY'); ?></span></li>
							<li><span id="ClickSize">{size}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_SIZE'); ?></span></li>
							<li><span id="ClickUpload">{upload_date}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_UPLOADED_DATE'); ?></span></li>
							<li><span id="ClickUploader">{uploader}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_UPLOADER'); ?></span></li>
							<li><span id="ClickLicense">{license}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_LICENSE'); ?></span></li>
							<li><span id="ClickSeeders">{seeders}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_SEEDERS'); ?></span></li>
							<li><span id="ClickLeechers">{leechers}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_LEECHERS'); ?></span></li>
							<li><span id="ClickCompleted">{completed}</span><span> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_COMPLETED'); ?></span></li>
						</ul>
					</div>
				</div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>