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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');

?>
<style type="text/css">.toggle-editor{display:none;}</style>
<div class="comment-from" style="width: 90%;">
	<h2><span><?php echo JText::_('COM_XBT_TRACKER_COMMENTS_COMMENT_FOR').'&nbsp;'.$this->form->torrentname; ?></span></h2>
	<br />
	<form id="comment-from" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
		<div>
			<span><?php echo $this->form->getLabel('description');?>:</span>
			<span><?php echo $this->form->getInput('description'); ?></span>
		</div>

		<div style="float: right;">
			<button class="button validate" type="submit"><?php echo JText::_('COM_XBT_TRACKER_SUBMIT_COMMENT_BUTTON'); ?></button>
		</div>

		<input type="hidden" name="torrentid" value="<?php echo $this->form->torrentid; ?>" />
		<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>" />
		<input type="hidden" name="option" value="com_tracker" />
		<input type="hidden" name="task" value="torrent.commented" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
