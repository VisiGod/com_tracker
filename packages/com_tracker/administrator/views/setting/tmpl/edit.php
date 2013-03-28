<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>
<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=setting&layout=edit'); ?>" method="post" name="adminForm" id="settingform" class="form-validate">
<table style="width:100%;">
		<tr>
			<td width="26%" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="announce_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_ANNOUNCE_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="announce_interval" id="announce_interval" class="inputbox" size="11" value="<?php echo $this->item['announce_interval']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="anonymous_announce" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_ANONYMOUS_ANNOUNCE' ); ?></b></label></td>
							<td><?php echo $this->item['anonymous_announce']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="anonymous_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_ANONYMOUS_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->item['anonymous_scrape']; ?></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="auto_register" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_AUTO_REGISTER' ); ?></b></label></td>
							<td><?php echo $this->item['auto_register']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="clean_up_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_CLEAN_UP_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="clean_up_interval" id="clean_up_interval" class="inputbox" size="11" value="<?php echo $this->item['clean_up_interval']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="daemon" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_DAEMON' ); ?></b></label></td>
							<td><?php echo $this->item['daemon']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="debug" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_DEBUG' ); ?></b></label></td>
							<td><?php echo $this->item['debug']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="full_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_FULL_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->item['full_scrape']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="gzip_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_GZIP_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->item['gzip_scrape']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="listen_ipa" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LISTEN_IPA' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="listen_ipa" id="listen_ipa" class="inputbox" size="18" value="<?php echo $this->item['listen_ipa']; ?>" /></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="listen_port" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LISTEN_PORT' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="listen_port" id="listen_port" class="inputbox" maxlength="5" size="10" value="<?php echo $this->item['listen_port']; ?>" /></td>
						</tr>	
					</thead>
				</table>
			</td>
			<td width="36%" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="log_access" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_ACCESS' ); ?></b></label></td>
							<td><?php echo $this->item['log_access']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="log_announce" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_ANNOUNCE' ); ?></b></label></td>
							<td><?php echo $this->item['log_announce']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="log_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->item['log_scrape']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="offline_message" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_OFFLINE_MESSAGE' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="offline_message" id="offline_message" class="inputbox" size="30" value="<?php echo $this->item['offline_message']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="pid_file" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_PID_FILE' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="pid_file" id="pid_file" class="inputbox" size="20" value="<?php echo $this->item['pid_file']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="query_log" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_QUERY_LOG' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="query_log" id="query_log" class="inputbox" size="20" value="<?php echo $this->item['query_log']; ?>" /></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="read_config_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_READ_CONFIG_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="read_config_interval" id="read_config_interval" class="inputbox" size="11" value="<?php echo $this->item['read_config_interval']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="read_db_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_READ_DB_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="read_db_interval" id="read_db_interval" class="inputbox" size="11" value="<?php echo $this->item['read_db_interval']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="redirect_url" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_REDIRECT_URL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="redirect_url" id="redirect_url" class="inputbox" size="30" value="<?php echo $this->item['redirect_url']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="scrape_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_SCRAPE_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="scrape_interval" id="scrape_interval" class="inputbox" size="11" value="<?php echo $this->item['scrape_interval']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="write_db_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_WRITE_DB_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="write_db_interval" id="write_db_interval" class="inputbox" size="11" value="<?php echo $this->item['write_db_interval']; ?>" /></td>
						</tr>
					</thead>
				</table>
			</td>
			<td width="37%" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="table_announce_log" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_ANNOUNCE_LOG' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="table_announce_log" id="table_announce_log" class="inputbox" size="40" value="<?php echo $this->item['table_announce_log']; ?>" /></td>
						</tr>
						<?php if ($this->params->get('host_banning')) { ?>
						<tr>
							<td class="key" nowrap><label for="table_deny_from_hosts" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_BANHOSTS' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="table_deny_from_hosts" id="table_deny_from_hosts" class="inputbox" size="40" value="<?php echo $this->item['table_deny_from_hosts']; ?>" /></td>
						</tr>
						<?php } ?>
						<?php if ($this->params->get('peer_banning')) { ?>
						<tr>
							<td class="key" nowrap><label for="table_deny_from_clients" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_BANCLIENTS' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="table_deny_from_clients" id="table_deny_from_clients" class="inputbox" size="40" value="<?php echo $this->item['table_deny_from_clients']; ?>" /></td>
						</tr>
						<?php } ?>
						<tr>
							<td class="key" nowrap><label for="table_files" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_FILES' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="table_files" id="table_files" class="inputbox" size="40" value="<?php echo $this->item['table_files']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="table_files_users" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_FILES_USERS' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="table_files_users" id="table_files_users" class="inputbox" size="40" value="<?php echo $this->item['table_files_users']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="table_scrape_log" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_SCRAPE_LOG' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="table_scrape_log" id="table_scrape_log" class="inputbox" size="40" value="<?php echo $this->item['table_scrape_log']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="table_users" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TABLE_USERS' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="table_users" id="table_users" class="inputbox" size="40" value="<?php echo $this->item['table_users']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_completed" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_COMPLETED' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="column_files_completed" id="column_files_completed" class="inputbox" size="40" value="<?php echo $this->item['column_files_completed']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_fid" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_FID' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="column_files_fid" id="column_files_fid" class="inputbox" size="40" value="<?php echo $this->item['column_files_fid']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_leechers" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_LEECHERS' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="column_files_leechers" id="column_files_leechers" class="inputbox" size="40" value="<?php echo $this->item['column_files_leechers']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_files_seeders" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_FILES_SEEDERS' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="column_files_seeders" id="column_files_seeders" class="inputbox" size="40" value="<?php echo $this->item['column_files_seeders']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="column_users_uid" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_COLUMN_USER_UID' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="column_users_uid" id="column_users_uid" class="inputbox" size="40" value="<?php echo $this->item['column_users_uid']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="torrent_pass_private_key" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_TORRENT_PASS_PRIVATE_KEY' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="torrent_pass_private_key" id="torrent_pass_private_key" class="inputbox" size="40" value="<?php echo $this->item['torrent_pass_private_key']; ?>" /></td>
						</tr>
					</thead>
				</table>
			</td>
		</tr>
	</table>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>
