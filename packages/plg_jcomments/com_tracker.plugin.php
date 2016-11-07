<?php
defined('_JEXEC') or die;

class jc_com_tracker extends JCommentsPlugin {
	function getObjectInfo($id, $language = null) {
		$info = new JCommentsObjectInfo();

		$db = JFactory::getDBO();
		$db->setQuery('SELECT name, uploader, categoryID FROM #__tracker_torrents WHERE fid =' . $id);
		$row = $db->loadObject();

		$info->title = $row->name;
		$info->userid = $row->uploader;
		$info->category_id = $row->categoryID;

		$info->link = JRoute::_('index.php?option=com_tracker&view=torrent&id=' . $id);

	return $info;
	}
}