<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

?>
<form action="<?php echo JRoute::_('index.php?option=com_tracker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="banhost-form" class="form-validate">
				<li><?php echo $this->form->getLabel('id'); ?><?php echo $this->form->getInput('id'); ?></li>
				<li><?php echo $this->form->getLabel('begin'); ?><?php echo $this->form->getInput('begin');?></li>
				<li><?php echo $this->form->getLabel('end'); ?><?php echo $this->form->getInput('end'); ?></li>
				<li><?php echo $this->form->getLabel('comment'); ?><?php echo $this->form->getInput('comment'); ?></li>

