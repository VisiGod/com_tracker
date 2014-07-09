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
		if (task == 'license.cancel' || document.formvalidator.isValid(document.id('license-form'))) {
			Joomla.submitform(task, document.getElementById('license-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="license-form" class="form-validate form-horizontal">
	<fieldset>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('shortname'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('shortname'); ?></div>

			<div class="control-label left"><?php echo $this->form->getLabel('alias'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>

			<div class="control-label left"><?php echo $this->form->getLabel('fullname'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('fullname'); ?></div>

			<div class="control-label left"><?php echo $this->form->getLabel('link'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('link'); ?></div>

			<div class="control-label left"><?php echo $this->form->getLabel('description'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>