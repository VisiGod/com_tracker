<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	mod_xbt_tracker_latest
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_ADMINISTRATOR.'/components/com_tracker/helpers/tracker.php';
jimport( 'joomla.html.parameter' );

$user	= JFactory::getUser();
$appParams = $app->getParams('com_tracker');

if ($user->get('guest') && $appParams->get('allow_guest') == 1) {
	$user->load($appParams->get('guest_user'));
} else if ($user->get('guest') && $appParams->get('allow_guest') == 0) {
	$user->can_leech = 0;
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
  <div id="row">
		<?php
			$head = '';
			if ($params->get('name_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_NAME_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('filename_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('filename_field').'&nbsp;</h3><hr /></div>';
			if ($params->get('description_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_DESCRIPTION_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('size_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_SIZE_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('added_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_ADDED_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('leechers_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_LEECHERS_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('seeders_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_SEEDERS_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('completed_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_COMPLETED_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('numfiles_field')) $head .= '<div id="value"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_NUMFILES_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('forum_post_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_FORUM_POST_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('info_post_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_INFO_POST_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('download_multiplier_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_DOWNLOAD_MULTIPLIER_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('upload_multiplier_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_UPLOAD_MULTIPLIER_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('license_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_LICENSE_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('image_file_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_IMAGE_FILE_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('torrent_owner_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_TORRENT_OWNER_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('torrent_category_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_TORRENT_CATEGORY_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('category_image_field')) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_CATEGORY_IMAGE_FIELD_LABEL').'&nbsp;</h3><hr /></div>';
			if ($params->get('download_button') && ( $user->can_leech || ( $appParams->get('allow_guest') && $user->can_leech))) $head .= '<div id="value-center"><h3>&nbsp;'.JText::_('MOD_XBT_TRACKER_FIELD_DOWNLOAD_BUTTON_VIEW').'&nbsp;</h1><hr /></div>';
			$head .= '</div>';

$items = '';
foreach ($list as $item) {
	$items .='<div id="row">';
	if ($params->get('name_field')) {
		$item->name = (strlen($item->name) > ($params->get('name_field_length') + 3)) ? substr($item->name,0,$params->get('name_field_length')).'...' : $item->name;
		$items .= '<div id="value">&nbsp;<a href="'.JRoute::_('index.php?option=com_tracker&view=torrent&id='.(int)$item->fid).'">';
		$items .= str_replace('_', ' ', $item->name).'</a>&nbsp;<hr /></div>';
	}
	if ($params->get('filename_field')) {
		$item->filename = (strlen($item->filename) > ($params->get('filename_field_length') + 3)) ? substr($item->filename,0,$params->get('filename_field_length')).'...' : $item->filename;
		$items .= '<div id="value">&nbsp;'.$item->filename.'&nbsp;<hr /></div>';
	}
	if ($params->get('description_field')) $items .= '<div id="value">&nbsp;'.$item->description.'&nbsp;<hr /></div>';
	if ($params->get('size_field')) $items .= '<div id="value-right">&nbsp;'.TrackerHelper::make_size($item->size).'&nbsp;<hr /></div>';
	if ($params->get('added_field')) $items .= '<div id="value-right">&nbsp;'.date('Y.m.d', strtotime($item->added)).'&nbsp;<hr /></div>';
	if ($params->get('leechers_field')) $items .= '<div id="value-center">&nbsp;'.$item->leechers.'&nbsp;<hr /></div>';
	if ($params->get('seeders_field')) $items .= '<div id="value-center">&nbsp;'.$item->seeders.'&nbsp;<hr /></div>';
	if ($params->get('completed_field')) $items .= '<div id="value-center">&nbsp;'.$item->completed.'&nbsp;<hr /></div>';
	if ($params->get('numfiles_field')) $items .= '<div id="value">&nbsp;'.$item->numfiles.'&nbsp;<hr /></div>';
	if ($params->get('forum_post_field')) $items .= '<div id="value-center">&nbsp;'.$item->forum_post.'&nbsp;<hr /></div>';
	if ($params->get('info_post_field')) $items .= '<div id="value-center">&nbsp;'.$item->info_post.'&nbsp;<hr /></div>';
	if ($params->get('download_multiplier_field')) $items .= '<div id="value-center">&nbsp;'.$item->download_multiplier.'&nbsp;<hr /></div>';
	if ($params->get('upload_multiplier_field')) $items .= '<div id="value-center">&nbsp;'.$item->upload_multiplier.'&nbsp;<hr /></div>';
	if ($params->get('license_field')) $items .= '<div id="value-center">&nbsp;'.$item->license.'&nbsp;<hr /></div>';
	if ($params->get('image_file_field')) $items .= '<div id="value-center">&nbsp;'.$item->image_file.'&nbsp;<hr /></div>';
	if ($params->get('torrent_owner_field')) $items .= '<div id="value-center">&nbsp;'.$item->torrent_owner.'&nbsp;<hr /></div>';
	if ($params->get('torrent_category_field')) $items .= '<div id="value-center">&nbsp;'.$item->torrent_category.'&nbsp;<hr /></div>';
	if ($params->get('category_image_field')) {
		$category_params = new JParameter( $item->category_params );
		if (is_file($_SERVER['DOCUMENT_ROOT'].JUri::root(true).DS.$category_params->get('image'))) {
			$items .= '<div id="value-center">&nbsp;<img id="image'.$item->fid.'" alt="'.$item->torrent_category.'" src="'.JUri::root(true).DS.$category_params->get('image').'" width="36" />&nbsp;<hr /></div>';
		} else $items .= '<div id="value">&nbsp;'.$item->torrent_category.'&nbsp;<hr /></div>';
	}
	if ($params->get('download_button') && ( $user->can_leech || ( $appParams->get('allow_guest') && $user->can_leech))) {
		$items .= '<div id="value-center">&nbsp;<a href="'.JRoute::_("index.php?option=com_tracker&task=torrent.download&id=".$item->fid).'">';
		$items .= '<img src="'.JURI::base().'components/com_tracker/assets/images/download.gif" alt="'.JText::_( 'TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ).'" border="0" /></a>&nbsp;<hr /></div>';
	}

	$items .='</div>';
}

echo $head.$items.'</div>';
?>
</div></div>