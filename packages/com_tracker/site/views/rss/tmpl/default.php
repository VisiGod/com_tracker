<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<h1><?php echo JText::_('COM_TRACKER_RSS_AVAILABLE'); ?></h1>
<h3><?php echo JText::_('COM_TRACKER_RSS_WARNING'); ?></h3>
<br />
<h4><?php echo JText::_('COM_TRACKER_RSS_YOUR_LINKS_ARE'); ?></h4><br />

<?php foreach ($this->items as $i => $item) :?>
	<?php if (($item->rss_authentication != 0 && ($this->user->id != $this->params->get('guest_user'))) || $item->rss_authentication == 0) : ?>
		<div><?php echo '<b>'.JText::_('COM_TRACKER_RSS_NAME').'</b>:&nbsp;'.$item->name; ?></div>
		<div><?php echo '<b>'.JText::_('COM_TRACKER_RSS_CHANNEL_TITLE').'</b>:&nbsp;'.$item->channel_title; ?></div>
		<div><?php echo '<b>'.JText::_('COM_TRACKER_RSS_CHANNEL_DESCRIPTION').'</b>:&nbsp;'.$item->channel_description; ?></div>
		<div>
			<?php
				echo '<b>'.JText::_('COM_TRACKER_RSS_TYPE').'</b>:&nbsp;';
				if ($item->rss_type == 0) echo JText::_('COM_TRACKER_RSS_TYPE_LATEST');
				else if ($item->rss_type == 1) echo JText::_('COM_TRACKER_RSS_TYPE_CATEGORY');
				else echo JText::_('COM_TRACKER_RSS_TYPE_LICENSE');
			?>
		</div>
		<div><?php echo '<b>'.JText::_('COM_TRACKER_RSS_ITEM_COUNT').'</b>:&nbsp;'.$item->item_count; ?></div>
		<div>
			<?php
				echo '<b>'.JText::_('COM_TRACKER_RSS_LINK').'</b>:&nbsp;';
				if ($item->rss_authentication == 0) echo '<a href="'.JRoute::_("index.php?view=rss&rss=".(int)$item->id).'">'.JText::_('COM_TRACKER_RSS_LINK_HERE').'</a>';
				else echo '<a href="'.JRoute::_("index.php?view=rss&rss=".(int)$item->id).'&hash='.$item->hash.'&uid='.$item->uid.'">'.JText::_('COM_TRACKER_RSS_LINK_HERE').'</a>';
			?>
		</div>
		<br />
	<?php endif; ?>
<?php endforeach; ?>
