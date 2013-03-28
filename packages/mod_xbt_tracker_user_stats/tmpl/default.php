<?php
/**
 * @version		2.5.1
 * @package		Joomla
 * @subpackage	mod_xbt_tracker_user_stats
 * @copyright	Copyright (C) 2007 - 2013 Hugo Carvalho and Psylodesign. All rights reserved.
 * @license		GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.html.parameter' );

$user	= JFactory::getUser();
$appParams = $app->getParams('com_tracker');



if ($user->get('guest')) {
	?>
	<div id="container">
		<div>
		<?php echo JText::_('MOD_TRACKER_USER_STATS_GUEST_USER');?>
		</div>
	</div>
	<?php 
	return;
}

$document =& JFactory::getDocument();
$style = '#container {
		display: table;
		width: 99%;
}
		#row  {
		display: table-row;
}
		#value {
		display: table-cell;
		white-space: nowrap;
}
		#value-right {
		display: table-cell;
		text-align: right;
		white-space: nowrap;
}
		#value-left {
		display: table-cell;
		text-align: left;
		white-space: nowrap;
}
		#value-center {
		display: table-cell;
		text-align: center;
		white-space: nowrap;
}';

$document->addStyleDeclaration( $style );

?>
<div id="container">
	<div>
		<?php
			if ($params->get('name'))
				echo '<div><h3>'.JText::_('MOD_TRACKER_USER_STATS_WELCOME_TEXT').' '.$user_stats->name.'</h3></div>';
			if ($params->get('registration'))
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_REGISTERED_TEXT').' '.JFactory::getDate($user_stats->registerDate)->toFormat('%d %B %Y').'</div>';
			if ($params->get('group'))
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_GROUP_TEXT').' '.$user_stats->groupname.'</div>';
			if ($params->get('country')) {
				echo '<div>';
				echo '<div style="display:inline-block; vertical-align:middle;">'.JText::_('MOD_TRACKER_USER_STATS_COUNTRY_TEXT').' '.$user_stats->countryName.'</div>';
				if ($params->get('country_flag')) echo '<div style="display:inline-block; vertical-align:middle;"><img id="'.$user_stats->countryName.'" alt="'.$user_stats->countryName.'" src="'.JURI::base().$user_stats->countryImage.'" width="32px" /></div>';
				echo '</div>';
			}

			if ($params->get('uploaded')) {
				echo '<div>';
				echo '<div style="float: left;display:inline-block; vertical-align:middle;"><img id="'.$user_stats->uploaded.'" alt="'.$user_stats->uploaded.'" src="'.JURI::base().$params->get('uploaded_image').'" width="16px" /></div>';
				echo '<div style="margin-left: 2px;float: left;display:inline-block; vertical-align:middle;">'.JText::_('MOD_TRACKER_USER_STATS_UPLOADED_TEXT').'</div>';
				echo '<div style="float: right;display:inline-block; vertical-align:middle;"><span style="color:green">'.TrackerHelper::make_size($user_stats->uploaded).'</span></div>';
				echo '</div><br />';
			}
			
			if ($params->get('downloaded')) {
				echo '<div>';
				echo '<div style="float: left;display:inline-block; vertical-align:middle;"><img id="'.$user_stats->downloaded.'" alt="'.$user_stats->downloaded.'" src="'.JURI::base().$params->get('downloaded_image').'" width="16px" /></div>';
				echo '<div style="margin-left: 2px;float: left;display:inline-block; vertical-align:middle;">'.JText::_('MOD_TRACKER_USER_STATS_DOWNLOADED_TEXT').'</div>';
				echo '<div style="float: right;display:inline-block; vertical-align:middle;"><span style="color:red">'.TrackerHelper::make_size($user_stats->downloaded).'</span></div>';
				echo '</div><br />';
			}

			if ($params->get('ratio')) {
				echo '<div>';
				if ( TrackerHelper::make_ratio($user_stats->downloaded, $user_stats->uploaded, 1) >= $user_stats->minimum_ratio)
					echo '<div style="float: left;display:inline-block; vertical-align:middle;"><img id="ratio" alt="'.JText::_('MOD_TRACKER_USER_STATS_RATIO_TEXT').'" src="'.JURI::base().$params->get('good_ratio_image').'" width="16px" /></div>';
				else echo '<div style="float: left;display:inline-block; vertical-align:middle;"><img id="ratio" alt="'.JText::_('MOD_TRACKER_USER_STATS_RATIO_TEXT').'" src="'.JURI::base().$params->get('bad_ratio_image').'" width="16px" /></div>';
				echo '<div style="margin-left: 2px;float: left;display:inline-block; vertical-align:middle;">'.JText::_('MOD_TRACKER_USER_STATS_RATIO_TEXT').'</div>';
				echo '<div style="float: right;display:inline-block; vertical-align:middle;">';
				if ( TrackerHelper::make_ratio($user_stats->downloaded, $user_stats->uploaded, 1) >= $user_stats->minimum_ratio)
					echo '<span style="color:green">';
				else echo '<span style="color:red">';
				echo TrackerHelper::make_ratio($user_stats->downloaded, $user_stats->uploaded);
				echo '</span></div>';
				echo '</div><br />';
			}
		

			if ($params->get('can_leech')) {
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_CAN_LEECH_TEXT');
				echo $user_stats->can_leech ? JText::_('JYES') : JText::_('JNO');
				echo '</div>';
			}

			if ($params->get('wait_time')) {
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_WAIT_TIME_TEXT');
				echo $user_stats->wait_time ? TrackerHelper::make_wait_time($user_stats->wait_time, 0) : JText::_('MOD_TRACKER_USER_STATS_WAIT_TIME_NO_WAIT_TIME'); 
				echo '</div>';
			}

			if ($params->get('peer_limit')) {
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_PEER_LIMIT_TEXT');
				echo $user_stats->peer_limit ? $user_stats->peer_limit.JText::_('MOD_TRACKER_USER_STATS_PEER_LIMIT_IPS') : JText::_('MOD_TRACKER_USER_STATS_PEER_LIMIT_UNLIMITED');
				echo '</div>';
			}

			if ($params->get('torrent_limit')) {
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_TORRENT_LIMIT_TEXT');
				echo $user_stats->torrent_limit ? $user_stats->torrent_limit.JText::_('MOD_TRACKER_USER_STATS_TORRENT_LIMIT_TORRENTS') : JText::_('MOD_TRACKER_USER_STATS_TORRENT_LIMIT_UNLIMITED');
				echo '</div>';
			}

			if ($params->get('upload_multiplier')) {
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_TORRENT_UPLOAD_MULTIPLIER_TEXT');
				echo $user_stats->multiplier_type ? $user_stats->user_um : $user_stats->group_um;
				echo '</div>';
			}

			if ($params->get('download_multiplier')) {
				echo '<div>'.JText::_('MOD_TRACKER_USER_STATS_TORRENT_DOWNLOAD_MULTIPLIER_TEXT');
				echo $user_stats->multiplier_type ? $user_stats->user_dm : $user_stats->group_dm;
				echo '</div>';
			}
			?>
	</div>
</div>





