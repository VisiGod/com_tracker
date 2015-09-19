<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.modal', 'a.modalpopup');

$user	= JFactory::getUser();
$appParams = $app->getParams('com_tracker');

if ($user->get('guest') && $appParams->get('allow_guest') == 1) {
	$user->id = $appParams->get('guest_user');
} else if ($user->get('guest') && $appParams->get('allow_guest') == 0) {
	$user->id = 0;
}

$document = JFactory::getDocument();
$style = '#container {
		display: table;
		width: 99%;
}
		#row  {
		display: table-row;
}
		#value {
		display: table-cell;
		vertical-align:middle;
		white-space: nowrap;
}
		#value-right {
		display: table-cell;
		vertical-align:middle;
		text-align: right;
		white-space: nowrap;
}
		#value-left {
		display: table-cell;
		vertical-align:middle;
		text-align: left;
		white-space: nowrap;
}
		#value-center {
		display: table-cell;
		vertical-align:middle;
		text-align: center;
		white-space: nowrap;
}';
	
$document->addStyleDeclaration( $style );

?>
<div id="container">
	<div id="row">
		<?php
			$head = '';
			if ($params->get('name_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_NAME_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('filename_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_FILENAME_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('description_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_DESCRIPTION_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('size_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_SIZE_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('created_time_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_CREATED_TIME_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('leechers_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_LEECHERS_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('seeders_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_SEEDERS_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('completed_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_COMPLETED_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('number_files_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_NUMBER_FILES_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('forum_post_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_FORUM_POST_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('info_post_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_INFO_POST_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('download_multiplier_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_DOWNLOAD_MULTIPLIER_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('upload_multiplier_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_UPLOAD_MULTIPLIER_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('license_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_LICENSE_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('image_file_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_IMAGE_FILE_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('torrent_owner_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_TORRENT_OWNER_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('torrent_category_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_TORRENT_CATEGORY_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('category_image_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_CATEGORY_IMAGE_FIELD_LABEL').'&nbsp;</h3></div>';
			if ($params->get('download_button') && TrackerHelper::user_permissions('can_leech', $user->id) ) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_DOWNLOAD_BUTTON_VIEW').'&nbsp;</h1></div>';
			$head .= '</div>';

			$items = '';
			foreach ($list as $item) {
				$items .='<div id="row">';
				if ($params->get('name_field')) {
					$item->name = (strlen($item->name) > ($params->get('name_field_length') + 3)) ? substr($item->name,0,$params->get('name_field_length')).'...' : $item->name;
					$items .= '<div id="value">&nbsp;<a href="'.JRoute::_('index.php?option=com_tracker&view=torrent&id='.(int)$item->fid).'">';
					$items .= str_replace('_', ' ', $item->name).'</a>&nbsp;</div>';
				}
				if ($params->get('filename_field')) {
					$item->filename = (strlen($item->filename) > ($params->get('filename_field_length') + 3)) ? substr($item->filename,0,$params->get('filename_field_length')).'...' : $item->filename;
					$items .= '<div id="value">&nbsp;'.$item->filename.'&nbsp;</div>';
				}
				
				if ($params->get('image_file_field') && !empty($item->image_file)) {
					$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
								
					// If we have a link in the field
					if(!preg_match($reg_exUrl, $item->image_file)) {
						$item->image_file = JURI::base().'images/tracker/torrent_image/'.$item->image_file;
					}

					$item->image_file = '<a href="'.$item->image_file.'" class="modalpopup" ><img style="width: '.$params->get('image_file_width').'px;margin-top: 2px; margin-bottom: 2px;" src="'.$item->image_file.'" /></a>';
				} else $item->image_file = '';
				
				if ($params->get('description_field')) $items .= '<div id="value">&nbsp;'.$item->description.'&nbsp;</div>';
				if ($params->get('size_field')) $items .= '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;</div>';
				if ($params->get('created_time_field')) $items .= '<div id="value-right">&nbsp;'.date('Y.m.d', strtotime($item->created_time)).'&nbsp;</div>';
				if ($params->get('leechers_field')) $items .= '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;</div>';
				if ($params->get('seeders_field')) $items .= '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;</div>';
				if ($params->get('completed_field')) $items .= '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;</div>';
				if ($params->get('number_files_field')) $items .= '<div id="value-center">&nbsp;'.$item->number_files.'&nbsp;</div>';
				if ($params->get('forum_post_field')) $items .= '<div id="value-center">&nbsp;'.$item->forum_post.'&nbsp;</div>';
				if ($params->get('info_post_field')) $items .= '<div id="value-center">&nbsp;'.$item->info_post.'&nbsp;</div>';
				if ($params->get('download_multiplier_field')) $items .= '<div id="value-center">&nbsp;'.$item->download_multiplier.'&nbsp;</div>';
				if ($params->get('upload_multiplier_field')) $items .= '<div id="value-center">&nbsp;'.$item->upload_multiplier.'&nbsp;</div>';
				if ($params->get('license_field')) $items .= '<div id="value-center">&nbsp;'.$item->torrent_license.'&nbsp;</div>';
				if ($params->get('image_file_field')) $items .= '<div id="value-center">&nbsp;'.$item->image_file.'&nbsp;</div>';
				if ($params->get('torrent_owner_field')) $items .= '<div id="value-center">&nbsp;'.$item->torrent_owner.'&nbsp;</div>';
				if ($params->get('torrent_category_field')) $items .= '<div id="value-center">&nbsp;'.$item->torrent_category.'&nbsp;</div>';
				if ($params->get('category_image_field')) {
					$category_params = new JRegistry();
					$category_params->loadString($item->category_params);

					if (is_file($_SERVER['DOCUMENT_ROOT'].JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image'))) {
						$items .= '<div id="value-center">&nbsp;<img id="image'.$item->fid.'" alt="'.$item->torrent_category.'" src="'.JUri::root(true).DIRECTORY_SEPARATOR.$category_params->get('image').'" width="36" />&nbsp;</div>';
					} else $items .= '<div id="value">&nbsp;'.$item->torrent_category.'&nbsp;</div>';
				}
				if ($params->get('download_button') && TrackerHelper::user_permissions('can_leech', $user->id) ) {
					$items .= '<div id="value-center">&nbsp;<a href="'.JRoute::_("index.php?option=com_tracker&task=torrent.download&id=".$item->fid).'">';
					$items .= '<img src="'.JURI::base().'components/com_tracker/assets/images/download.gif" alt="'.JText::_( 'TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ).'" border="0" /></a>&nbsp;</div>';
				}
				$items .='</div>';
			}
		echo $head.$items;
		?>
	</div>
</div>
