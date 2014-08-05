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

<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
	<div class="row-fluid">
		<div class="span12 center">
			<h1><a href="http://xbtt.sf.net" target="_blank">XBT Tracker</a> frontend for Joomla!</h1>
		</div>
	</div>
	<br />
	<div class="row-fluid" style="margin-left: 15px;">
		<div class="span4">
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_WEBSITE');?>:&nbsp;<a href="http://www.visigod.com" target="_blank">http://www.visigod.com</a></div>
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_FORUM');?>:&nbsp;<a href="http://www.visigod.com/forum" target="_blank">http://www.visigod.com/forum</a></div>
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_DOCUMENTATION');?>:&nbsp;<a href="http://www.visigod.com/xbt-tracker-frontend" target="_blank">http://www.visigod.com/xbt-tracker-frontend</a></div>
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_LICENSE');?>:&nbsp;<a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GNU/GPL v3</a></div>
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_COMPONENT_VERSION');?>:&nbsp;<?php echo $this->component_info['version'];?></div>
			<br />
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_FLAGS_ICONS');?>:&nbsp;<a href="http://kampongboy92.deviantart.com/" target="_blank">kampongboy92</a></div>
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_ICONS');?>:&nbsp;<a href="http://www.deleket.com/" target="_blank">Jojo</a></div>
			<br />
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_GITHUB');?>:&nbsp;<a href="https://github.com/VisiGod/com_tracker" target="_blank">GitHub</a></div>
			<div class="row"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_TRANSIFEX');?>:&nbsp;<a href="https://www.transifex.com/projects/p/com_tracker/" target="_blank">Transifex</a></div>
		</div>
		<div class="span8">
			<div class="row-fluid">
				<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=torrents', 'torrent-48x48.png', JText::_( 'COM_TRACKER_TORRENTS' ) );?></div>
				<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_categories&amp;extension=com_tracker', 'category-48x48.png', JText::_( 'JCATEGORIES' ) );?></div>
				<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=users', 'tuser-48x48.png', JText::_( 'COM_TRACKER_USERS' ) );?></div>
				<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=groups', 'group-48x48.png', JText::_( 'COM_TRACKER_GROUPS' ) );?></div>
				<?php if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=comments', 'comments-48x48.png', JText::_( 'COM_TRACKER_COMMENTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_donations')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=donations', 'donations-48x48.png', JText::_( 'COM_TRACKER_DONATIONS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_licenses')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=licenses', 'licenses-48x48.png', JText::_( 'COM_TRACKER_LICENSES' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_countries')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=countries', 'countries-48x48.png', JText::_( 'COM_TRACKER_COUNTRIES' ) );?></div>
				<?php } ?>
				<?php if ($params->get('peer_banning')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=banclients', 'clientban-48x48.png', JText::_( 'COM_TRACKER_BANCLIENTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('host_banning')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=banhosts', 'ipban-48x48.png', JText::_( 'COM_TRACKER_BANHOSTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_thankyou')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=thankyous', 'thankyou-48x48.png', JText::_( 'COM_TRACKER_THANKYOUS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_filetypes')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=filetypes', 'filetype-48x48.png', JText::_( 'COM_TRACKER_FILETYPES' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_reseedrequest')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=reseeds', 'reseed-48x48.png', JText::_( 'COM_TRACKER_RESEEDS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_reporttorrent')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=reports', 'report-48x48.png', JText::_( 'COM_TRACKER_REPORTS' ) );?></div>
				<?php } ?>
				<?php if ($params->get('enable_rss')) { ?>
					<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=rsses', 'rss-48x48.png', JText::_( 'COM_TRACKER_RSSES' ) );?></div>
				<?php } ?>
				<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=settings', 'settings-48x48.png', JText::_( 'COM_TRACKER_SETTINGS' ) );?></div>
				<div class="span2"><?php TrackerHelper::quickiconButton( 'index.php?option=com_tracker&amp;view=utilities', 'utilities-48x48.png', JText::_( 'COM_TRACKER_UTILITIES' ) );?></div>
			</div>
		</div>
	</div>
</div>