<?php
require_once 'Moneda.php';
require_once 'Aviso.php';
require_once 'AvisoInfo.php';
require_once 'UsuarioPortal.php';

/**
 * Description of AvisoImpreso
 *
 * @author comercio
 */
class AvisoImpreso
{
    private $_data = array();
//    private $_monedas = array();
//    private $_len_tit = 0;
//    private $_can_pal = 0;
    
//array(7) {
//  ["module"]=>
//  string(7) "usuario"
//  ["controller"]=>
//  string(11) "publicacion"
//  ["action"]=>
//  string(11) "texto-aviso"
//  ["titulo"]=>
//  string(8) "avisodas"
//  ["precio"]=>
//  string(0) ""
//  ["moneda"]=>
//  string(1) "1"
//  ["tex_imp"]=>
//  string(0) ""
//}
    
    public function __construct(array $datos)
    {
        $filtro = new Zend_Filter_StripTags();
        
        unset($datos['controller']);
        unset($datos['module']);
        unset($datos['action']);
        foreach ($datos as $key => $value) {
            $this->_data[$key] = $filtro->filter(trim($value));
        }
        if (strlen($this->_data['titulo']) > 14) {
            $this->_data['titulo'] = substr($this->_data['titulo'], 0, 14);
        }
        $this->_validarMoneda();
        //$this->_validarTelefono();
    }
    
    private function _validarMoneda()
    {
        $moneda = new Moneda();
        $monedas = $moneda->getList();
        foreach ($monedas as $mon) {
            if ($this->_data['moneda'] == $mon->ID_MONEDA) {
                $this->_data['sim_mon'] = $mon->SIMB;
            }
        }
        $this->_data['fono1'] = $this->identity->FONO1;
    }   

    public function getImpreso()
    {
        return $this->_data;
    }
    
    private function _validarTamaño($bar, $len)
    {
        
    }
    
    
}