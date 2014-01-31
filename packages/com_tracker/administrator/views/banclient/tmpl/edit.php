<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<label style="align: center;">
	<b>You can check the list <a href="https://wiki.theory.org/BitTorrentSpecification#peer_id" target="_blank">here</a></b>
</label>
<form	action="<?php	echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int)	$this->item->id);	?>"	method="post"	name="adminForm" id="banclient-form"	class="form-validate">
	<div class="width-90 fltlft">
		<fieldset	class="adminform">
			<legend><?php	echo JText::_('COM_TRACKER_BANCLIENTS');	?></legend>
			<ul	class="adminformlist">
				<li><?php	echo $this->form->getLabel('id');	?><?php	echo $this->form->getInput('id');	?></li>

				<li><?php	echo $this->form->getLabel('peer_id'); ?><?php echo	$this->form->getInput('peer_id');	?></li>

				<li><?php	echo $this->form->getLabel('peer_description');	?><?php	echo $this->form->getInput('peer_description');	?></li>

				<li><?php echo $this->form->getLabel('comment'); ?><?php echo $this->form->getInput('comment'); ?></li>

			</ul>
		</fieldset>
	</div>

	<input type="hidden" name="task" value=""	/>
	<?php	echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>
