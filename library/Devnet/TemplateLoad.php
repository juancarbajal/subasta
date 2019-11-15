<?php
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
class Devnet_TemplateLoad
{
    /**
     * @var type
     */
    protected $_template;

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
      */
    public function __construct($templateName)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $ip = $frontController->getParam('bootstrap')->getOption('app');
        $templateOp = $frontController->getParam('bootstrap')->getOption('template');
        $cache = Zend_Registry::get('cache');
        //$cacheTemplateName = 'template_' . $templateOp['file'][$templateName];
        $cacheTemplateName = 'template_' . $templateName;
        //$cache->remove($cacheTemplateName);

        if (!$result = $cache->load($cacheTemplateName)) {
            $db = Zend_Registry::get('db');
            $db->query("SET TEXTSIZE 30000;");
            $this->_template = $db->fetchOne('SELECT CONTENIDO FROM IK_PLANTILLA WHERE "DESC" = ?' , array($templateName));
            $this->replace(array('[url]' => substr($ip['url'], 7),
                                 '[domain]'=> 'Kotear.pe',
                                 '[email_info]' => 'info@kotear.pe',
                                 '[link_ayuda]' => 'ayuda.kotear.pe/index.php/Ayuda',
                                 '[copyright]' => '&copy; Copyright 2010 ', //3Dev Business &amp; Consulting S.A.C.
                                 '[link_tos]' => 'http://ayuda.kotear.pe/index.php/Términos_y_Condiciones_de_Uso'));
            /*$filename = $templateOp['save_path']
                      . '/'
                      . $templateOp['file'][$templateName];
            $this->_template = file_get_contents($filename);*/
            $cache->save($this->_template, $cacheTemplateName);
        } else {
            $this->_template = $result;
        } //end if
        //return $this;
    } // end function

    /**
     * Remplaza en el template una lista de palabras y sus valores
     * @param array $data Lista de palabras a reemplazar y sus valores de reemplazo array('[mipalabra]' => 'mireemplazo')
     * @uses Clase::metodo()
     * @return type desc
     */
    public function replace(array $data)
    {
    	//print_r($data);

        if (isset($this->_template)) {
            foreach ($data as $index => $value) :
                $this->_template = str_replace($index, $value, $this->_template);
            endforeach; //end foreach
        }
        return $this->_template;
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getTemplate()
    {
        return $this->_template;
    } // end function
} // end class