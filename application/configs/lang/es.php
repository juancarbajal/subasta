<?php
require_once('Zend/Validate/NotEmpty.php');
require_once('Zend/Validate/StringLength.php');
require_once('Zend/Validate/Between.php');
require_once('Zend/Validate/Alnum.php');
require_once('Zend/Validate/Digits.php');
require_once('Zend/Validate/Float.php');
require_once('Zend/Validate/Int.php');
require_once('Zend/Validate/Alpha.php');
require_once('Zend/Validate/Ccnum.php');
require_once('Zend/Validate/EmailAddress.php');
require_once('Zend/Validate/GreaterThan.php');
require_once('Zend/Validate/Iban.php');
require_once('Zend/Validate/Regex.php');
require_once('Zend/Validate/Date.php');
require_once('Zend/Validate/Hex.php');
require_once('Zend/Validate/Identical.php');
require_once('Zend/Validate/Ip.php');
require_once('Zend/Validate/Hostname.php');
require_once('Zend/Validate/InArray.php');
require_once('Zend/Validate/LessThan.php');

return array(Zend_Validate_NotEmpty::IS_EMPTY => 'El valor es requerido y no puede ser vacio',
             Zend_Validate_NotEmpty::INVALID => 'Invalido tipo esperado, el valor debe ser punto flotante, cadena, arreglo, booleano o entero',
             Zend_Validate_StringLength::INVALID   => "Inválido tipo esperado, el valor debe ser una cadena",
             Zend_Validate_StringLength::TOO_SHORT => "El valor es menor que %min% caracteres requeridos",
             Zend_Validate_StringLength::TOO_LONG  => "El valor es mayor que %max% caracteres requeridos",
             Zend_Validate_Between::NOT_BETWEEN        => "El valor no esta entre '%min%' y '%max%', incluyendolos",
             Zend_Validate_Between::NOT_BETWEEN_STRICT => "El valor no esta estrictamente entre '%min%' y '%max%'",
             Zend_Validate_Alnum::INVALID      => "Invalido tipo esperado, el valor debe ser punto flotante, cadena o entero",
             Zend_Validate_Alnum::NOT_ALNUM    => "El valor no tiene caracteres alfanumericos",
             Zend_Validate_Alnum::STRING_EMPTY => "El valor es una cadena vacia",
             Zend_Validate_Digits::NOT_DIGITS   => "El valor no contiene solo caracteres númerales",
             Zend_Validate_Digits::STRING_EMPTY => "El valor es requerido y no puede ser vacio",
             Zend_Validate_Digits::INVALID      => "Invalido tipo esperado, el valor debe ser flotante, cadena o entero",
             Zend_Validate_Float::INVALID   => "Invalido tipo esperado, el valor debe ser punto flotante, cadena o entero",
             Zend_Validate_Float::NOT_FLOAT => "El valor no es un punto flotante",
             Zend_Validate_Int::INVALID => "Invalido tipo esperado, el valor debe ser una cadena o un entero",
             Zend_Validate_Int::NOT_INT => "El valor no es un entero",
             Zend_Validate_Alpha::INVALID      => "Invalido tipo esperado, el valor debe ser una cadena",
             Zend_Validate_Alpha::NOT_ALPHA    => "El valor no tiene solo caracteres alfabeticos",
             Zend_Validate_Alpha::STRING_EMPTY => "El valor es requerido y no puede ser vacio",
             /*
              Zend_Validate_Ccnum::LENGTH   => "'%value%' debe contener entre 13 y 19 digitos",
              Zend_Validate_Ccnum::CHECKSUM => "Algoritmo Luhm (mod-10 checksum) fallo en '%value%'",
             */
              Zend_Validate_EmailAddress::INVALID            => "Invalido tipo esperado, el valor debe ser una cadena",
              Zend_Validate_EmailAddress::INVALID_FORMAT     => "El valor no es una dirección de correo valida",
              Zend_Validate_EmailAddress::INVALID_HOSTNAME   => "'%hostname%' no es un Hostname valido para la dirección de correo '%value%'",
              Zend_Validate_EmailAddress::INVALID_MX_RECORD  => "'%hostname%' no contiene un valido registro MX para la dirección del correo '%value%'",
              Zend_Validate_EmailAddress::DOT_ATOM           => "'%localPart%' not matched against dot-atom format",
              Zend_Validate_EmailAddress::QUOTED_STRING      => "'%localPart%' no contien una cadena en formato valido",
              Zend_Validate_EmailAddress::INVALID_LOCAL_PART => "'%localPart%' no es una parte valida de una dirección de correo '%value%'",
              Zend_Validate_EmailAddress::LENGTH_EXCEEDED    => "El valor excede el tamaño permitido",
             /*
              Zend_Validate_GreaterThan::NOT_GREATER => "'%value%' no es mayor que '%min%'",
              Zend_Validate_Iban::NOTSUPPORTED => "'%value%' no tiene IBAN",
              Zend_Validate_Iban::FALSEFORMAT  => "'%value%' tiene un formato falso",
              Zend_Validate_Iban::CHECKFAILED  => "'%value%' tuvo fallas en el chequeo del IBAN",
             */
              
              Zend_Validate_Regex::INVALID   => "Invalido tipo esperado, el valor debe ser una cadena, entero o punto flotante",
              Zend_Validate_Regex::NOT_MATCH => "'%value%' no cumple con el patrón '%pattern%'",
             
              Zend_Validate_Date::INVALID        => "Invalido tipo esperado, el valor debe ser una cadena, entero, array o Zend_Date",
              //Zend_Validate_Date::NOT_YYYY_MM_DD => "El valor no esta en el formato AAAA-MM-DD",
              Zend_Validate_Date::INVALID_DATE   => "El valor no aparece como una fecha válida",
              Zend_Validate_Date::FALSEFORMAT    => "El valor no tiene un formato de fecha válido",
             
              Zend_Validate_Hex::INVALID => "Invalido tipo esperado, el valor debe ser una cadena",
              Zend_Validate_Hex::NOT_HEX => "El valor no contiene digitos hexadecimales válidos",
              Zend_Validate_Identical::NOT_SAME      => "La ficha '%token%' no es igual a la ficha esperada '%value%'",
              Zend_Validate_Identical::MISSING_TOKEN => 'La ficha no provee una el valor esperado',
              Zend_Validate_Ip::INVALID        => "Invalido tipo esperado, el valor debe ser una cadena",
              Zend_Validate_Ip::NOT_IP_ADDRESS => "'%value%' no parece ser una dirección IP válida",
              Zend_Validate_Hostname::INVALID                 => "Invalido tipo esperado, el valor debe ser una cadena",
              Zend_Validate_Hostname::IP_ADDRESS_NOT_ALLOWED  => "'%value%' parece ser una dirección IP, pero la dirección IP no es permitida",
              Zend_Validate_Hostname::UNKNOWN_TLD             => "'%value%' parece ser un nombre de host DNS, pero no aparece en la lista TLD",
              Zend_Validate_Hostname::INVALID_DASH            => "'%value%' parece ser un nombre de host DNS, pero contiene un guíon (-) en una posición invalida",
              Zend_Validate_Hostname::INVALID_HOSTNAME_SCHEMA => "'%value%' parece ser un nombre de host DNS, pero no cumple con un esquema de host para TLD '%tld%'",
              Zend_Validate_Hostname::UNDECIPHERABLE_TLD      => "'%value%' parece ser un nombre de host DNS, pero no extra la parte TLD",
              Zend_Validate_Hostname::INVALID_HOSTNAME        => "'%value%' no es una estructura esperado de nombre de dominio DNS",
              Zend_Validate_Hostname::INVALID_LOCAL_NAME      => "'%value%' no aparece ser un nombre de red local",
              Zend_Validate_Hostname::LOCAL_NAME_NOT_ALLOWED  => "'%value%' parece ser una nombre de red local, pero el nombre de red local no ha sido asignado",
              Zend_Validate_Hostname::CANNOT_DECODE_PUNYCODE  => "'%value%' parece ser un nombre de host DNS, pero la notación no puede ser decodificada",
              Zend_Validate_InArray::NOT_IN_ARRAY => "'%value%' no se encontro en el arreglo",
              Zend_Validate_LessThan::NOT_LESS => "'%value%' no es menor que '%max%'"
             
             );