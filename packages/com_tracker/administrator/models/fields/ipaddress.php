<?php
/**
 * @version			3.3.1-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('text');

/**
 * Form Field class for the Joomla Platform.
 * Provides and input field for ip v4 addresses
 *
 */
class JFormFieldIPAddress extends JFormFieldText {
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'IPAddress';

	/**
	 * Method to get the field input markup for ip v4 addresses.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Initialize some field attributes.
		$size         = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$class        = !empty($this->class) ? ' class="validate-ipaddress ' . $this->class . '"' : ' class="validate-ipaddress"';
		$readonly     = $this->readonly ? ' readonly' : '';
		$disabled     = $this->disabled ? ' disabled' : '';
		$required     = $this->required ? ' required="true"' : '';
		$hint         = $hint ? ' placeholder="' . $hint . '"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);

		return '<input type="ipaddress" name="' . $this->name . '"' . $class . ' id="' . $this->id . '" value="'
			. $this->value . '"' . ' spellcheck="false" '. $size . $disabled . $readonly
			. $onchange . ' autocomplete="off" maxlength="15" ' . $hint . $required. ' autofocus />';
	}
}
