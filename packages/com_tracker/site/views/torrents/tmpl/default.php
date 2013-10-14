<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
jimport( 'joomla.html.parameter' );

if ($this->user->guest && $this->params->get('allow_guest')) :
	$this->user->id = $this->params->get('guest_user');
endif;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$testCSS = '
.bar{

width:800px;
}

.barrerecherche{

width:50%;
float:left;
}
.barrecat{

width:1%;
float:right;

}
.gantry-filtre {
    background-color: #FFFFFF;
    border: 1px solid #DDDDDD;
    border-radius: 4px 4px 4px 4px;
    margin: 15px 0;
    padding: 39px 19px 14px;
    position: relative;
}
.gantry-filtre .table, .gantry-filtre .well, .gantry-filtre blockquote {
    margin-bottom: 5px;
}
.gantry-filtre .lead {
    font-size: 18px;
    line-height: 24px;
}
.gantry-filtre > p:last-child {
    margin-bottom: 0;
}
.gantry-filtre + .prettyprint {
    margin-top: -20px;
    padding-top: 15px;
}
.gantry-filtre:after {
    background-color: #F5F5F5;
    border: 1px solid #DDDDDD;
    border-radius: 4px 0 4px 0;
    color: #9DA0A4;
    content: "Filtre";
    font-size: 12px;
    font-style: normal;
    font-weight: bold;
    left: -1px;
    padding: 3px 7px;
    position: absolute;
    top: -1px;;
}
form.gantry-filtre {
    padding-bottom: 19px;
}
.badge2 {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-image: none;
    border-radius: 4px 4px 4px 4px;
    border-style: solid;
    border-width: 1px;
    
    display: inline-block;
    font-size: 14px;
    line-height: 20px;
    margin-bottom: 0;
    padding: 4px 14px;
    text-align: center;
    vertical-align: middle;
}
a.hastip {
	position: relative;
	text-decoration: none;
}
.tp {
	opacity: 0;
	filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0);					/* Fucking IE */
	position: absolute;
	left: 300px;
	top: -200px;
	z-index: 30;
	padding: 0 20px;
	height: auto;
	width: auto;
	min-width: 300px;
	border: 1px solid #E3E3E3;	
	-webkit-border-radius:	4px;
	-moz-border-radius:		4px;
	-o-border-radius:		4px;
	border-radius:			4px;
	line-height: 40px;
	color: #333333;
	text-shadow: 0 -1px 1px rgba(0,0,0,.6), 0 1px 1px rgba(255,255,255,1);
	-webkit-box-shadow:	inset 0 1px 1px rgba(0, 0, 0, 0.05);
	-moz-box-shadow:	inset 0 1px 1px rgba(0, 0, 0, 0.05);
	-o-box-shadow:		inset 0 1px 1px #rgba(0, 0, 0, 0.05);
	box-shadow:			0 1px 1px rgba(0, 0, 0, 0.05) inset;
	-webkit-transform:	scale(0);
	-moz-transform:		scale(0);
	-o-transform:		scale(0);
	transform:			scale(0);
	-webkit-transition:	opacity .4s ease-in-out,top .4s ease-in-out,-webkit-transform 0s linear .4s;
	-moz-transition:	opacity .4s ease-in-out,top .4s ease-in-out,-moz-transform 0s linear .4s;
	-o-transition:		opacity .4s ease-in-out,top .4s ease-in-out,-o-transform 0s linear .4s;
	transition:			opacity .4s ease-in-out,top .4s ease-in-out,transform 0s linear .4s;

	
}
.hastip:hover .tp, .hastip:active .tp {
	opacity: 1;
	filter: progid:DXImageTransform.Microsoft.Alpha(opacity=100);				/* Fucking IE */
	top:-50px;
	
	z-index: 40;
	-webkit-transform:	scale(1);
	-moz-transform:		scale(1);
	-o-transform:		scale(1);
	transform:			scale(1);
	-webkit-transition:	opacity .4s ease-in-out,top .4s ease-in-out;
	-moz-transition:	opacity .4s ease-in-out,top .4s ease-in-out;
	-o-transition:		opacity .4s ease-in-out,top .4s ease-in-out;
	transition:			opacity .4s ease-in-out,top .4s ease-in-out;

	

}


