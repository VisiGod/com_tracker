<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

$user		= $this->user;
$userId		= $user->get('id');

$params = JComponentHelper::getParams('com_tracker');
$app = JFactory::getApplication();

if (!isset($this->items['table_deny_from_hosts'])) $this->items['table_deny_from_hosts'] = $app->getCfg('dbprefix', 1).'tracker_deny_from_hosts';
if (!isset($this->items['table_deny_from_clients'])) $this->items['table_deny_from_clients'] = $app->getCfg('dbprefix', 1).'tracker_deny_from_clients';

?>
<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=settings'); ?>" method="post" name="adminForm" id="adminForm">
	<table style="width:100%;">
		<tr>
			<td style="width:26%;" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="announce_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_ANNOUNCE_INTERVAL' ); ?></b></label></td>
							<td style="width:50%;"><?php echo $this->items['announce_interval']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="clean_up_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_CLEAN_UP_INTERVAL' ); ?></b></label></td>
							<td><?php echo $this->items['clean_up_interval']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="daemon" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_DAEMON' ); ?></b></label></td>
							<td><?php echo $this->items['daemon'] ? JText::_('JYES') : JText::_('JNO') ; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="debug" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_DEBUG' ); ?></b></label></td>
							<td><?php echo $this->items['debug'] ? JText::_('JYES') : JText::_('JNO') ; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="full_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_FULL_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->items['full_scrape'] ? JText::_('JYES') : JText::_('JNO') ; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="gzip_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_GZIP_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->items['gzip_scrape'] ? JText::_('JYES') : JText::_('JNO') ; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="listen_ipa" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LISTEN_IPA' ); ?></b></label></td>
							<td><?php echo $this->items['listen_ipa']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="listen_port" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LISTEN_PORT' ); ?></b></label></td>
							<td><?php echo $this->items['listen_port']; ?></td>
						</tr>	
					</thead>
				</table>
			</td>
			<td width="37%" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="log_access" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_ACCESS' ); ?></b></label></td>
							<td width="50%"><?php echo $this->items['log_access'] ? JText::_('JYES') : JText::_('JNO') ; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="log_announce" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_ANNOUNCE' ); ?></b></label></td>
							<td><?php echo $this->items['log_announce'] ? JText::_('JYES') : JText::_('JNO') ; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="log_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->items['log_scrape'] ? JText::_('JYES') : JText::_('JNO') ; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="offline_message" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_OFFLINE_MESSAGE' ); ?></b></label></td>
							<td><?php echo $this->items['offline_message']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="pid_file" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_PID_FILE' ); ?></b></label></td>
							<td><?php echo $this->items['pid_file']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="query_log" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_QUERY_LOG' ); ?></b></label></td>
							<td><?php echo $this->items['query_log']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="read_config_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_READ_CONFIG_INTERVAL' ); ?></b></label></td>
							<td><?php echo $this->items['read_config_interval']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="read_db_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_READ_DB_INTERVAL' ); ?></b></label></td>
							<td><?php echo $this->items['read_db_interval']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="redirect_url" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_REDIRECT_URL' ); ?></b></label></td>
							<td><?php echo $this->items['redirect_url']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="scrape_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_SCRAPE_INTERVAL' ); ?></b></label></td>
							<td><?php echo $this->items['scrape_interval']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="write_db_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_WRITE_DB_INTERVAL' ); ?></b></label></td>
							<td><?php echo $this->items['write_db_interval']; ?></td>
						</tr>
					</thead>
				</table>
			</td>
			<td width="37%" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="table_announce_log" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_ANNOUNCE_LOG' ); ?></b></label></td>
							<td><?php echo $this->items['table_announce_log']; ?></td>
						</tr>
						<?php if ($params->get('host_banning')) { ?>
						<tr>
							<td class="key" nowrap><label for="table_banhosts" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_BANHOSTS' ); ?></b></label></td>
							<td width="50%"><?php echo $this->items['table_deny_from_hosts']; ?></td>
						</tr>
						<?php } ?>
						<?php if ($params->get('peer_banning')) { ?>
						<tr>
							<td class="key" nowrap><label for="table_banclients" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_BANCLIENTS' ); ?></b></label></td>
							<td><?php echo $this->items['table_deny_from_clients']; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td class="key" nowrap><label for="table_files" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_FILES' ); ?></b></label></td>
							<td><?php echo $this->items['table_files']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="table_files_users" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_FILES_USERS' ); ?></b></label></td>
							<td><?php echo $this->items['table_files_users']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="table_scrape_log" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_SCRAPE_LOG' ); ?></b></label></td>
							<td><?php echo $this->items['table_scrape_log']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="table_users" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_USERS' ); ?></b></label></td>
							<td><?php echo $this->items['table_users']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_completed" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_COMPLETED' ); ?></b></label></td>
							<td><?php echo $this->items['column_files_completed']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_fid" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_FID' ); ?></b></label></td>
							<td><?php echo $this->items['column_files_fid']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_leechers" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_LEECHERS' ); ?></b></label></td>
							<td><?php echo $this->items['column_files_leechers']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_seeders" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_SEEDERS' ); ?></b></label></td>
							<td><?php echo $this->items['column_files_seeders']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_users_uid" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_USER_UID' ); ?></b></label></td>
							<td><?php echo $this->items['column_users_uid']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="torrent_pass_private_key" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TORRENT_PASS_PRIVATE_KEY' ); ?></b></label></td>
							<td><?php echo $this->items['torrent_pass_private_key']; ?></td>
						</tr>
					</thead>
				</table>
			</td>
		</tr>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="1" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>