<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Alnum.php 16870 2009-07-20 10:17:59Z mikaelkael $
 */

/**
 * @see Zend_Filter_Interface
 */
//require_once 'Devnet/Filter/Interface.php';
/**
 * @see Zend_Locale
 */
//require_once 'Devnet/Locale.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Devnet_Filter_Alnum extends Zend_Filter_Alnum
{
     public function __construct($allowWhiteSpace = false)
    {
    	parent::__construct();
    }
    
    public function filter($value, $valuechange='', $min ='')
    {

    	if (!self::$_unicodeEnabled) {
		// POSIX named classes are not supported, use alternative a-zA-Z0-9 match
		$pattern = '/[^a-zA-Z0-9]/';
		} else if (self::$_meansEnglishAlphabet) {
		//The Alphabet means english alphabet.
		$pattern = '/[^a-zA-Z0-9]/u';
		} else {
		//The Alphabet means each language's alphabet.
		$pattern = '/[^\p{L}\p{N}]/u';
		}
    	if(trim($valuechange)!=""){
    		$str = preg_replace('/\s\s+/',' ',preg_replace($pattern,' ',$value));
    		$replace=array("á","à","é","è","í","ì","ó","ò","ú","ù","ñ","Ñ","Á","À","É","È","Í","Ì","Ó","Ò","Ú","Ù");
    		$change=array("a","a","e","e","i","i","o","o","u","u","n","N","A","A","E","E","I","I","O","O","U","U");
    		$str=str_replace($replace,$change,$str);
                if($min == 1)
                    return str_replace(" ",$valuechange,strtoupper($str));
                if($min == 0)
                    return str_replace(" ",$valuechange,strtolower($str));
                if($min == '')
                    return str_replace(" ",$valuechange,$str);
    	}
    	else
    	{
    		return parent::filter($value);
    	}
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */

    public function urlFriendly($value, $valuechange='', $min ='')
    {

    	if (!self::$_unicodeEnabled) {
		// POSIX named classes are not supported, use alternative a-zA-Z0-9 match
		$pattern = '/[^a-zA-Z0-9]/';
		} else if (self::$_meansEnglishAlphabet) {
		//The Alphabet means english alphabet.
		$pattern = '/[^a-zA-Z0-9]/u';
		} else {
		//The Alphabet means each language's alphabet.
		$pattern = '/[^\p{L}\p{N}]/u';
		}
    	if(trim($valuechange)!=""){
    		$str = preg_replace('/\s\s+/',' ',preg_replace($pattern,' ',$value));
    		$replace=array("á","à","é","è","í","ì","ó","ò","ú","ù","ñ","Ñ","Á","À","É","È","Í","Ì","Ó","Ò","Ú","Ù");
    		$change=array("a","a","e","e","i","i","o","o","u","u","n","N","A","A","E","E","I","I","O","O","U","U");
    		$str=str_replace($replace,$change,$str);
                if($min == 1)
                    return str_replace(" ",$valuechange,strtoupper($str));
                if($min == 0)
                    return str_replace(" ",$valuechange,strtolower($str));
                if($min == '')
                    return str_replace(" ",$valuechange,$str);
    	}
    	else
    	{
    		return parent::filter($value);
    	}
    }

}
