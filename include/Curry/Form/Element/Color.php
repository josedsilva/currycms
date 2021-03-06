<?php
/**
 * Curry CMS
 *
 * LICENSE
 *
 * This source file is subject to the GPL license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://currycms.com/license
 *
 * @category   Curry CMS
 * @package    Curry
 * @copyright  2011-2012 Bombayworks AB (http://bombayworks.se)
 * @license    http://currycms.com/license GPL
 * @link       http://currycms.com
 */

/**
 * Creates a colorpicker element.
 * 
 * @package Curry\Form
 */
class Curry_Form_Element_Color extends Zend_Form_Element_Text
{
	/**
	 * Override attributes to append colorpicker class.
	 *
	 * @return array
	 */
	public function getAttribs()
	{
		$attribs = parent::getAttribs();
		$class = isset($attribs['class']) ? array($attribs['class']) : array();
		$class[] = 'colorpicker';
		$attribs['class'] = join(" ", $class);
		return $attribs;
	}
}
