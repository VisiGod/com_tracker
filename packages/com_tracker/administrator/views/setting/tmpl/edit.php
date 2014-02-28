<?php
/**
 * @version			2.5.13-dev
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
			<td width="50%" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="announce_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_ANNOUNCE_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="announce_interval" id="announce_interval" class="inputbox" size="11" value="<?php echo $this->item['announce_interval']; ?>" /></td>
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
						<tr>
							<td class="key" nowrap><label for="log_access" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_ACCESS' ); ?></b></label></td>
							<td><?php echo $this->item['log_access']; ?></td>
						</tr>	
						<tr>
							<td class="key" nowrap><label for="log_scrape" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_LOG_SCRAPE' ); ?></b></label></td>
							<td><?php echo $this->item['log_scrape']; ?></td>
						</tr>
					</thead>
				</table>
			</td>
			<td width="50%" valign="top">
				<table class="adminlist">
					<thead>
						<tr>
							<td class="key" nowrap><label for="offline_message" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_OFFLINE_MESSAGE' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="offline_message" id="offline_message" class="inputbox" size="50" value="<?php echo $this->item['offline_message']; ?>" /></td>
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
							<td>&nbsp;&nbsp;<input type="text" name="redirect_url" id="redirect_url" class="inputbox" size="50" value="<?php echo $this->item['redirect_url']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="scrape_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_SCRAPE_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="scrape_interval" id="scrape_interval" class="inputbox" size="11" value="<?php echo $this->item['scrape_interval']; ?>" /></td>
						</tr>
						<tr>
							<td class="key" nowrap><label for="write_db_interval" style="align:left"><b><?php echo JText::_( 'COM_TRACKER_SETTING_WRITE_DB_INTERVAL' ); ?></b></label></td>
							<td>&nbsp;&nbsp;<input type="text" name="write_db_interval" id="write_db_interval" class="inputbox" size="11" value="<?php echo $this->item['write_db_interval']; ?>" /></td>
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
