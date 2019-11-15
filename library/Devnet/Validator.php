<?php
/**
 * Descripción Corta
 * 
 * Descripción Larga
 * 
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer el archivo LICENSE
 * @version    1.0
 * @since      Archivo disponible desde su version 1.0
 */
/**
 * Descripción Corta
 * Descripción Larga
 * @category   
 * @package    
 * @subpackage    
 * @copyright  Leer archivo COPYRIGHT 
 * @license    Leer archivo LICENSE
 * @version    Release: @package_version@
 * @link
 */
//require('/application/lang/es.php');
class Devnet_Validator
{
    /**
     * @var array
     */
    protected $_errors;

    /**
     * @var array
     */
    protected $_validates;
    
    /**
     * @var array
     */
    protected $_labels;

    /**
     * Añadir error al Validador
     * @param string $index nombre del objeto en el cual ocurre el error
     * @param array|string $messages Lista de mensajes que se añaden a determinado Índice
     * @param string $label Label con el cual se identificara el error
     */
    function addError ($index, $messages = null, $label = null)
    {        
        if (($prevLabel = $this->getLabel($index)) == null) {            
            $this->addLabel($index, $label);
            $prevLabel = $this->getLabel($index);
        } //end if
        if ($messages != null) {            
            if (is_array($messages)){
                foreach($messages as $message):
                    //array_push($this->_errors[$prevLabel], $message);
                    $this->_errors[$prevLabel][] = $message;
                endforeach;
            } else {
                //array_push($this->_errors[$prevLabel], $messages);
                $this->_errors[$prevLabel][] = $messages;
            }
        } else {
            if (!is_array($this->_errors[$prevLabel])) {
                $this->_errors[$prevLabel] = null;
            } //end if
        }//end function        
    }

    /**
     * Añade un label en un Índice determinado
     * @param integer $index Índice del Label
     * @param string $label Label que se añadira     
     */
    public function addLabel($index, $label)
    {
        if (!isset($label)) {
            $label = ucwords($index);
        } //end if
        $this->_labels[$index] = $label;
    } // end function
    
    /**
     * Captura el Label determinado según un Índice
     * @param integer $index Índice de Label
     * @return string Label encontrado según el Índice
     */
    public function getLabel($index)
    {
        return $this->_labels[$index];
    } // end function
    
    /**
     * Añade validador (Zend_Validate) a la lista de validaciones
     * @param string $index índice (identificador) del error
     * @param Zend_Validate $validate Validador añadido a alista de validación
     * @param string $label Texto indicado en la validación
     */
    function add($index, Zend_Validate $validate, $label = null)
    {
        $this->_validates[$index] = $validate;
        $this->addLabel($index, $label);
        $this->addError($index, null, $label);
    } //end function

    /**
     * Valida la lista de Validaciones ingresada
     * @param array $data Lista de valores a validar
     * @return boolean Resultado de la validación total de la lista
     */
    function isValid ($data = null) 
    { 
        $result = true;
        if (is_array($data)) {
            foreach ($this->_validates as $index => $validate){
                $isValid = $validate->isValid($data[$index]);                
                $this->addError($index, (!$isValid)? $validate->getMessages() : null );
            } 
        } //end function
        return ($this->countErrors() == 0);
    }

    /**
     * Captura el array de errores
     * @return array Lista de errores
     */
    function getErrors () 
    { 
        return $this->_errors;
    } //end function

    /**
     * Captura el array de errores de un determinado Índice
     * @param integer $index Índice del error a capturar
     * @return array|string Lista de errores de determiando Índice
     */
    public function getErrorById($index)
    {
        return $this->_errors[$this->_labels[$index]];
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function countErrors()
    {
        $count = 0;
        foreach ($this->_errors as $error) :
            $count += (isset($error))? 1 : 0;
        endforeach; //end foreach
        return $count;
    } // end function
} //end class