<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
$params = JComponentHelper::getParams( 'com_tracker' );
?>
<script type="text/javascript">
function toggle_visibility(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'block') e.style.display = 'none';
	else e.style.display = 'block';
}

function changeFolder(folder) {
	document.pickFile.submit();
}
</script>

<div class="row-fluid">
	<div id="sidebar" class="span2">
		<div class="sidebar-nav">
			<?php echo $this->sidebar; ?>
		</div>
	</div>

	<div class="span10">
		<div class="row-fluid">
			<div class="row-fluid">
				<div class="span3 text-center">
					<dl>
						<dd>
							<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.clearannounce'); ?>">
								<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-trash.png' , JText::_( 'COM_TRACKER_UTILITY_CLEAN_ANNOUNCE' ), null, false, false); ?>
							</a>
						</dd>
						<dd>
							<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.clearannounce'); ?>">
								<?php echo JText::_( 'COM_TRACKER_UTILITY_CLEAN_ANNOUNCE' ); ?>
							</a>
						</dd>
					</dl>
				</div>
				<div class="span3 text-center">
					<dl>
						<dd>
							<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.optimizetables'); ?>">
								<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-stats.png' , JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES' ), null, false, false); ?>
							</a>
						</dd>
						<dd>
							<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.optimizetables'); ?>">
								<?php echo JText::_( 'COM_TRACKER_UTILITY_OPTIMIZE_TABLES' ); ?>
							</a>
						</dd>
					</dl>
				</div>
				<div class="span3 text-center">
					<dl>
						<?php if ($params->get('freeleech') == 0) {?>
							<dd>
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.enable_free_leech'); ?>">
									<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/free_leech_start-48x48.png' , JText::_( 'COM_TRACKER_UTILITY_ENABLE_FREE_LEECH' ), null, false, false); ?>
								</a>
							</dd>
							<dd>
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.enable_free_leech'); ?>">
									<?php echo JText::_( 'COM_TRACKER_UTILITY_ENABLE_FREE_LEECH' ); ?>
								</a>
							</dd>
						<?php } else { ?>
							<dd>
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.disable_free_leech'); ?>">
									<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/free_leech_stop-48x48.png' , JText::_( 'COM_TRACKER_UTILITY_DISABLE_FREE_LEECH' ), null, false, false); ?>
								</a>
							</dd>
							<dd>
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.disable_free_leech'); ?>">
									<?php echo JText::_( 'COM_TRACKER_UTILITY_DISABLE_FREE_LEECH' ); ?>
								</a>
							</dd>
						<?php } ?>
					</dl>
				</div>
				<div class="span3 text-center">
					<dl>
						<dd>
			 				<a href="#" onclick="toggle_visibility('utilities_div');">
								<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-install.png' , JText::_( 'COM_TRACKER_UTILITY_IMPORT_BULK_IMPORT' ), null, false, false); ?>
							</a>
						</dd>
						<dd>
							<a href="#" onclick="toggle_visibility('utilities_div');">
								<?php echo JText::_( 'COM_TRACKER_UTILITY_IMPORT_BULK_IMPORT' ); ?>
							</a>
						</dd>
					</dl>
				</div>
				<?php if ($params->get('forum_integration')) {?>
					<div class="span3">
						<dl>
							<dd>
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.importgroups'); ?>">
									<?php echo JHtml::_('image', '/administrator/components/com_tracker/images/panel/icon-48-purge.png' , JText::_( 'COM_TRACKER_UTILITY_IMPORT_FORUM_GROUPS' ), null, false, false); ?>
								</a>
							</dd>
							<dd>
								<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.importgroups'); ?>">
									<?php echo JText::_( 'COM_TRACKER_UTILITY_IMPORT_FORUM_GROUPS' ); ?>
								</a>
							</dd>
						</dl>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="row-fluid hide span10" id="utilities_div">
	<br /><br />
		<div class="control-group">
			<h3><?php echo JText::_( 'COM_TRACKER_UTILITY_IMPORT_DEFAULT_DIRECTORY' ); ?></h3>
		</div>
		<?php
			// Get the form.
			jimport('joomla.form.form');
			JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/forms');
			$form = JForm::getInstance('jform', 'torrent_import', array('array' => true));
		?>
		<form action="<?php echo JRoute::_('index.php?option=com_tracker&task=utilities.bulk_import'); ?>" method="post" name="adminForm" id="bulkimport-form" class="form-validate form-horizontal">
			<fieldset>
				<div class="control-group">
					<div class="control-label"><?php echo $form->getLabel('import_filename'); ?></div>
					<div class="controls"><?php echo $form->getInput('import_filename'); ?></div>
 				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $form->getLabel('field_separator'); ?></div>
					<div class="controls"><?php echo $form->getInput('field_separator'); ?></div>
				</div>
			</fieldset>

			<?php echo JHtml::_('form.token'); ?>
			<input type="submit" value="Submit">
		</form>		
	</div>
</div>
