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
class Devnet_Form extends Zend_Form {
    protected $_options;
    protected $_defaults = array('lang' => 'es', 'accept' => 'on'); 
  /**
   * Descripción Corta
   * Descripción Larga
   * @category   
   * @package    
   * @copyright  Copyright (c) 2008 Juan Carbajal Paxi <juancarbajal@gmail.com>
   * @license    Leer archivo LICENSE
   */
    public $formDecorators = array( 
                                  'FormElements', 
                                  array('HtmlTag', array('tag'=>'div')), 
                                  array('Fieldset',array('class'=>'')), 
                                  'Form',
                                  //array('Errors', array('placement'=>'prepend')), 
                                  );
    
   /*public function __construct($options,$caption) {
   	$this->formDecorators[2][1]['legend']=$caption;
   	return parent::__construct($options);
   }*/
  /**
   * Descripción Corta
   * Descripción Larga
   * @category   
   * @package    
   * @copyright  Copyright (c) 2008 Juan Carbajal Paxi <juancarbajal@gmail.com>
   * @license    Leer archivo LICENSE
   */
   function init() {
        //$op=$this->getOptions();
        //$this->formDecorators[2][1]['legend']=$op->caption;
        $this->clearDecorators();
        $this->setDisableLoadDefaultDecorators(true);  
        $this->setDecorators($this->formDecorators);
    } //end function
    
    /**
     * Set form state from options array
     *
     * @param  array $options
     * @return Zend_Form
     */
    public function setOptions(array $options)
    {
        $this->_options=$options;
        parent::setOptions($options);           
    }                   
    public function getOptions(){
        return $this->_options;
    }     
}
?>