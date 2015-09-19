<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
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
		if (task == 'donation.cancel' || document.formvalidator.isValid(document.id('donation-form'))) {
			Joomla.submitform(task, document.getElementById('donation-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="donation-form" class="form-validate form-horizontal">
	<fieldset>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('uid'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('uid'); ?></div>
		</div>
			
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('ratio'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('ratio'); ?></div>
		</div>
			
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('donated'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('donated'); ?></div>
		</div>
			
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('donation_date'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('donation_date'); ?></div>
		</div>
			
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('comments'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('comments'); ?></div>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>