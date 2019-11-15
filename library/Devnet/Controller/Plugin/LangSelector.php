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
class Devnet_Controller_Plugin_LangSelector
    extends Zend_Controller_Plugin_Abstract 
{
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
	function preDispatch(Zend_Controller_Request_Abstract $request) {
		$lang=$request->getParam('lang','');
		if ((strtolower($lang)!=='es') && (strtolower($lang)!=='en')){
		    $request->setParam('lang','es');
		}
		$lang=$request->getParam('lang');
		if ($lang=='es')
		    $locale='es_ES';
		else 
		    $locale='en_CA';
		$zl = new Zend_Locale();
		$zl->setLocale($locale);
		Zend_Registry::set('Zend_Locale',$zl);
		$translate = new Zend_Translate('csv',APPLICATION_PATH.'/configs/lang/'.$lang.'.csv',$lang);
		Zend_Registry::set('Zend_Translate',$translate);
		//var_dump($request->getParams()); 
		//die("Idioma : ".$lang);
	}
}