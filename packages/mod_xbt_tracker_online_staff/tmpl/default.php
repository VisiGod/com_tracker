<?php
/**
 * @version		3.3.1-dev
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_online_staff
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho, Psylodesign and Patlol. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die; 

?>
<div class="moduletable<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<ul style="list-style-type:none;">
		<?php foreach ($online_staff as $item) { ?>
		<li style="margin-bottom: 2px;">
			<span style="text-transform: capitalize;color: #fc7e7e;padding: 10px;"><?php echo $item->name ;?></span>
			<?php if ($item->time) { ?>
  			<img style="float: left;vertical-align: middle;border: 0px;align: center;width: 16px;" src="<?php echo JURI::base();?>/modules/mod_xbt_tracker_online_staff/images/online.png" alt="<?php echo JText::_('MOD_XBT_TRACKER_ONLINE_STAFF_ONLINE'); ?>" />
  			<?php } else { ?>
			<img style="float: left;vertical-align: middle;border: 0px;align: center;width: 16px;" src="<?php echo JURI::base();?>/modules/mod_xbt_tracker_online_staff/images/offline.png" alt="<?php echo JText::_('MOD_XBT_TRACKER_ONLINE_STAFF_OFFLINE'); ?>" />
			<?php } ?>
			<small style="float: right;"><em><?php echo $item->groupName;?></em></small>
		</li>
		<?php } ?>
	</ul>
</div>
