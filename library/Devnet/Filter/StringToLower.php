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
 * @package    Devnet_Filter
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: StringToLower.php 5125 2007-06-05 20:12:16Z andries $
 */


/**
 * @see Devnet_Filter_Interface
 */
//require_once 'Devnet/Filter/Interface.php';


/**
 * @category   Zend
 * @package    Devnet_Filter
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Devnet_Filter_StringToLower implements Devnet_Filter_Interface
{
    /**
     * Encoding for the input string
     *
     * @var string
     */
    protected $_encoding = null;

    /**
     * Set the input encoding for the given string
     *
     * @param  string $encoding
     * @throws Devnet_Filter_Exception
     */
    public function setEncoding($encoding = null)
    {
        if (!function_exists('mb_strtolower')) {
            //require_once 'Devnet/Filter/Exception.php';
            throw new Devnet_Filter_Exception('mbstring is required for this feature');
        }
        $this->_encoding = $encoding;
    }

    /**
     * Defined by Devnet_Filter_Interface
     *
     * Returns the string $value, converting characters to lowercase as necessary
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        if ($this->_encoding) {
            return mb_strtolower((string) $value, $this->_encoding);
        }
        return strtolower((string) $value);
    }
}
