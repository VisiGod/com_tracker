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
		if (task == 'reseed.cancel' || document.formvalidator.isValid(document.id('reseed-form'))) {
			Joomla.submitform(task, document.getElementById('reseed-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="reseed-form" class="form-validate form-horizontal">
	<fieldset>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('fid'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('fid'); ?></div>

			<div class="control-label"><?php echo $this->form->getLabel('requester'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('requester'); ?></div>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>