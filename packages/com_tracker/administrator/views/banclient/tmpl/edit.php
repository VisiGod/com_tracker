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

$user		= JFactory::getUser()->get('id');

$app = JFactory::getApplication();
$params = JComponentHelper::getParams( 'com_tracker' );
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'banclient.cancel' || document.formvalidator.isValid(document.id('banclient-form'))) {
			Joomla.submitform(task, document.getElementById('banclient-form'));
		}
	}
</script>

<label style="align: center;">
	<b><?php echo JText::_('COM_TRACKER_BANCLIENT_YOU_CAN_CHECK');?>&nbsp;<a href="https://wiki.theory.org/BitTorrentSpecification#peer_id" target="_blank"><?php echo JText::_('COM_TRACKER_BANCLIENT_HERE');?></a></b>
</label>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="banclient-form" class="form-validate form-horizontal">
	<fieldset>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('peer_id'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('peer_id'); ?></div>
		</div>

		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('peer_description'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('peer_description'); ?></div>
		</div>

		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('comment'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('comment'); ?></div>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>