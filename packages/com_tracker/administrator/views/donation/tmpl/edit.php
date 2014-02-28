<?php
/**
 * @version			2.5.13-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="donation-form" class="form-validate">
	<div class="width-80 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKER_DONATION'); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('uid'); ?><?php echo $this->form->getInput('uid'); ?></li>

				<li><?php echo $this->form->getLabel('ratio'); ?><?php echo $this->form->getInput('ratio'); ?></li>

				<li><?php echo $this->form->getLabel('donated'); ?><?php echo $this->form->getInput('donated'); ?></li>

				<li><?php echo $this->form->getLabel('donation_date'); ?><?php echo $this->form->getInput('donation_date'); ?></li>

				<li><?php echo $this->form->getLabel('comments'); ?><?php echo $this->form->getInput('comments'); ?></li>
			</ul>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>