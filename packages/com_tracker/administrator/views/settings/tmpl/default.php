<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');

$app = JFactory::getApplication();

if (!isset($this->items['table_deny_from_hosts'])) $this->items['table_deny_from_hosts'] = $app->getCfg('dbprefix', 1).'tracker_deny_from_hosts';
if (!isset($this->items['table_deny_from_clients'])) $this->items['table_deny_from_clients'] = $app->getCfg('dbprefix', 1).'tracker_deny_from_clients';

?>
<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=settings'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>

	<div id="j-main-container" class="span10">
		<div class="clearfix"></div>

		<table class="table">
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_ANNOUNCE_INTERVAL' ); ?></b></span>
					<span class="span3"><?php echo $this->items['announce_interval']; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_OFFLINE_MESSAGE' ); ?></b></span>
					<span class="span3"><?php echo $this->items['offline_message']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_CLEAN_UP_INTERVAL' ); ?></b></span>
					<span class="span3"><?php echo $this->items['clean_up_interval']; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_PID_FILE' ); ?></b></span>
					<span class="span3"><?php echo $this->items['pid_file']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_DAEMON' ); ?></b></span>
					<span class="span3"><?php echo $this->items['daemon']; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_QUERY_LOG' ); ?></b></span>
					<span class="span3"><?php echo $this->items['query_log']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_DEBUG' ); ?></b></span>
					<span class="span3"><?php echo $this->items['debug']; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_READ_CONFIG_INTERVAL' ); ?></b></span>
					<span class="span3"><?php echo $this->items['read_config_interval']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_FULL_SCRAPE' ); ?></b></span>
					<span class="span3"><?php echo $this->items['full_scrape']; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_READ_DB_INTERVAL' ); ?></b></span>
					<span class="span3"><?php echo $this->items['read_db_interval']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_GZIP_SCRAPE' ); ?></b></span>
					<span class="span3"><?php echo $this->items['gzip_scrape'] ? JText::_('JYES') : JText::_('JNO') ; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_REDIRECT_URL' ); ?></b></span>
					<span class="span3"><?php echo $this->items['redirect_url']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LISTEN_IPA' ); ?></b></span>
					<span class="span3"><?php echo $this->items['listen_ipa']; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_SCRAPE_INTERVAL' ); ?></b></span>
					<span class="span3"><?php echo $this->items['scrape_interval']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LISTEN_PORT' ); ?></b></span>
					<span class="span3"><?php echo $this->items['listen_port']; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_WRITE_DB_INTERVAL' ); ?></b></span>
					<span class="span3"><?php echo $this->items['write_db_interval']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_ACCESS' ); ?></b></span>
					<span class="span3"><?php echo $this->items['log_access'] ? JText::_('JYES') : JText::_('JNO') ; ?></span>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TORRENT_PASS_PRIVATE_KEY' ); ?></b></span>
					<span class="span3"><?php echo $this->items['torrent_pass_private_key']; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="span3"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_SCRAPE' ); ?></b></span>
					<span class="span3"><?php echo$this->items['log_scrape'] ? JText::_('JYES') : JText::_('JNO') ; ?></span>
				</td>
			</tr>
		</table>
	</div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="1" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>