<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
$params =& JComponentHelper::getParams( 'com_tracker' );
?>

<table style="align:center;">
	<tr>
		<td width="25%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.clearannounce'); ?>">
				<?php echo JHTML::_('image.site',  'icon-48-trash.png', '/templates/'. $template .'/images/header/', NULL, NULL, JText::_( 'COM_TRACKER_UTILITY_CLEAN_ANNOUNCE' ) ); ?>
			</a>
		</td>
		<td width="25%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.optimizetables'); ?>">
				<?php echo JHTML::_('image.site',  'icon-48-stats.png', '/templates/'. $template .'/images/header/', NULL, NULL, JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES' ) ); ?>
			</a>
		</td>

		<?php if ($params->get('forum_integration')) {?>
		<td width="25%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.importgroups'); ?>">
				<?php echo JHTML::_('image.site',  'icon-48-purge.png', '/templates/'. $template .'/images/header/', NULL, NULL, JText::_( 'COM_TRACKER_UTILITY_IMPORT_FORUM_GROUPS' ) ); ?>
			</a>
		</td>
		<?php } ?>
	</tr>

	<tr>
		<td width="25%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.clearannounce'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_CLEAN_ANNOUNCE' ); ?>
			</a>
		</td>
		<td width="25%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.optimizetables'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES' ); ?>
			</a>
		</td>
		<?php if ($params->get('forum_integration')) {?>
		<td width="25%" align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.importgroups'); ?>">
				<?php echo JText::_( 'COM_TRACKER_UTILITY_IMPORT_FORUM_GROUPS' ); ?>
			</a>
		</td>
		<?php } ?>
	</tr>
</table>