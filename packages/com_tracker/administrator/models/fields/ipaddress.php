<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
 
class JFormFieldIPAddress extends JFormField {
    protected $type = 'IPAddress';
 
    protected function getInput() {
    	return '<input type="text" name="'.$this->name.'" id="'.$this->value.'" class="inputbox" size="15" maxlength="15" value="'.long2ip($this->value).'" />';
    }
}

