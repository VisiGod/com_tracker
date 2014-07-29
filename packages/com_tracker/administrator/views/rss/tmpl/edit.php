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

$doc = JFactory::getDocument();
$jquery_drag_style='span.choosable {border: 1px solid red;}';
$doc->addStyleDeclaration($jquery_drag_style);
$style = '.hide { display:none; }';
$doc->addStyleDeclaration( $style );
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'rss.cancel' || document.formvalidator.isValid(document.id('rss-form'))) {
			Joomla.submitform(task, document.getElementById('rss-form'));
		}
	}
</script>

<script>
$("#rss_params").ready(function(){
	function default_dropdown() {
		if ($("#jform_rss_authentication").val() == "2") $("#authentication_groups").show();
		if ($("#jform_rss_type").val() == "1") $("#rss_type_category").show();
		if ($("#jform_rss_type").val() == "2") $("#rss_type_license").show();
	}
	default_dropdown();
	
	$("#jform_rss_authentication").change(function(){
		if ($(this).val() == "0" ) {
			$("#authentication_groups").hide();
		}
		if ($(this).val() == "1" ) {
			$("#authentication_groups").hide();
		}
		if ($(this).val() == "2" ) {
			$("#authentication_groups").show();
		}
    });

	$("#jform_rss_type").change(function(){
		if ($(this).val() == "0" ) {
			$("#rss_type_category").hide();
			$("#rss_type_license").hide();
		}
		if ($(this).val() == "1" ) {
			$("#rss_type_category").show();
			$("#rss_type_license").hide();
		}
		if ($(this).val() == "2" ) {
			$("#rss_type_category").hide();
			$("#rss_type_license").show();
		}
    });
	
});

$(document).ready(function() {
			$("#DragWordList span").draggable({helper: "clone"});
			$("#DragWordList span").draggable({cursor: "hand"});
			$("#NoDrag span" ).draggable({ disabled: true });
			$(".txtDropTarget").droppable({
				accept: "#DragWordList span",
				drop: function(ev, ui) {
					$(this).insertAtCaret(ui.draggable.text());
				}
			});
		});

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
</script>
<style type="text/css" media="Screen">
	#field_list span{cursor:pointer;}
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
				<div class="controls">
					<?php
						echo $this->form->getInput('rss_authentication');
						echo '<span class="hide" id="authentication_groups">&nbsp;&nbsp;&nbsp;';
						echo '<span>'.$this->form->getLabel('rss_authentication_group').$this->form->getInput('rss_authentication_group').'</span>';
						echo '<div class="clear"></div>';
						echo '</span>';
					?>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('rss_type'); ?></div>
				<div class="controls">
					<?php
						echo $this->form->getInput('rss_type');
						echo '<span class="hide" id="rss_type_category">&nbsp;&nbsp;&nbsp;';
						echo '<span>'.$this->form->getLabel('rss_type_category').$this->form->getInput('rss_type_category').'</span>';
						echo '<div class="clear"></div>';
						echo '</span>';
						echo '<span class="hide" id="rss_type_license">&nbsp;&nbsp;&nbsp;';
						echo '<span>'.$this->form->getLabel('rss_type_license').$this->form->getInput('rss_type_license').'</span>';
						echo '<div class="clear"></div>';
						echo '</span>';
					?>
				</div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'rss', 'items', JText::_('COM_TRACKER_RSS_ITEMS', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('item_count'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('item_count'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('item_title'); ?></div>
				<div class="controls"><textarea name="jform[item_title]" id="jform_item_title" class="txtDropTarget" cols="85" rows="1"><?php echo $this->form->getValue('item_title'); ?></textarea></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('item_description'); ?></div>
				<div class="controls"><textarea name="jform[item_description]" id="jform_item_description" class="txtDropTarget" cols="85" rows="19"><?php echo $this->form->getValue('item_description'); ?></textarea></div>

				<legend><?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER'); ?></legend>
				<?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_DESCRIPTION_PRE').JText::_('COM_TRACKER_RSS_ITEM_DESCRIPTION').JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_DESCRIPTION_POST'); ?>
				<br />
				<ul id="DragWordList">
					<li><span class="choosable" id="DragWordList">{name}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_NAME'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{description}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_DESCRIPTION'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{link}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_LINK'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{category}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_CATEGORY'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{size}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_SIZE'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{upload_date}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_UPLOADED_DATE'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{uploader}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_UPLOADER'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{license}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_LICENSE'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{seeders}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_SEEDERS'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{leechers}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_LEECHERS'); ?></span></li>
					<li><span class="choosable" id="DragWordList">{completed}</span><span id="NoDrag"> - <?php echo JText::_('COM_TRACKER_RSS_FIELD_CHOOSER_TORRENT_COMPLETED'); ?></span></li>
			    </ul>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>