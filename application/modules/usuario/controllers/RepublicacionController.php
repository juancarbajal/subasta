<?php 

require_once 'Republicacion.php';

class Usuario_RepublicacionController
    extends Devnet_Controller_Action
{
    /** 
     * Republicacion Masiva
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     **/
    function masivaAction ()
    {
        try{
            $avisos = implode(',', $_REQUEST['chkavisos']);
            if (empty($avisos )) throw new Exception('Error: seleccione avisos.');
            $republicacion = new Republicacion();
            $res = $republicacion->masiva($avisos, $this->identity->ID_USR);
            //var_dump($res);
            $this->_redirect(
                $this->view->baseUrl().'/usuario/venta/inactivas/error/'.$res->K_ERROR.'/msg/'.$res->K_MSG
            );
        } catch(Exception $e){
            $this->_redirect($this->view->baseUrl().'/usuario/venta/inactivas/error/1/msg/'.$e->getMessage());
        }
    }


}