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
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>

<script type="text/javascript">
function changedownloaded() {
	var addRemoveDownloadDropDown = document.adminForm.addRemoveDownload;
	var addRemoveDownload = addRemoveDownloadDropDown.options[addRemoveDownloadDropDown.selectedIndex].value;

	var valueDownload = document.adminForm.valueDownload.value;

	var unitDownloadDropDown = document.adminForm.unitDownload;
	var unitDownload = unitDownloadDropDown.options[unitDownloadDropDown.selectedIndex].value;

	var currentDownload = Number(document.adminForm.jform_downloaded.value);
	var totalDownload = Number(valueDownload * unitDownload);

	if (addRemoveDownload == "add") document.adminForm.jform_downloaded.value = currentDownload + totalDownload;
	if (addRemoveDownload == "remove") document.adminForm.jform_downloaded.value = currentDownload - totalDownload;
}

function changeuploaded() {
	var addRemoveUploadDropDown = document.adminForm.addRemoveUpload;
	var addRemoveUpload = addRemoveUploadDropDown.options[addRemoveUploadDropDown.selectedIndex].value;

	var valueUpload = document.adminForm.valueUpload.value;

	var unitUploadDropDown = document.adminForm.unitUpload;
	var unitUpload = unitUploadDropDown.options[unitUploadDropDown.selectedIndex].value;

	var currentUpload = Number(document.adminForm.jform_uploaded.value);
	var totalUpload = Number(valueUpload * unitUpload);

	if (addRemoveUpload == "add") document.adminForm.jform_uploaded.value = currentUpload + totalUpload;
	if (addRemoveUpload == "remove") document.adminForm.jform_uploaded.value = currentUpload - totalUpload;
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate form-horizontal" >
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'trackerUser', array('active' => 'user_details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerUser', 'user_details', JText::_('COM_TRACKER_USER_USER_DETAILS', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>

				<div class="control-label"><label id="jform_username-lbl" for="jform_username" class=""><?php echo JText::_('JGLOBAL_USERNAME');?></label></div>
				<div class="controls"><input type="text" name="jform[username]" id="jform_username" value="<?php echo $this->item->name;?>" class="inputbox readonly" size="20"/></div>

				<div class="control-label"><label id="jform_email-lbl" for="jform_email" class=""><?php echo JText::_('JGLOBAL_EMAIL');?></label></div>
				<div class="controls"><input type="text" name="jform[email]" id="jform_email" value="<?php echo $this->item->email;?>" class="inputbox readonly" size="20"/></div>
 
				<?php if ($params->get('enable_countries')) { ?>
					<div class="control-label"><?php echo $this->form->getLabel('countryID'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('countryID'); ?></div>
				<?php } ?>

				<div class="control-label"><?php echo $this->form->getLabel('groupID'); ?></div>
				<div class="controls"><?php echo $params->get('forum_integration') ? $this->form->getValue('groupID') : $this->form->getInput('groupID'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerUser', 'tracker_details', JText::_('COM_TRACKER_USER_TRACKER_DETAILS', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('can_leech'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('can_leech'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('wait_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('wait_time'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('peer_limit'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('peer_limit'); ?></div>

				<div class="control-label"><?php echo $this->form->getLabel('torrent_limit'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('torrent_limit'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerUser', 'tracker_info', JText::_('COM_TRACKER_USER_TRACKER_INFORMATION', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php JText::_( 'COM_TRACKER_USER_LASTIP' ); ?></div>
				<div class="controls"><input disabled value="<?php echo ($this->item->ipa) ? long2ip($this->item->ipa) : JText::_( 'COM_TRACKER_USER_NO_IP_DETAILS' );?>" /></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('downloaded'); ?></div>
				<div class="controls">
					<input type="text" name="jform[downloaded]" id="jform_downloaded" value="<?php echo $this->item->downloaded;?>" class="inputbox" size="20"/>
					<select id="addRemoveDownload">
						<option value="add"><?php echo JText::_( 'COM_TRACKER_ADD' );?></option>
						<option value="remove"><?php echo JText::_( 'COM_TRACKER_REMOVE' );?></option>
					</select>
					<input type="text" id="valueDownload" size="5" />
					<select id="unitDownload">
						<option value="1024"><?php echo JText::_( 'COM_TRACKER_KILOBYTES' );?></option>
						<option value="1048576"><?php echo JText::_( 'COM_TRACKER_MEGABYTES' );?></option>
						<option value="1073741824"><?php echo JText::_( 'COM_TRACKER_GIGABYTES' );?></option>
						<option value="1099511627776"><?php echo JText::_( 'COM_TRACKER_TERABYTES' );?></option>
					</select>
					<input type='button' id='changeDownloaded' name='changeDownloaded' onclick='javascript: changedownloaded();' value='<?php echo JText::_( 'COM_TRACKER_USER_CHANGE_DOWNLOADED' );?>' />
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('uploaded'); ?></div>
				<div class="controls">
					<input type="text" name="jform[uploaded]" id="jform_uploaded" value="<?php echo $this->item->uploaded;?>" class="inputbox" size="20"/>
					<select id="addRemoveUpload">
						<option value="add"><?php echo JText::_( 'COM_TRACKER_ADD' );?></option>
						<option value="remove"><?php echo JText::_( 'COM_TRACKER_REMOVE' );?></option>
					</select>
					<input type="text" id="valueUpload" size="5" />
					<select id="unitUpload">
						<option value="1024"><?php echo JText::_( 'COM_TRACKER_KILOBYTES' );?></option>
						<option value="1048576"><?php echo JText::_( 'COM_TRACKER_MEGABYTES' );?></option>
						<option value="1073741824"><?php echo JText::_( 'COM_TRACKER_GIGABYTES' );?></option>
						<option value="1099511627776"><?php echo JText::_( 'COM_TRACKER_TERABYTES' );?></option>
					</select>
					<input type='button' id='changeUploaded' name='changeUploaded' onclick='javascript: changeuploaded();' value='<?php echo JText::_( 'COM_TRACKER_USER_CHANGE_UPLOADED' );?>' />
				</div>
			</div>

			<?php if ($params->get('enable_donations')) { ?>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_( 'COM_TRACKER_USER_DONATED' );?></div>
					<div class="controls"><input disabled value="<?php echo ($this->item->donated) ? '$'.$this->item->donated : JText::_( 'COM_TRACKER_USER_NOTHING_DONATED' );?>" /></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_( 'COM_TRACKER_USER_CREDITED' );?></div>
					<div class="controls"><input disabled value="<?php echo ($this->item->credited) ? TrackerHelper::make_size($this->item->credited * 1073741824) : JText::_( 'COM_TRACKER_USER_NOTHING_CREDITED' );?>" /></div>
				</div>
			<?php } ?>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('torrent_pass_version'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('torrent_pass_version'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'trackerUser', 'user_ratio', JText::_('COM_TRACKER_USER_RATIO_INFORMATION', true)); ?>
			<div class="control-group">
				<div class="control-label"><?php echo JText::_( 'COM_TRACKER_USER_RATIO' ); ?></div>
				<div class="controls">
					<input size="25" disabled="disabled" value="
						<?php
							if ($this->form->getvalue('downloaded') > 0 && $this->form->getvalue('uploaded') > 0 && $this->item->credited > 0) echo number_format(( (($this->item->credited * 1073741824) + $this->form->getvalue('uploaded')) / $this->form->getvalue('downloaded')), 3, '.', ' ');
							elseif ($this->form->getvalue('downloaded') > 0 && $this->form->getvalue('uploaded') > 0) echo number_format(($this->form->getvalue('uploaded')/$this->form->getvalue('downloaded')), 3, '.', ' ');
							elseif ($this->form->getvalue('downloaded') < 1 && $this->form->getvalue('uploaded') > 0) echo JText::_( 'COM_TRACKER_USER_SEEDER' );
							elseif ($this->form->getvalue('downloaded') < 1 && $this->form->getvalue('uploaded') < 1) echo JText::_( 'COM_TRACKER_USER_NOT_ENOUGH_RATIO_INFORMATION' );
							elseif ($this->form->getvalue('downloaded') > 0 && $this->form->getvalue('uploaded') < 1) echo JText::_( 'COM_TRACKER_USER_LEECHER' );
							else echo JText::_( 'COM_TRACKER_USER_UNKNOWN' );
						?>
					" />
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('minimum_ratio'); ?></div>
				<div class="controls">
					<?php if($this->form->getValue('exemption_type') == 2) { ?>
						<input type="text" name="jform[minimum_ratio]" id="jform_minimum_ratio" value="<?php echo $this->item->minimum_ratio;?>" class="inputbox" disabled="disabled" size="5"/>
					<?php } else { ?>
						<input type="text" name="jform[minimum_ratio]" id="jform_minimum_ratio" value="<?php echo $this->item->minimum_ratio;?>" class="inputbox" size="5"/>
					<?php }	?>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('exemption_type'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('exemption_type'); ?></div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if ($params->get('torrent_multiplier')) { ?>
			<?php echo JHtml::_('bootstrap.addTab', 'trackerUser', 'user_multiplier', JText::_('COM_TRACKER_USER_TORRENT_MULTIPLIER', true)); ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('multiplier_type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('multiplier_type'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('download_multiplier'); ?></div>
					<div class="controls"><input type="text" name="jform[download_multiplier]" id="jform_download_multiplier" value="<?php echo $this->item->download_multiplier;?>" class="inputbox" size="5"/></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('upload_multiplier'); ?></div>
					<div class="controls"><input type="text" name="jform[upload_multiplier]" id="jform_upload_multiplier" value="<?php echo $this->item->upload_multiplier;?>" class="inputbox" size="5"/></div>
				</div>
				
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php } ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>