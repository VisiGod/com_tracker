<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
$params = JComponentHelper::getParams( 'com_tracker' );
?>

<table style="align:center;">
	<tr>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.clearannounce'); ?>">
				<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-trash.png' , JText::_( 'COM_TRACKER_UTILITY_CLEAN_ANNOUNCE' ), null, false, false); ?>
			</a>
		</td>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.optimizetables'); ?>">
				<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-stats.png' , JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES' ), null, false, false); ?>
			</a>
		</td>

		<?php if ($params->get('forum_integration')) {?>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.importgroups'); ?>">
				<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-purge.png' , JText::_( 'COM_TRACKER_UTILITY_IMPORT_FORUM_GROUPS' ), null, false, false); ?>
			</a>
		</td>
		<?php } ?>
		
		<?php if ($params->get('freeleech') == 0) {?>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.enable_free_leech'); ?>">
				<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/free_leech_start-48x48.png' , JText::_( 'COM_TRACKER_UTILITY_ENABLE_FREE_LEECH' ), null, false, false); ?>
			</a>
		</td>
		<?php } else { ?>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.disable_free_leech'); ?>">
				<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/free_leech_stop-48x48.png' , JText::_( 'COM_TRACKER_UTILITY_DISABLE_FREE_LEECH' ), null, false, false); ?>
			</a>
		</td>
		<?php } ?>

		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.bulk_import'); ?>">
				<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-install.png' , JText::_( 'COM_TRACKER_UTILITY_IMPORT_BULK_IMPORT' ), null, false, false); ?>
			</a>
		</td>
	</tr>

	<tr>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.clearannounce'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_CLEAN_ANNOUNCE' ); ?>
			</a>
		</td>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.optimizetables'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES' ); ?>
			</a>
		</td>
		<?php if ($params->get('forum_integration')) {?>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.importgroups'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_IMPORT_FORUM_GROUPS' ); ?>
			</a>
		</td>
		<?php } ?>
		<?php if ($params->get('freeleech') == 0) {?>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.enable_free_leech'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_ENABLE_FREE_LEECH' ); ?>
			</a>
		</td>
		<?php } else { ?>
		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.disable_free_leech'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_DISABLE_FREE_LEECH' ); ?>
			</a>
		</td>
		<?php } ?>

		<td width="20%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.bulk_import'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_IMPORT_BULK_IMPORT' ); ?>
			</a>
		</td>
	</tr>
</table>
