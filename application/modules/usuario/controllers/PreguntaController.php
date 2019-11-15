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
require_once 'Mensaje.php';
require_once 'MensajeDetalle.php';
require_once 'UsuarioPortal.php';
require_once 'AvisoInfo.php';
require_once 'Calificacion.php';
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
class Usuario_PreguntaController 
    extends Devnet_Controller_Action
{
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function compradorAction ()
    {   
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        if ($this->_request->isXMLHttpRequest()) {
            $mMensaje = new Mensaje();
            $mUsuarioPortal = new UsuarioPortal();
            $calificacion = new Calificacion();

            $anonimo = $mMensaje->validarPropioAviso($this->_request->getParam('idAviso'));
            $duenoAviso = $anonimo[0]->ID_USR;

            if ($duenoAviso == $this->identity->ID_USR) {
                $this->json(array('code'=>3, 'msg'=>'Ud. no puede preguntar sobre su propio aviso'));
            } else {
                $mensaje = strip_tags($this->_request->getParam('mensage'));
                $moderacion = $mUsuarioPortal->moderacionSuspension($mensaje);

                if ((int)$moderacion[0]->ERROR == 1) $flag = 1;

                if ((int)$moderacion[0]->ERROR == 2) {
                   $this->json(
                       array(
                           'code'=>2,
                           'msg'=>'Modificar esta(s) palabra(s) por favor: '.
                           substr(rtrim(str_replace('|', ', ', $moderacion[0]->MSJ)), 2) 
                       )
                   );
                } else {
                    if ((int)$moderacion[0]->ERROR == 0) $flag = 0;                        
                    $res = $mMensaje->insertPreguntaAviso(
                        $this->_request->getParam('idAviso'),
                        $mensaje,
                        $this->identity->ID_USR,
                        $flag
                    );
                    if ($res->K_ERROR == 0) {
//                            $data = $m->getPreguntasRespuestasAviso($this->_request->getParam('idAviso'));
                        $mAvisoInfo = new AvisoInfo();
                        $datosAviso = $mAvisoInfo->obtenerDatos($this->_request->getParam('idAviso'));
                        $emailDueno = $mUsuarioPortal->find($datosAviso[0]->ID_USR);
                        $datosUsuario = $mUsuarioPortal->find($this->identity->ID_USR);

                        $frontController = Zend_Controller_Front::getInstance();
                        $app = $frontController->getParam('bootstrap')->getOption('app');
                        $enlace = $app['url'] . '/usuario/venta/preguntas-recibidas/opc/categoria/codigo/0';

                        $puntaje = $calificacion->obtenerPuntaje($this->identity->ID_USR);

                        $template = new Devnet_TemplateLoad('enviopregunta');
                        $template->replace(
                            array(
                                '[NOMBRE]' => $emailDueno->APODO,
                                '[COMPRADOR]' => $datosUsuario->APODO,
                                '[ENLACE]' => $enlace,
                                '[AVISO]' => $datosAviso[0]->TIT,
                                '[IDAVISO]' => $this->_request->getParam('idAviso'),
                                '[IDUSUARIO]' => $this->identity->ID_USR,
                                '[PUNTAJE]' => $puntaje,
                            )
                        );

                        if (empty($this->getConfig()->correo->disable)) {
                            $correo = Zend_Registry::get('mail');
                            $correo->addTo($emailDueno->EMAIL, $emailDueno->NOM)
                                ->setSubject('Hicieron una pregunta por uno de tus Avisos')
                                ->setBodyHtml($template->getTemplate());
                            $correo->send();
                        }

                        $this->json(
                            array(
                                'code'=>0,
                                'msg'=>'Pregunta Registrada',
                                'user'=>$this->identity->APODO,
                                'hora'=>date("d/m/Y H:i")
                            )
                        );
                            ////'data'=>$data));
                    } else {
                        $this->json(
                            array(
                                'code'=>$res->K_ERROR,
                                'msg'=>$res->K_MSG_ERROR
                            )
                        );
                    }
                }
            }
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function avisoAction ()
    {
        $this->view->volver=$this->session->avisoUrl;
        if ($this->_request->isGet()) {
            $data=$this->db->fetchAll(
                "SELECT ID_AVISO,TIT FROM KO_AVISO WHERE ID_AVISO=?", array($this->_request->getParam('id'))
            );
            $this->view->aviso=$data[0];
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function avisoterminoAction  ()
    {
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            if (isset($params['coment'])) {
                $this->db->beginTransaction();
                try{
                    $mMensaje = new Mensaje();
                    $idMensaje = $mMensaje->getNextId();
                    $mMensaje->insert(
                        array(
                            'ID_MENSAJE'=>$idMensaje,
                            'ID_REGISTRO' => $params['id_aviso'],
                            'FEC_CREA' => $this->utils->now(),
                            'ID_TIPO_MENSAJE' => 1,
                            'ID_USR' => $this->identity->ID_USR
                         )
                    );
                    $mMensajeDetalle = new MensajeDetalle();
                    $mMensajeDetalle->insert(
                        array(
                            'ID_DETALLE_MENSAJE'=>$mMensajeDetalle->getNextId(),
                            'ID_MENSAJE'=>$idMensaje,
                            'ID_TIPO_MENSAJE'=> 1,
                            'ID_USR' =>$this->identity->ID_USR,
                            'COMENT'=> $params['coment']
                        )
                    );
                    $this->db->commit();
                } catch (Exception $e) {
                    echo $e->getMessage(); die();
                    $this->db->rollBack();
                }
            }
            else echo 'no entro';
            $this->view->volver=$this->session->avisoUrl;
        }
    }
}