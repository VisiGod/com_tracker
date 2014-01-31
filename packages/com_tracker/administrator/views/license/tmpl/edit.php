<?php
/**
 * @version			2.5.11-dev
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

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="license-form" class="form-validate">
	<div class="width-80 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKER_LICENSE'); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('shortname'); ?><?php echo $this->form->getInput('shortname'); ?></li>

				<li><?php echo $this->form->getLabel('alias'); ?><?php echo $this->form->getInput('alias'); ?></li>

				<li><?php echo $this->form->getLabel('fullname'); ?><?php echo $this->form->getInput('fullname'); ?></li>

				<li><?php echo $this->form->getLabel('link'); ?><?php echo $this->form->getInput('link'); ?></li>

				<li><?php echo $this->form->getLabel('description'); ?><?php echo $this->form->getInput('description'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?><?php echo $this->form->getInput('state'); ?></li>

			</ul>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>