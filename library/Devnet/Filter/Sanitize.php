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
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id:$
 */
/**
 * @see Devnet_Filter_Interface
 */
////require_once'Devnet/Filter/Interface.php';
/**
 * @see Devnet_Filter_StringToLower
 */
////require_once'Devnet/Filter/StringToLower.php';
/**
 * @see Devnet_Filter_StringTrim
 */
////require_once'Devnet/Filter/StringTrim.php';
/**
 * @category   Zend
 * @package    Devnet_Filter
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Devnet_Filter_Sanitize implements Devnet_Filter_Interface
{
    /**
     * Character(s) used to replace delimiters
     *
     * @var string
     */
    protected $_delimiterReplacement;

    /**
     * Word delimiters which are replaced by spaceReplacement string
     *
     * @var array
     */
    protected $_wordDelimiters = array(' ', '.', '\\', '/', '-', '_');

    /**
     * Which characters are not replaced
     *
     * @var array
     */
    protected $_notReplacedChars = array();

    /**
     *
     * @param string $delimiterReplacement
     * @param string|array $wordDelimiters
     */
    public function __construct ($delimiterReplacement = '-', $wordDelimiters = null)
    {
        $this->setDelimiterReplacement($delimiterReplacement);
        if (null !== $wordDelimiters) {
            $this->addWordDelimiter($wordDelimiters);
        }
    }

    /**
     * Defined by Devnet_Filter_Interface
     *
     * Returns $value filtered to valid url
     *
     * @param  string $value
     * @return string
     */
    public function filter ($s)
    {
        //convert to ascii -> translate strange chars
        //$s = $this->_convertToAscii($s);
        //trim spaces
        $s = $this->_trimString($s);
        //replace delimiters with another character
        $s = $this->_replaceDelimiters($s);
        //lower chars
        $s = $this->_toLowerChars($s);
        //delete chars except a-z0-9
        $s = $this->_trimSpecialsChars($s);
        //replace double dashes with single one
        $s = $this->_replaceDoubleDelimiterReplacementWithSingle($s);
        //trim dashes on beginning/end of the string
        $s = $this->_trimStartAndEndSpaceReplacement($s);
        return $s;
    }

    public function addNotReplacedChars($notReplaced)
    {
        if (in_array($notReplaced, $this->getNotReplacedChars())) {
            //require_once'Devnet/Filter/Exception.php';
            throw new Devnet_Filter_Exception("Not replaced characterr '$notReplaced' is already there.");
        }
        if (empty($notReplaced)) {
            //require_once'Devnet/Filter/Exception.php';
            throw new Devnet_Filter_Exception('Not replaced character cannot be null.');
        }
        if (is_array($notReplaced)) {
            $this->_notReplacedChars = array_merge($this->getNotReplacedChars(), $notReplaced);
        } else {
            $this->_notReplacedChars[] = $notReplaced;
        }
        return $this;
    }

    /**
     * returns chars which are not replaced
     *
     * @return array
     */
    public function getNotReplacedChars ()
    {
        return $this->_notReplacedChars;
    }

        /**
     * Remove not replaced character
     *
     * @param string|array $notReplaced
     * @return Devnet_Filter_Sanitize
     */
    public function removeNotReplacedChar ($notReplaced)
    {
        if (empty($notReplaced)) {
            //require_once'Devnet/Filter/Exception.php';
            throw new Devnet_Filter_Exception('Not replaced character cannot be null.');
        }
        if (is_array($notReplaced)) {
            foreach ($notReplaced as $n) {
                $this->removeNotReplacedChar($n);
            }
        } else {
            if (! in_array($notReplaced, $this->getNotReplacedChars())) {
                //require_once'Devnet/Filter/Exception.php';
                throw new Devnet_Filter_Exception("Not replaced character '$notReplaced' is not in array.");
            }
            $newArray = array();
            foreach ($this->_notReplacedChars as $n) {
                if ($n != $notReplaced) {
                    $newArray[] = $n;
                }
                $this->_notReplacedChars = $newArray;
            }
        }
        return $this;
    }

    /**
     * Returns the delimiterReplacement option
     *
     * @return string
     */
    public function getDelimiterReplacement ()
    {
        return $this->_delimiterReplacement;
    }

    /**
     * Sets the delimiterReplacement option
     *
     * @param  string $delimiterReplacement
     * @return Devnet_Filter_Sanitize Provides a fluent interface
     */
    public function setDelimiterReplacement ($delimiterReplacement)
    {
        $this->_delimiterReplacement = $delimiterReplacement;
        return $this;
    }

    /**
     * Returns word delimiters array
     *
     * @return array
     */
    public function getWordDelimiters ()
    {
        return $this->_wordDelimiters;
    }

    /**
     * Add word delimiter
     *
     * @param string|array $delimiter
     * @return Devnet_Filter_Sanitize
     */
    public function addWordDelimiter ($delimiter)
    {
        if (in_array($delimiter, $this->getWordDelimiters())) {
            //require_once'Devnet/Filter/Exception.php';
            throw new Devnet_Filter_Exception("Word delimiter '$delimiter' is already there.");
        }
        if (empty($delimiter)) {
            //require_once'Devnet/Filter/Exception.php';
            throw new Devnet_Filter_Exception('Word delimiter cannot be null.');
        }
        if (is_array($delimiter)) {
            $this->_wordDelimiters = array_merge($this->getWordDelimiters(), $delimiter);
        } else {
            $this->_wordDelimiters[] = $delimiter;
        }
        return $this;
    }


    /**
     * Remove word delimiter
     *
     * @param string|array $delimiter
     * @return Devnet_Filter_Sanitize
     */
    public function removeWordDelimiter ($delimiter)
    {
        if (empty($delimiter)) {
            //require_once'Devnet/Filter/Exception.php';
            throw new Devnet_Filter_Exception('Word delimiter cannot be null.');
        }
        if (is_array($delimiter)) {
            foreach ($delimiter as $delim) {
                $this->removeWordDelimiter($delim);
            }
        } else {
            if (! in_array($delimiter, $this->getWordDelimiters())) {
                //require_once'Devnet/Filter/Exception.php';
                throw new Devnet_Filter_Exception("Word delimiter '$delimiter' is not in delimiters array.");
            }
            $newArray = array();
            foreach ($this->_wordDelimiters as $delim) {
                if ($delim != $delimiter) {
                    $newArray[] = $delim;
                }
                $this->_wordDelimiters = $newArray;
            }
        }
        return $this;
    }

    /**
     * trim spaces, tabs, newlines
     *
     * @param string $s
     * @return string
     */
    private function _trimString ($s)
    {
        $trimFilter = new Devnet_Filter_StringTrim();
        return $trimFilter->filter($s);
    }

    /**
     * replace delimiters with another string
     *
     * @param string $s
     * @return string
     */
    private function _replaceDelimiters ($s)
    {
        foreach ($this->getWordDelimiters() as $delimiter) {
            if ($delimiter == $this->getDelimiterReplacement()) {
                continue;
            }
            if (in_array($delimiter, $this->getNotReplacedChars())) {
                continue;
            }

            $s = str_replace($delimiter, $this->getDelimiterReplacement(), $s);
        }
        return $s;
    }

    /**
     * convert to ascii -> translate strange chars
     *
     * @param string $s
     * @return string
     */
    private function _convertToAscii ($s)
    {
        //FIXME use autoload or shorter require
        ////require_once'Devnet/Filter/Transliteration.php';
        $f = new Devnet_Filter_Transliteration();
        return $f->filter($s);
    }

    /**
     * to lower chars
     *
     * @param string $s
     * @return string
     */
    private function _toLowerChars ($s)
    {
        $lowerFilter = new Devnet_Filter_StringToLower();
        $lowerFilter->setEncoding('utf8');
        return $lowerFilter->filter($s);
    }

    /**
     * to lower chars
     *
     * @param string $s
     * @return string
     */
    private function _trimSpecialsChars ($s)
    {
        if (count($this->getNotReplacedChars()) == 0) {
            $reg = '~[^-a-z0-9_]+~';
        } else {
            $reg = '~[^-a-z0-9_' . implode('',$this->getNotReplacedChars()) .']+~';
        }
        return preg_replace($reg, '', $s);
    }

    /**
     * replace double delimiter with single one
     *
     * @param string $s
     * @return string
     */
    private function _replaceDoubleDelimiterReplacementWithSingle ($s)
    {
        $doubleDelimiterReplacement = $this->getDelimiterReplacement() . $this->getDelimiterReplacement();
        while (strpos($s, $doubleDelimiterReplacement) !== false) {
            $s = str_replace($doubleDelimiterReplacement, $this->getDelimiterReplacement(), $s);
        }
        return $s;
    }

    /**
     * trim dashes on beginning/end of the string
     *
     * @param string $s
     * @return string
     */
    private function _trimStartAndEndSpaceReplacement ($s)
    {
        return trim($s, $this->getDelimiterReplacement());
    }
}