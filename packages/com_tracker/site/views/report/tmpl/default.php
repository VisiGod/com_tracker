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
$params = JComponentHelper::getParams( 'com_tracker' );
JHtml::_('behavior.modal');

?>
<style type="text/css">.toggle-editor{display:none;}</style>
<div class="report-form">
	<form id="report-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" target="_parent" class="form-validate">
		<h1><?php echo JText::_('COM_TRACKER_REPORT_TORRENT'); ?></h1>
		<fieldset>
			<dl>
				<dt><?php echo $this->form->getLabel('fid').':'; ?></dt>
				<dd><?php echo $this->item->name; ?></dd>
				<dt><?php echo $this->form->getLabel('report_type').':'; ?></dt>
				<dd>
					<select name="report_type">
					<?php 
						$report_causes = explode(',',JText::_('COM_TRACKER_REPORT_CAUSE'));
						foreach($report_causes as $report_cause) {
							echo "<option value='".$report_cause."'>".$report_cause."</option>";
						}
					?>
					</select>
				</dd>
				<dt><?php echo $this->form->getLabel('comments').':'; ?></dt>
				<dd><?php echo $this->form->getInput('comments'); ?></dd>
				<dt></dt>
				<dd style="float: right; margin-right: 60px;">
					<button class="button validate" type="submit" ><?php echo JText::_('COM_TRACKER_REPORT_BUTTON'); ?></button>
					<input type="hidden" name="jform[reporter]" value="<?php echo $this->item->reporter; ?>" />
					<input type="hidden" name="jform[reporter_name]" value="<?php echo $this->item->reporter_name; ?>" />
					<input type="hidden" name="jform[fid]" value="<?php echo $this->item->fid; ?>" />
					<input type="hidden" name="option" value="com_tracker" />
					<input type="hidden" name="task" value="torrent.reported" />
					<?php echo JHtml::_( 'form.token' ); ?>
				</dd>
			</dl>
		</fieldset>
	</form>
</div>
