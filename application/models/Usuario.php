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
require_once('Base/Usuario.php');
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
class Usuario
    extends Base_Usuario
{
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getNextId () 
    { 
        return $this->getAdapter()->fetchOne("SELECT COALESCE(MAX(ID_USR),0)+1 FROM ".$this->_name);  
    } //end function
    
    /**
     * ander
     * Se utiliza para retornar valores de paginacion y datos de un usuario
     * @return type array
     */
    public function getPaginacion(
        $k_NUM_PAGINA = 1, $k_NUM_REGISTROS='', $K_ID_USR='', $k_APODO='', $k_ID_TIPO_DOC='',
        $k_EMAIL='', $k_NUM_DOC='', $k_ID_TIPO_USUARIO=0, $k_ID_EST_USUARIO=0, $K_CLAVE='',
        $k_FECHA_INI='1900-01-01 00:00:00', $k_FECHA_FIN='2080-01-01 00:00:00'
        )
    {
        if (empty($K_ID_USR)) {
            $K_ID_USR = -1;
            $k_NUM_REGISTROS = empty($k_NUM_REGISTROS)?10:$k_NUM_REGISTROS;
            $k_FECHA_INI = empty($k_FECHA_INI)?'1900-01-01 00:00:00':$k_FECHA_INI;
            $k_FECHA_FIN = empty($k_FECHA_FIN)?'2080-01-01 00:00:00':$k_FECHA_FIN;
        } else {
            $k_NUM_PAGINA = -1;
            $k_NUM_REGISTROS = -1;
        }
        $result = $this->getAdapter()->fetchAll(
            'EXEC IN_SP_USUARIO_PORTAL_SEL ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            array($k_NUM_PAGINA, $k_NUM_REGISTROS, $K_ID_USR, $k_APODO, $k_ID_TIPO_DOC, $k_EMAIL, $k_NUM_DOC, 
                $k_ID_TIPO_USUARIO, $k_ID_EST_USUARIO, $K_CLAVE, $k_FECHA_INI, $k_FECHA_FIN
            )
        );
        return $result;
    }
    
    /**
     * ander
     * Se utiliza para retornar valores de paginacion y datos de un usuario
     * @return type array
     */
    public function guardar(
        $K_ID_USUARIO,          $K_NOM,         $K_APEL,            $K_APODO,           $K_EMAIL,       
        $K_ID_TIPO_DOCUMENTO,   $K_NUM_DOC,     $K_ID_TIPO_USUARIO, $K_ID_EST_USUARIO,	$K_CLAVE,
        $K_ID_UBIGEO,           $K_NUM_TELEF1,  $K_NUM_TELEF2
    )
    {
        return $this->getAdapter()->fetchAll(
            'EXEC IN_SP_USUARIO_PORTAL_UPD ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            array($K_ID_USUARIO, $K_NOM, $K_APEL, $K_APODO, $K_EMAIL, $K_ID_TIPO_DOCUMENTO, 
                  $K_NUM_DOC, $K_ID_TIPO_USUARIO, $K_ID_EST_USUARIO, $K_CLAVE, $K_ID_UBIGEO,
                  $K_NUM_TELEF1, $K_NUM_TELEF2
            )
        );        
    }
            
    
}