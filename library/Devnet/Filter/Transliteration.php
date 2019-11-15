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
 * @version    $Id: $
 */
/**
 * @see Devnet_Filter_Interface
 */
//require_once 'Devnet/Filter/Interface.php';
/**
 * @category   Zend
 * @package    Devnet_Filter
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Devnet_Filter_Transliteration implements Devnet_Filter_Interface
{

    /**
     * Defined by Devnet_Filter_Interface
     *
     * Returns $value translitered to ASCII
     *
     * @param  string $value
     * @return string
     */
    public function filter ($value)
    {
        //translitere specific chars
        $value = $this->_transliterateSpanish($value);
        /*$value = $this->_transliterateCzech($value);
        $value = $this->_transliterateRussian($value);
        $value = $this->_transliterateGerman($value);
        $value = $this->_transliterateFrench($value);
        $value = $this->_transliterateHungarian($value);*/

        //split string to single characters
        $characters = mb_split("~(.)~", $value);

        $return = '';
        foreach ($characters as $character) {
            /*  maybe should contain also //IGNORE  */
            $converted = iconv("utf-8", "ASCII//TRANSLIT", $character);

            //if character was converted, strip out wrong marks
            if ($character !== $converted) {
                $return .= preg_replace('~["\'^]+~', '', $converted);
            } else {
                $return .= $converted;
            }
        }
        return $return;
    }

    /**
     * Transliterate Russian chars (Cyrillic)
     *
     * @param string $s
     * @return string
     */
    private function _transliterateSpanish ($s)
    {
        $replace=array("á","à","é","è","í","ì","ó","ò","ú","ù","ñ","Ñ","Á","À","É","È","Í","Ì","Ó","Ò","Ú","Ù");
    		$change=array("a","a","e","e","i","i","o","o","u","u","n","N","A","A","E","E","I","I","O","O","U","U");
    
        $table = array (
            "?" => "a",
            "à" => "a",
            "é" => "e",
            "è" => "e",
            "?" => "a",
            "" => "E",
            "Е" => "JE",
            "Ё" => "JO",
            "Ж" => "ZH",
            "З" => "Z",
            "И" => "I",
            "Й" => "J",
            "К" => "K",
            "Л" => "L",
            "М" => "M",
            "Н" => "N",
            "О" => "O",
            "П" => "P",
            "Р" => "R",
            "С" => "S",
            "Т" => "T",
            "У" => "U",
            "Ф" => "F",
            "Х" => "KH",
            "Ц" => "TS",
            "Ч" => "CH",
            "Ш" => "SH",
            "Щ" => "SHCH",
            "Ъ" => "",
            "Ы" => "Y",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "JU",
            "Я" => "JA",
            "Ґ" => "G",
            "Ї" => "I",
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "є" => "e",
            "е" => "je",
            "ё" => "jo",
            "ж" => "zh",
            "з" => "z",
            "и" => "i",
            "й" => "j",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "kh",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "shch",
            "ъ" => "",
            "ы" => "y",
            "ь" => "",
            "э" => "e",
            "ю" => "ju",
            "я" => "ja",
            "ґ" => "g",
            "ї" => "i"
        );
        return strtr($s, $table);
    }

    /**
     * Transliterate Russian chars (Cyrillic)
     *
     * @param string $s
     * @return string
     */
    private function _transliterateRussian ($s)
    {
        $table = array (
            "А" => "A",
            "Б" => "B",
            "В" => "V",
            "Г" => "G",
            "Д" => "D",
            "Є" => "E",
            "Е" => "JE",
            "Ё" => "JO",
            "Ж" => "ZH",
            "З" => "Z",
            "И" => "I",
            "Й" => "J",
            "К" => "K",
            "Л" => "L",
            "М" => "M",
            "Н" => "N",
            "О" => "O",
            "П" => "P",
            "Р" => "R",
            "С" => "S",
            "Т" => "T",
            "У" => "U",
            "Ф" => "F",
            "Х" => "KH",
            "Ц" => "TS",
            "Ч" => "CH",
            "Ш" => "SH",
            "Щ" => "SHCH",
            "Ъ" => "",
            "Ы" => "Y",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "JU",
            "Я" => "JA",
            "Ґ" => "G",
            "Ї" => "I",
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "є" => "e",
            "е" => "je",
            "ё" => "jo",
            "ж" => "zh",
            "з" => "z",
            "и" => "i",
            "й" => "j",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "kh",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "shch",
            "ъ" => "",
            "ы" => "y",
            "ь" => "",
            "э" => "e",
            "ю" => "ju",
            "я" => "ja",
            "ґ" => "g",
            "ї" => "i"
        );
        return strtr($s, $table);
    }

        /**
     * Transliterate Czech chars
     *
     * @param string $s
     * @return string
     */
    private function _transliterateCzech ($s)
    {
        $table = array (
            'á' => 'a',
            'č' => 'c',
            'ď' => 'd',
            'é' => 'e',
            'ě' => 'e',
            'í' => 'i',
            'ň' => 'n',
            'ó' => 'o',
            'ř' => 'r',
            'š' => 's',
            'ť' => 't',
            'ú' => 'u',
            'ů' => 'u',
            'ý' => 'y',
            'ž' => 'z',
                'Á' => 'A',
            'Č' => 'C',
            'Ď' => 'D',
            'É' => 'E',
            'Ě' => 'E',
            'Í' => 'I',
                'Ň' => 'N',
            'Ó' => 'O',
            'Ř' => 'R',
            'Š' => 'S',
            'Ť' => 'T',
            'Ú' => 'U',
            'Ů' => 'U',
            'Ý' => 'Y',
            'Ž' => 'Z',
        );
        return strtr($s, $table);
    }

        /**
     * Transliterate German chars
     *
     * @param string $s
     * @return string
     */
    private function _transliterateGerman ($s)
    {
        $table = array (
            'ä' => 'ae',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'oe',
            'ü' => 'ue',
            'Ä' => 'Ae',
            'Ë' => 'E',
            'Ï' => 'I',
            'Ö' => 'Oe',
            'Ü' => 'Ue',
            'ß' => 'ss',
        );
        return strtr($s, $table);
    }

        /**
     * Transliterate French chars
     *
     * @param string $s
     * @return string
     */
    private function _transliterateFrench ($s)
    {
        $table = array (
            'â' => 'a',
            'ê' => 'e',
            'î' => 'i',
            'ô' => 'o',
            'û' => 'u',
            'Â' => 'A',
            'Ê' => 'E',
            'Î' => 'I',
            'Ô' => 'O',
            'Û' => 'U',
            'œ' => 'oe',
            'æ' => 'ae',
            'Ÿ' => 'Y',
            'ç' => 'c',
                'Ç' => 'C',
        );
        return strtr($s, $table);
    }

        /**
     * Transliterate Hungarian chars
     *
     * @param string $s
     * @return string
     */
    private function _transliterateHungarian ($s)
    {
        $table = array (
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ö' => 'o',
            'ő' => 'o',
            'ú' => 'u',
            'ü' => 'u',
            'ű' => 'u',
        );
        return strtr($s, $table);
    }


}
