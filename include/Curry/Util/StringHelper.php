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
namespace Curry\Util;
use Curry\App;

/**
 * A static class with utility string functions.
 *
 * @package Curry\Util
 */
class StringHelper
{
	/**
	 * Check if string $a starts with $b
	 *
	 * @param string $a
	 * @param string $b
	 * @return bool
	 */
	public static function startsWith($a, $b)
	{
		return substr($a, 0, strlen($b)) === $b;
	}
	
	/**
	 * Check if string $a ends in $b
	 *
	 * @param string $a
	 * @param string $b
	 * @return bool
	 */
	public static function endsWith($a, $b)
	{
		return substr($a, -strlen($b)) === $b;
	}
	
	/**
	 * Transform string to use only ascii alpha-numeric characters (meaning a-z, 0-9 and hyphen). Useful to create URL slugs.
	 *
	 * @param string $sString
	 * @return string
	 */
	public static function getRewriteString($sString)
	{
		 $string = strtolower(htmlentities($sString, null, 'utf-8'));
		 $string = preg_replace("/&(.)(uml);/", "$1e", $string);
		 $string = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $string);
		 $string = preg_replace("/([^a-z0-9]+)/", "-", html_entity_decode($string));
		 $string = trim($string, "-");
		 return $string;
	}
	
	/**
	 * Generates a "quoted-string" commonly used in HTTP headers.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function escapeQuotedString($value)
	{
		return '"'.addcslashes($value, "\\").'"';
	}
	
	/**
	 * Break up long words using a separator.
	 *
	 * @param string $value Subject to break up.
	 * @param integer $limit Maximum number of consecutive non-space characters.
	 * @param string $break String used as a separator. Default is soft-hyphen.
	 * @return string
	 */
	public static function breakLongWords($value, $limit, $break = "\xC2\xAD")
	{
		return preg_replace('/([^\s]{'.$limit.'})/u', "$1$break", $value);
	}
	
	/**
	 * Parse content as HTML and break up element text-content.
	 *
	 * @param string $html HTML content to break up.
	 * @param integer $limit Maximum number of consecutive non-space characters.
	 * @param string $break String used as a separator. Default is soft-hyphen.
	 * @return string
	 */
	public static function breakLongWordsHtml($html, $limit, $break = "\xC2\xAD")
	{
		$doc = new \DOMDocument();
		$doc->loadXML("<root>".$html."</root>");
		self::_breakWordsNode($doc, $limit, $break);
		$xml = $doc->saveXML();
		
		// keep the inner contents of the <root> element
		return substr($xml, 28, -8);
	}
	
	/**
	 * Internal function used by breakLongWordsHtml to process HTML nodes.
	 *
	 * @param \DOMNode $node
	 * @param integer $limit
	 * @param string $break
	 */
	private static function _breakWordsNode(\DOMNode $node, $limit, $break)
	{
		if ($node->nodeType == XML_TEXT_NODE) {
			$node->nodeValue = self::breakLongWords($node->nodeValue, $limit, $break);
		} else if ($node->nodeType == XML_ELEMENT_NODE || $node->nodeType == XML_DOCUMENT_NODE || $node->nodeType == XML_HTML_DOCUMENT_NODE) {
			foreach ($node->childNodes as $childNode)
				self::_breakWordsNode($childNode, $limit, $break);
		}
	}
}
