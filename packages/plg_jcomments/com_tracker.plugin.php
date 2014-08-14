<?php
defined('_JEXEC') or die;

class jc_com_tracker extends JCommentsPlugin {

	public function getObjectTitle( $id ) {
		// Data load from database by given id 
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('name')
			  ->from('`#__tracker_torrents`')
			  ->where('fid = '.(int) $id);
		$db->setQuery($query);
		return $db->loadResult();
	}

	public function getObjectLink( $id ) {
		// Itemid meaning of our component
		$_Itemid = JCommentsPlugin::getItemid( 'com_tracker' );

		// url link creation for given object by id 
		$link = JRoute::_( 'index.php?option=com_tracker&view=torrent&id='. $id . '&Itemid=' . $_Itemid );
		return $link;
	}

	public function getObjectOwner( $id ) {
		$db		= JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('uploader, fid')
			  ->from('`#__tracker_torrents`')
			  ->where('fid = '.(int) $id);
		$db->setQuery($query);
		return $db->loadResult();
	}
}