<?php
/**
 * @version			2.5.11-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

$params = JComponentHelper::getParams('com_tracker');

function quickiconButton( $link, $image, $text ) {
	$lang = JFactory::getLanguage();
	?>
	<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
	<div class="icon"><a href="<?php echo $link; ?>"> <?php echo JHTML::_('image.site',	$image, 'components/com_tracker/images/panel/', NULL, NULL, $text );?>
	<span><?php echo $text; ?></span></a></div>
	</div>
<?php } ?>

<div class="container">
	<div style="float: left; width: 40%;">
		<h1>
			<a href="http://xbtt.sf.net" target="_blank">XBT Tracker</a> frontend for Joomla!
		</h1>
		<table class="admintable">
			<tr>
				<td class="key" align="right"><?php echo JText::_('COM_TRACKER_CONTROL_PANEL_WEBSITE');?></td>
				<td align="left">&nbsp;&nbsp;<a href="http://www.visigod.com" target="_blank">http://www.visigod.com</a></td>
			</tr>
			<tr>
				<td class="key" align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_FORUM' );?></td>
				<td align="left">&nbsp;&nbsp;<a href="http://www.visigod.com/forum" target="_blank">http://www.visigod.com/forum</a></td>
			</tr>
			<tr>
				<td class="key" align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_DOCUMENTATION' );?></td>
				<td align="left">&nbsp;&nbsp;<a href="http://www.visigod.com/xbt-tracker-frontend" target="_blank">http://www.visigod.com/xbt-tracker-frontend</a></td>
			</tr>
			<tr>
				<td class="key" align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_LICENSE' );?></td>
				<td align="left">&nbsp;&nbsp;<a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GNU/GPL v3</a></td>
			</tr>
			<tr>
				<td class="key" nowrap align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_COMPONENT_VERSION' );?></td>
				<td align="left">
					&nbsp;&nbsp;<?php echo $this->component_info['version'];
				//echo TrackerHelper::checkComponentConfigured();
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="key" align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_FLAGS_ICONS' );?></td>
				<td align="left">&nbsp;&nbsp;<a href="http://kampongboy92.deviantart.com/" target="_blank">kampongboy92</a></td>
			</tr>
			<tr>
				<td class="key" align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_ICONS' );?></td>
				<td align="left">&nbsp;&nbsp;<a href="http://www.deleket.com/" target="_blank">Jojo</a></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="key" align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_GITHUB' );?></td>
				<td align="left">&nbsp;&nbsp;<a href="https://github.com/VisiGod/com_tracker" target="_blank">GitHub</a></td>
			</tr>
			<tr>
				<td class="key" align="right"><?php echo JText::_( 'COM_TRACKER_CONTROL_PANEL_TRANSIFEX' );?></td>
				<td align="left">&nbsp;&nbsp;<a href="https://www.transifex.com/projects/p/com_tracker/" target="_blank">Transifex</a></td>
			</tr>
			
		</table>
	</div>
	<div id="cpanel" style="float: right; width: 60%;">
		<?php
		quickiconButton( 'index.php?option=com_tracker&amp;view=torrents', 'torrent-48x48.png', JText::_( 'COM_TRACKER_TORRENTS' ) );
		quickiconButton( 'index.php?option=com_categories&amp;extension=com_tracker', 'category-48x48.png', JText::_( 'JCATEGORIES' ) );
		quickiconButton( 'index.php?option=com_tracker&amp;view=users', 'tuser-48x48.png', JText::_( 'COM_TRACKER_USERS' ) );
		quickiconButton( 'index.php?option=com_tracker&amp;view=groups', 'group-48x48.png', JText::_( 'COM_TRACKER_GROUPS' ) );
		if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') quickiconButton( 'index.php?option=com_tracker&amp;view=comments', 'comments-48x48.png', JText::_( 'COM_TRACKER_COMMENTS' ) );
		if ($params->get('enable_donations')) quickiconButton( 'index.php?option=com_tracker&amp;view=donations', 'donations-48x48.png', JText::_( 'COM_TRACKER_DONATIONS' ) );
		if ($params->get('enable_licenses')) quickiconButton( 'index.php?option=com_tracker&amp;view=licenses', 'licenses-48x48.png', JText::_( 'COM_TRACKER_LICENSES' ) );
		if ($params->get('enable_countries')) quickiconButton( 'index.php?option=com_tracker&amp;view=countries', 'countries-48x48.png', JText::_( 'COM_TRACKER_COUNTRIES' ) );
		if ($params->get('peer_banning')) quickiconButton( 'index.php?option=com_tracker&amp;view=banclients', 'clientban-48x48.png', JText::_( 'COM_TRACKER_BANCLIENTS' ) );
		if ($params->get('host_banning')) quickiconButton( 'index.php?option=com_tracker&amp;view=banhosts', 'ipban-48x48.png', JText::_( 'COM_TRACKER_BANHOSTS' ) );
		if ($params->get('enable_thankyou')) quickiconButton( 'index.php?option=com_tracker&amp;view=thankyous', 'thankyou-48x48.png', JText::_( 'COM_TRACKER_THANKYOUS' ) );
		if ($params->get('enable_filetypes')) quickiconButton( 'index.php?option=com_tracker&amp;view=filetypes', 'filetype-48x48.png', JText::_( 'COM_TRACKER_FILETYPES' ) );
		if ($params->get('enable_reseedrequest')) quickiconButton( 'index.php?option=com_tracker&amp;view=reseeds', 'reseed-48x48.png', JText::_( 'COM_TRACKER_RESEEDS' ) );
		if ($params->get('enable_reporttorrent')) quickiconButton( 'index.php?option=com_tracker&amp;view=reports', 'report-48x48.png', JText::_( 'COM_TRACKER_REPORTS' ) );
		quickiconButton( 'index.php?option=com_tracker&amp;view=settings', 'settings-48x48.png', JText::_( 'COM_TRACKER_SETTINGS' ) );
		quickiconButton( 'index.php?option=com_tracker&amp;view=utilities', 'utilities-48x48.png', JText::_( 'COM_TRACKER_UTILITIES' ) );
		?>
	</div>
	<div style="clear: both;"></div>
</div>
