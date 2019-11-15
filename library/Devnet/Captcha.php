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
class Devnet_Captcha extends Zend_Captcha_Image {
	/**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
	public function __construct($options) {
		$options['name']=(isset($options['name']))?$options['name']:'captcha';
		$options['font']=(isset($options['font']))?$options['font']:'/usr/share/fonts/truetype/freefont/FreeMonoBold.ttf';
		$options['wordLen']=(isset($options['wordLen']))?$options['wordLen']:8;
		$options['timeout']=(isset($options['timeout']))?$options['timeout']:300;		
		$options['imgDir']=(isset($options['imgDir']))?$options['imgDir']:APPLICATION_PATH.'/../public/img/captchas';
		$options['imgUrl']=(isset($options['imgUrl']))?$options['imgUrl']:$options['baseUrl'].'/img/captchas'; 
        $options['dotNoiseLevel']=(isset($options['dotNoiseLevel']))?$options['dotNoiseLevel']:50;
        $options['lineNoiseLevel']=(isset($options['lineNoiseLevel']))?$options['lineNoiseLevel']:4;
        $options['width']=(isset($options['width']))?$options['width']:290;
        $options['height']=(isset($options['height']))?$options['height']:80;
        $options['messages'] = array('badCaptcha' => 'Captcha invalido');
		parent::__construct($options);
	}
}