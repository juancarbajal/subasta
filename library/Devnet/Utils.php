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
class Devnet_Utils
{
    /**
     * @var type
     */
    protected $_baseCoding;
    protected $_endCoding;
    protected $_filterSEO;
	public $_days = array(1 => 'Lunes',
							2 =>  'Martes',
							3 =>  'Miércoles',
							4 =>  'Jueves',
							5 =>  'Viernes',
							6 =>  'Sábado',
							7 =>  'Domingo');
    public $_months = array(1=>'Enero',
                               2=>'Febrero',
                               3=>'Marzo',
                               4=>'Abril',
                               5=>'Mayo',
                               6=>'Junio',
                               7=>'Julio',
                               8=>'Agosto',
                               9=>'Septiembre',
                               10=>'Octubre',
                               11=>'Noviembre',
                               12=>'Diciembre');
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function setBaseCoding($coding){
    	$this->_baseCoding=$coding;
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function setEndCoding($coding){
    	$this->_endCoding=$coding;
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function encode($string, $baseCoding = null, $endCoding = null)
    {
    	if ($baseCoding!=null){
    		$this->setBaseCoding($baseCoding);

    	}
    	if ($endCoding!=null){
    		$this->setEndCoding($endCoding);
    	}
    	return iconv($this->_baseCoding , $this->_endCoding, $string);
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function decode ($string)
    {
    	return iconv($this->_endCoding,$this->_baseCoding,$string);
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function now()
    {
        return $this->getTimestamp();
    }

    /**
     * Concatena la ruta de la imagen adecuada
     * @param pe name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getRutaImagen($tipoImagen='thumbnails') {
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')
                ->getOption('fileshare');
        return $urlImagen = $fileshare['url'] .'/'. $fileshare[$tipoImagen] . '/';
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getDate()
    {
    	return date('Y-m-d');
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getTimestamp()
    {
    	return date('Y-m-d H:i:s');
    } //end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function generateCode()
    {
        $time = explode('.', microtime(true));
        return $time[0];
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function generatePassword($length = 10)
    {
        $password = "";
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";
        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    } // end function

    /*
     * Convierte una cadena a su expresión SEO relacionada
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function convertSEO($url) {
    	if (!isset($this->_filterSEO)){
    		$this->_filterSEO = new Devnet_Filter_Alnum();
    	}
    	return $this->_filterSEO->filter(trim($url),'-');
    }

    /**
     * Repeticiones, permite evaluar y quitar letras repetidas en palabras de busqueda
     * @param type name desc
     * @uses Clase::metodo()
     * @return varchar Cadena sin repeticiones de caracteres
     */
    public function repeticionesPalabras($cadena) {
        $palabraCorregida='';
        $cadena = strtolower($cadena);
        for ($i=0; $i<=strlen($cadena); $i++):
            if ($i>0) {
                if ($cadena[$i]<>' ') { //Verificamos que no sea el primer caracter
                    if ($cadena[$i] == $cadena[$i-1]) { // Letras repetidas
                        if (($cadena[$i] == 'r' || $cadena[$i] == 'l') && ($cadena[$i] <> $cadena[$i-2])) { // Excepcion:
                            $palabraCorregida.= $cadena[$i];
                        }
                    }
                    else {
                        $palabraCorregida.= $cadena[$i];
                    }
                }
                else {
                    $palabraCorregida.= $cadena[$i];
                }
            }
            else {
                $palabraCorregida.= $cadena[$i];
        }
        endfor;
        return $palabraCorregida;

    } // end function
    function convertDateHistorial($date) {
    	$date=explode(' ', $date);
    	$fec = explode('/', $date[0]);
    	return sprintf('%s de %s %s a las %s hrs',$fec[0],$this->_months[(int)$fec[1]],$fec[2], substr($date[1],0,5));
    }
	
	function convertDateProcesoPago ($date = null)
	{
		if(!isset($date)){
			$date = date('d/m/Y/N');
            //$date = date('d/m/Y/e');
		}
        $fec = explode('/', $date);
        return sprintf('%s %s de %s del  %s ', $this->_days[(int)$fec[3]], $fec[0], $this->_months[(int)$fec[1]], $fec[2]);
	}
    //end function convertDateProcesoPago
    
    //ander
    //suma fechas
    function sumaDiasAFechas($ndias, $fecha = null )
    {
        $fecha = empty($fecha)?$this->getDate():$fecha;
        if (preg_match("/([0-9][0-9]){1,2}\/[0-9]{1,2}\/[0-9]{1,2}/",$fecha))list($año,$mes,$dia)=split("/", $fecha);
        if (preg_match("/([0-9][0-9]){1,2}-[0-9]{1,2}-[0-9]{1,2}/",$fecha))list($año,$mes,$dia)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d-m-Y",$nueva);
        return ($nuevafecha);  
    }
    
    //ander
    //convertir a fecha formato deseada
    function convertFecha($formatNew = 'm-d', $fecha = null)
    {
        try {
            $fecha = empty($fecha)?$this->getDate():$fecha;
            $caracter = '/';
            if (strrpos($fecha, '-')){
                $caracter = '-';
            } 
            if ( preg_match("/^\d{4}\-\d{1,2}\-\d{1,2}$/", $fecha) ||
                preg_match("/^\d{4}\/\d{1,2}\/\d{1,2}$/", $fecha)){
                
                $format='Y'.$caracter.'m'.$caracter.'d';
            } elseif (preg_match("/^\d{1,2}\/\d{1,2}\/\d{4}$/",$fecha) ||
                      preg_match("/^\d{1,2}\-\d{1,2}\-\d{4}$/",$fecha)) {
                $format = 'd'.$caracter.'m'.$caracter.'Y';
            }
            $return = DateTime::createFromFormat($format, $fecha)->format($formatNew);
        } catch (Exception $exc) {
            $return = false;
        }
        return $return;
    }
    
	
} //end class