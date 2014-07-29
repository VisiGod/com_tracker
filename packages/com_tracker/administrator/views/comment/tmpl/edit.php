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

$user		= JFactory::getUser()->get('id');

$app = JFactory::getApplication();
$params = JComponentHelper::getParams( 'com_tracker' );
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'comment.cancel' || document.formvalidator.isValid(document.id('comment-form'))) {
			Joomla.submitform(task, document.getElementById('comment-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="comment-form" class="form-validate form-horizontal">
	<fieldset>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('torrentID'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('torrentID'); ?></div>
		</div>
			
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('comment'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('comment'); ?></div>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="created_user_id" value="<?php echo $user; ?>" />
	<input type="hidden" name="created_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>