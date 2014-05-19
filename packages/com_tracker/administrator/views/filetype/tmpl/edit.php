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

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="filetype-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TRACKER_FILETYPE'); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('id'); ?><?php echo $this->form->getInput('id'); ?></li>

				<li><?php echo $this->form->getLabel('name'); ?><?php echo $this->form->getInput('name'); ?></li>

				<li><?php echo $this->form->getLabel('image'); ?><?php echo $this->form->getInput('image'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?><?php echo $this->form->getInput('state'); ?></li>

			</ul>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>