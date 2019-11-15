<?php
  /**
   * Ander
   * Descripción Corta
   * 
   * Descripción Larga
   * 
   * @copyright  Leer archivo COPYRIGHT
   * @license    Leer el archivo LICENSE
   * @version    1.0
   * @since      Archivo disponible desde su version 1.0
   */
class Devnet_DatosKotear{ 
	
    public function impresoFechaPublicacion()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $impreso = $frontController->getParam('bootstrap')->getOption('impresion');
        $diferencia = 0;
        
        $hoy=date('d-m-Y');
        $dia    = (int) date('N');
        $hora   = (int) date('H');
        $min    = (int) date('i');

        if (!isset ($impreso['diaCierre'])){
            $impreso['diaCierre']=4;
        }

        if (!isset ($impreso['horaCierre'])){
            $impreso['horaCierre']=12;
        }

        if (!isset ($impreso['cantidadDias'])){
            $impreso['cantidadDias']=14;
        }

        if ($dia > (int) $impreso['diaCierre']
            || ($dia == (int)$impreso['diaCierre']
                && ($hora > (int) $impreso['horaCierre']
                    || ($hora == (int) $impreso['horaCierre']
                        && $min >= (int) $impreso['minutoCierre'])))
        ) {
            $diferencia = (int) $impreso['cantidadDias'] - $dia;
        } else {
            $diferencia = (((int) $impreso['cantidadDias'])/2) - $dia;
        }
       
        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$hoy))
            list($dia,$mes,$año)=split("/", $hoy);

        if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$hoy))
              list($dia,$mes,$año)=split("-",$hoy);

        $nueva = mktime(0,0,0, $mes,$dia,$año) + abs($diferencia) * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y/N",$nueva);
        $u = new Devnet_Utils();
        return $u->convertDateProcesoPago($nuevafecha);
//        return date("d-m-Y",$nueva);
    }
    
    public function impresoFechaCierre()
    {
        //Jueves
        $u = new Devnet_Utils();
        $hora = "6:00 p.m.";
        if(date('N')==4 && date('G')<18){
            $idMes = date("m");
            $dia = date("d");
        } else {
            $idMes = date("m", strtotime("next Thursday"));
            $dia = date("d", strtotime("next Thursday"));
        }
        $mes = $u->_months[$idMes];
        $return = "jueves ".$dia." de $mes / $hora";
        return $return;
    }
	  
}

?>