.blue {
	text-shadow: 0 -1px 1px #ffffff, 0 1px 1px #ffffff;
	background-color: rgba(254, 254, 254, 0.7);
	-webkit-box-shadow:	inset 0 1px 1px #ffffff, 0 4px 46px #494b4c;
	-moz-box-shadow:	inset 0 1px 1px #ffffff, 0 4px 46px #494b4c;
	-o-box-shadow:		inset 0 1px 1px #ffffff, 0 4px 46px #494b4c;
	box-shadow:			inset 0 1px 1px #ffffff, 0 4px 46px #494b4c;
padding:5px;

	
}
.blue:after {

 position: absolute;
  display: block;
  content: "";  
  border-color: rgba(254, 254, 254, 0.7) transparent transparent transparent;
  border-style: solid;
  border-width: 10px;
  height:0;
  width:0;
  position:absolute;
  bottom:-19px;
  left:-20px;
	top:40px;

-webkit-transform:rotate(90deg); 
  -moz-transform:rotate(90deg);
  -o-transform:rotate(90deg); 
  -ms-transform:rotate(90deg); 
  transform:rotate(90deg);

	
}
.thx {
display:inline-block;
width:auto;
background:url("http://share-on-underground.com/components/com_tracker/assets/images/fondthx.png")scroll no-repeat 50% 50%;
width:62px;
height:48px;
left:-20px;
position:absolute;
}
.thx span {

position:absolute;
top:14px;
left:15px;
font-family:"Lobster Two",Helvetica,arial,serif;
color:#ffffff;
}
';

$doc =& JFactory::getDocument();
$doc->addStyleSheet($testCSS);

