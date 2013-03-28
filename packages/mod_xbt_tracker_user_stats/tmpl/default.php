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
				echo '<div style="clear: both;"></div>';
			}

			if ($params->get('uploaded')) {
				echo '<div style="margin-bottom: 2px;">';
				echo '<div style="float: left;display:inline-block; vertical-align:middle;"><img id="'.$user_stats->uploaded.'" alt="'.$user_stats->uploaded.'" src="'.JURI::base().$params->get('uploaded_image').'" width="16px" /></div>';
				echo '<div style="margin-left: 2px;float: left;display:inline-block; vertical-align:middle;">'.JText::_('MOD_TRACKER_USER_STATS_UPLOADED_TEXT').'</div>';
				echo '<div style="float: right;display:inline-block; vertical-align:middle;"><span style="color:green">'.TrackerHelper::make_size($user_stats->uploaded).'</span></div>';
				echo '</div>';
				echo '<div style="clear: both;"></div>';
			}
			
			if ($params->get('downloaded')) {
				echo '<div style="margin-bottom: 2px;">';
				echo '<div style="float: left;display:inline-block; vertical-align:middle;"><img id="'.$user_stats->downloaded.'" alt="'.$user_stats->downloaded.'" src="'.JURI::base().$params->get('downloaded_image').'" width="16px" /></div>';
				echo '<div style="margin-left: 2px;float: left;display:inline-block; vertical-align:middle;">'.JText::_('MOD_TRACKER_USER_STATS_DOWNLOADED_TEXT').'</div>';
				echo '<div style="float: right;display:inline-block; vertical-align:middle;"><span style="color:red">'.TrackerHelper::make_size($user_stats->downloaded).'</span></div>';
				echo '</div>';
				echo '<div style="clear: both;"></div>';
			}

			if ($params->get('ratio')) {
				echo '<div style="margin-bottom: 2px;">';
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
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}
		

			if ($appParams->get('enable_donations') && $params->get('donations')) {
				echo '<div>';
				echo '<div style="float: left;">'.JText::_('MOD_TRACKER_USER_STATS_DONATIONS_TEXT').'</div>';
				echo '<div style="float: right;">'.JText::_('MOD_TRACKER_USER_STATS_DONATIONS_CURRENCY');
				echo $user_stats->donated ? $user_stats->donated : '0';
				echo '</div>';
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}
			
			
			if ($params->get('can_leech')) {
				echo '<div>';
				echo '<div style="float: left;">'.JText::_('MOD_TRACKER_USER_STATS_CAN_LEECH_TEXT').'</div>';
				echo '<div style="float: right;">'.$user_stats->can_leech ? JText::_('JYES') : JText::_('JNO').'</div>';
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}

			if ($params->get('wait_time')) {
				echo '<div>';
				echo '<div style="float: left;">'.JText::_('MOD_TRACKER_USER_STATS_WAIT_TIME_TEXT').'</div>';
				echo '<div style="float: right;">'.$user_stats->wait_time ? TrackerHelper::make_wait_time($user_stats->wait_time, 0) : JText::_('MOD_TRACKER_USER_STATS_WAIT_TIME_NO_WAIT_TIME').'</div>'; 
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}

			if ($params->get('peer_limit')) {
				echo '<div>';
				echo '<div style="float: left;">'.JText::_('MOD_TRACKER_USER_STATS_PEER_LIMIT_TEXT').'</div>';
				echo '<div style="float: right;">'.$user_stats->peer_limit ? $user_stats->peer_limit.JText::_('MOD_TRACKER_USER_STATS_PEER_LIMIT_IPS') : JText::_('MOD_TRACKER_USER_STATS_PEER_LIMIT_UNLIMITED').'</div>';
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}

			if ($params->get('torrent_limit')) {
				echo '<div>';
				echo '<div style="float: left;">'.JText::_('MOD_TRACKER_USER_STATS_TORRENT_LIMIT_TEXT').'</div>';
				echo '<div style="float: right;">'.$user_stats->torrent_limit ? $user_stats->torrent_limit.JText::_('MOD_TRACKER_USER_STATS_TORRENT_LIMIT_TORRENTS') : JText::_('MOD_TRACKER_USER_STATS_TORRENT_LIMIT_UNLIMITED').'</div>';
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}

			if ($params->get('upload_multiplier')) {
				echo '<div>';
				echo '<div style="float: left;">'.JText::_('MOD_TRACKER_USER_STATS_TORRENT_UPLOAD_MULTIPLIER_TEXT').'</div>';
				echo '<div style="float: right;">'.$user_stats->multiplier_type ? $user_stats->user_um : $user_stats->group_um.'</div>';
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}

			if ($params->get('download_multiplier')) {
				echo '<div>';
				echo '<div style="float: left;">'.JText::_('MOD_TRACKER_USER_STATS_TORRENT_DOWNLOAD_MULTIPLIER_TEXT').'</div>';
				echo '<div style="float: right;">'.$user_stats->multiplier_type ? $user_stats->user_dm : $user_stats->group_dm.'</div>';
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}
			?>
	</div>
</div>





