<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');

$params = JComponentHelper::getParams('com_tracker');
?>
<div class="row-fluid">
	<div id="sidebar" class="span2">
		<div class="sidebar-nav">
			<?php echo $this->sidebar; ?>
		</div>
	</div>

	<div class="span10">
		<div class="span12 center">
			<h1><a href="http://xbtt.sf.net" target="_blank">XBT Tracker</a> frontend for Joomla!</h1>
		</div>

		<div class="span12 left">
			<div class="span3">
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_VISIT');?><a href="http://www.visigod.com" target="_blank"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_WEBSITE');?></a></div>
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_ASK');?><a href="http://www.visigod.com/forum" target="_blank"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_FORUM');?></a></div>
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_READ');?><a href="http://www.visigod.com/xbt-tracker-frontend" target="_blank"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_DOCUMENTATION');?></a></div>
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_COMPONENT_VERSION');?>:&nbsp;<?php echo $this->component_info['version'];?></div>	
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_FLAGS_ICONS');?>:&nbsp;<a href="http://kampongboy92.deviantart.com/" target="_blank">kampongboy92</a></div>
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_ICONS');?>:&nbsp;<a href="http://www.deleket.com/" target="_blank">Jojo</a></div>
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_GITHUB');?>:&nbsp;<a href="https://github.com/VisiGod/com_tracker" target="_blank">GitHub</a></div>
				<div><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_TRANSIFEX');?>:&nbsp;<a href="https://www.transifex.com/projects/p/com_tracker/" target="_blank">Transifex</a></div>
				</div>
			<div class="span9">
				<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=torrents', 'torrent-48x48.png', JText::_( 'COM_TRACKER_TORRENTS' ) );?></div>
				<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_categories&amp;extension=com_tracker', 'category-48x48.png', JText::_( 'JCATEGORIES' ) );?></div>
				<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=users', 'tuser-48x48.png', JText::_( 'COM_TRACKER_USERS' ) );?></div>
				<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=groups', 'group-48x48.png', JText::_( 'COM_TRACKER_GROUPS' ) );?></div>
				<?php if ($params->get('enable_comments') == 1 && $params->get('comment_system') == 'internal') { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=comments', 'comments-48x48.png', JText::_( 'COM_TRACKER_COMMENTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_donations') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=donations', 'donations-48x48.png', JText::_( 'COM_TRACKER_DONATIONS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_licenses') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=licenses', 'licenses-48x48.png', JText::_( 'COM_TRACKER_LICENSES' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_countries') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=countries', 'countries-48x48.png', JText::_( 'COM_TRACKER_COUNTRIES' ) );?></div>
				<?php } ?>
				<?php if ($params->get('peer_banning') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=banclients', 'clientban-48x48.png', JText::_( 'COM_TRACKER_BANCLIENTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('host_banning') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=banhosts', 'ipban-48x48.png', JText::_( 'COM_TRACKER_BANHOSTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_thankyou') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=thankyous', 'thankyou-48x48.png', JText::_( 'COM_TRACKER_THANKYOUS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_filetypes') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=filetypes', 'filetype-48x48.png', JText::_( 'COM_TRACKER_FILETYPES' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_reseedrequest') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=reseeds', 'reseed-48x48.png', JText::_( 'COM_TRACKER_RESEEDS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_reporttorrent') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=reports', 'report-48x48.png', JText::_( 'COM_TRACKER_REPORTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_rss') == 1) { ?>
					<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=rsses', 'rss-48x48.png', JText::_( 'COM_TRACKER_RSSES' ) );?></div>
				<?php } ?>
				<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=settings', 'settings-48x48.png', JText::_( 'COM_TRACKER_SETTINGS' ) );?></div>
				<div class="span2" style="display: block;margin:0 auto;"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=utilities', 'utilities-48x48.png', JText::_( 'COM_TRACKER_UTILITIES' ) );?></div>
			</div>

		</div>
	</div>
</div>