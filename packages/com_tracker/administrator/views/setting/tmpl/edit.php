<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
$app = JFactory::getApplication();
$params = JComponentHelper::getParams( 'com_tracker' );
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'setting.cancel' || document.formvalidator.isValid(document.id('setting-form'))) {
			Joomla.submitform(task, document.getElementById('setting-form'));
		}
	}
</script>


<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit'); ?>" method="post" name="adminForm" id="setting-form" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span5 offset1">
			<fieldset>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('announce_interval'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('announce_interval'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('clean_up_interval'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('clean_up_interval'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('daemon'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('daemon'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('debug'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('debug'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('full_scrape'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('full_scrape'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('gzip_scrape'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('gzip_scrape'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('listen_ipa'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('listen_ipa'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('listen_port'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('listen_port'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('log_access'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('log_access'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('log_scrape'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('log_scrape'); ?></div>
				</div>
			</fieldset>
		</div>
		<div class="span5">
			<fieldset>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('offline_message'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('offline_message'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('pid_file'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('pid_file'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('query_log'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('query_log'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('read_config_interval'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('read_config_interval'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('read_db_interval'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('read_db_interval'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('redirect_url'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('redirect_url'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('scrape_interval'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('scrape_interval'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('write_db_interval'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('write_db_interval'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('torrent_pass_private_key'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('torrent_pass_private_key'); ?></div>
				</div>
			</fieldset>
		</div>
	</div>

	<div class="clr"></div>

	<!-- Now we need to pass the "static values" -->
	<input type="hidden" name="jform[anonymous_announce]" value="<?php echo $this->form->getValue('anonymous_announce'); ?>" />
	<input type="hidden" name="jform[anonymous_scrape]" value="<?php echo $this->form->getValue('anonymous_scrape'); ?>" />
	<input type="hidden" name="jform[auto_register]" value="<?php echo $this->form->getValue('auto_register'); ?>" />
	<input type="hidden" name="jform[log_announce]" value="<?php echo $this->form->getValue('log_announce'); ?>" />
	<input type="hidden" name="jform[column_files_completed]" value="<?php echo $this->form->getValue('column_files_completed'); ?>" />
	<input type="hidden" name="jform[column_files_fid]" value="<?php echo $this->form->getValue('column_files_fid'); ?>" />
	<input type="hidden" name="jform[column_files_leechers]" value="<?php echo $this->form->getValue('column_files_leechers'); ?>" />
	<input type="hidden" name="jform[column_files_seeders]" value="<?php echo $this->form->getValue('column_files_seeders'); ?>" />
	<input type="hidden" name="jform[column_users_uid]" value="<?php echo $this->form->getValue('column_users_uid'); ?>" />
	<input type="hidden" name="jform[table_announce_log]" value="<?php echo $app->getCfg('dbprefix', 1).'tracker_announce_log'; ?>" />
	<input type="hidden" name="jform[table_files]" value="<?php echo $app->getCfg('dbprefix', 1).'tracker_torrents'; ?>" />
	<input type="hidden" name="jform[table_files_users]" value="<?php echo $app->getCfg('dbprefix', 1).'tracker_files_users'; ?>" />
	<input type="hidden" name="jform[table_scrape_log]" value="<?php echo $app->getCfg('dbprefix', 1).'tracker_scrape_log'; ?>" />
	<input type="hidden" name="jform[table_users]" value="<?php echo $app->getCfg('dbprefix', 1).'tracker_users'; ?>" />
	<?php if ($params->get('peer_banning')) { ?>
			<input type="hidden" name="jform[table_deny_from_clients]" value="<?php echo $app->getCfg('dbprefix', 1).'tracker_deny_from_clients'; ?>" />
	<?php } ?>
	<?php  if ($params->get('host_banning')) { ?>
			<input type="hidden" name="jform[table_deny_from_hosts]" value="<?php echo $app->getCfg('dbprefix', 1).'tracker_deny_from_hosts'; ?>" />
	<?php } ?>
	
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