// Show extra page text (defined in menu)
if ($this->params->get('menu_text')) echo '<h2>'.$this->escape($this->params->get('menu-anchor_title')).'</h2>';
?>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=torrents'); ?>" method="post" name="adminForm" class="gantry-filtre form-search">
	<fieldset id="filter-bar" class="bar">

		<div class="filter-search fltlft" style="width:50%; float:left;">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>

		<?php if ($this->params->get('tl_category')) { ?>
		<div style="float: right;">
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_tracker'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
		</div>
		<?php } ?>

		<?php if ($this->params->get('tl_license')) { ?>
		<div style="float: right; margin-right: 3px;">
			<select name="filter_license_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_TRACKER_SELECT_LICENSE');?></option>
				<?php echo JHTML::_('select.options', TrackerHelper::SelectList('licenses', 'id', 'shortname', '1'), 'value', 'text', $this->state->get('filter.license_id')); ?>
			</select>
		</div>
		<?php } ?>
	<!-- TODO: Dropdown for torrent type (with peers, without peers, etc) -->
	</fieldset>
	<div class="clr"></div>

	<table class="adminlist table table-hover table-striped" style="width:100%;">
		<thead>
			<tr>
				<th width="93%" align='center'><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_NAME', 't.name', $listDirn, $listOrder); ?></th>
				<th width="1%">&nbsp;</th>
				<?php if ($this->params->get('tl_category')) { ?>
					<th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort',	'JCATEGORY', 'c.title', $listDirn, $listOrder); ?>&nbsp;</th>
				<?php } ?>
				<th width="1%" align='center' nowrap><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_SIZE', 't.size', $listDirn, $listOrder); ?></th>
				<th width="1%" align='center' nowrap><?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_CREATED_TIME', 't.created_time', $listDirn, $listOrder); ?></th>
				<th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_LEECHERS_SMALL', 't.leechers', $listDirn, $listOrder); ?>&nbsp;</th>
				<th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_SEEDERS_SMALL', 't.seeders', $listDirn, $listOrder); ?>&nbsp;</th>
				<th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_COMPLETED_SMALL', 't.completed', $listDirn, $listOrder); ?>&nbsp;</th>
				<th width="1%" align='center' nowrap>&nbsp;<?php echo JHtml::_('grid.sort',	'COM_TRACKER_TORRENT_UPLOADER', 'torrent_owner', $listDirn, $listOrder); ?>&nbsp;</th>
				<?php if (TrackerHelper::user_permissions('download_torrents', $this->user->id)) { ?>
					<th width="1%" class='align'>DL</th>
				<?php } ?>
			</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $this->user->authorise('core.create',		'com_tracker');
			$canEdit	= $this->user->authorise('core.edit',			'com_tracker');
			$canCheckin	= $this->user->authorise('core.manage',		'com_tracker');
			$canChange	= $this->user->authorise('core.edit.state',	'com_tracker');
			$category_params = new JParameter( $item->category_params );
			?>
			<tr class="row<?php echo $i % 2; ?>" style="width:90%;">
				<td width="92%">
					<a href="<?php echo JRoute::_("index.php?option=com_tracker&view=torrent&id=".(int)$item->fid); ?>">
					<?php echo $this->escape(str_replace('_', ' ', $item->name)); ?>
					</a>
				</td>
				<td width="1%" align="right" nowrap>
					<?php if ($this->params->get('enable_torrent_type')) {
						echo TrackerHelper::checkTorrentType((int)$item->fid);
					} ?>
				</td>
				
				<?php if ($this->params->get('tl_category')) { ?>
				<td width="1%" align="center" nowrap>
					<?php if (is_file($_SERVER['DOCUMENT_ROOT'].JUri::root(true).DS.$category_params->get('image'))) { ?>
						<img id="image<?php echo $item->fid;?>" alt="<?php echo $item->torrent_category; ?>" src="<?php echo JUri::root(true).DS.$category_params->get('image'); ?>" width="36" />
					<?php }
						else echo '&nbsp;'.$item->torrent_category.'&nbsp;';
					?>
				</td>
				<?php } ?>
				
				<td width="1%" align="right" nowrap>&nbsp;<?php echo TrackerHelper::make_size($item->size);?>&nbsp;</td>
				<td width="1%" align="right" nowrap>&nbsp;<?php echo date('Y.m.d', strtotime($item->created_time));?>&nbsp;</td>
				<td width="1%" align="center" nowrap>&nbsp;<?php echo $item->leechers;?>&nbsp;</td>
				<td width="1%" align="center" nowrap>&nbsp;<?php echo $item->seeders;?>&nbsp;</td>
				<td width="1%" align="center" nowrap>&nbsp;<?php echo $item->completed;?>&nbsp;</td>

				<td align="right" nowrap>&nbsp;
				<?php 
				//echo $item->torrent_owner;
				if (($this->params->get('allow_upload_anonymous') == 0) || ($item->uploader_anonymous == 0) || ($item->uploader == $this->user->id)) echo $item->torrent_owner;
				else echo JText::_( 'COM_TRACKER_TORRENT_ANONYMOUS' );
				?>&nbsp;</td>
				<?php if (TrackerHelper::user_permissions('download_torrents', $this->user->id)) { ?>
					<td width="1%" align="center">
						<a href="<?php echo JRoute::_("index.php?option=com_tracker&task=torrent.download&id=".$item->fid); ?>">
							<img src="<?php echo JURI::base();?>components/com_tracker/assets/images/download.gif" alt="<?php echo JText::_( 'TORRENT_DOWNLOAD_TORRENT_LIST_ALT' ); ?>" border="0" />
						</a>
					</td>
				<?php } ?>
				<?php
				// experiment for Psylo to have number of thanks in torrent listing 
				if ($this->params->get('enable_thankyou')) {
					
					//if (!$item->thanks) $item->thanks = 0;
					//echo '<td width="1%" align="center">'.$item->thanks.'</td>';
					
				}
				?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($this->params->get('enable_torrent_type') && (
			$this->params->get('enable_torrent_type_new') ||
			$this->params->get('enable_torrent_type_top') ||
			$this->params->get('enable_torrent_type_hot') ||
			$this->params->get('enable_torrent_type_semifree') ||
			$this->params->get('enable_torrent_type_free') 
			)) { ?>
	<br />
	<div>
		<div><h2><?php echo JText::_( 'COM_TRACKER_LEGEND' );?>:</h2></div>
		<br />
		<?php if ($this->params->get('enable_torrent_type_new')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_new_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_new_text'), $this->params->get('torrent_type_new_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_top')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_top_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_top_text'), $this->params->get('torrent_type_top_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_hot')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_hot_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_hot_text'), $this->params->get('torrent_type_hot_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_semifree')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_semifree_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo JText::sprintf($this->params->get('torrent_type_semifree_text'), $this->params->get('torrent_type_semifree_value')); ?>
		</div>
		<?php } ?>
		<?php if ($this->params->get('enable_torrent_type_free')) { ?>
		<div>
			<img src="<?php echo JURI::base().$this->params->get('torrent_type_free_image');?>" border="0" />
			&nbsp;-&nbsp;
			<?php echo $this->params->get('torrent_type_free_text');?>
		</div>
		<?php } ?>
		
	</div>
	<?php } ?>

	<div class="pagination">
			<?php echo $this->pagination->getLimitBox(); ?>
			<?php echo $this->pagination->getPagesCounter(); ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
	</div>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>