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
require_once 'UsuarioPortal.php';
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
class Usuario_SuspensionController extends Devnet_Controller_Action
{
    function indexAction ()
    {
        
    }
    /**
     * Permite realizar la solicitud de cancelacion de suspensión
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function cancelacionAction ()
    {
        $mUsuarioPortal = new UsuarioPortal();
        if ($this->_request->isXMLHttpRequest()) {
            $this->_request->getParam('usuariosuspendido');
            $validator = new Devnet_Validator();
            $textoValidator = new Zend_Validate();
            $textoValidator->addValidator(
                new Zend_Validate_NotEmpty()
            )->addvalidator(new Zend_Validate_Alpha(true));
            $validator->add('mensaje', $textoValidator);
            if (!$validator->isValid($this->_request->getParams())) {
                //$this->view->errors = $validator->getErrors();
                $this->json(array('error'=>1, 'msg'=>$validator->getErrors()));

            } else {
                $moderacion = $mUsuarioPortal->moderacionSuspension($this->_request->getParam('mensaje'));
                if ($moderacion[0]->ERROR==2) {
                     $this->json(
                         array(
                             'error'=>2,
                             'msg'=>'Modificar esta(s) palabra(s) por favor: '.$moderacion[0]->MSJ
                         )
                     );
                } else {
                    $retorno = $mUsuarioPortal->verificarApodoSuspencion(
                        $this->_request->getParam('usuariosuspendido')
                    );
                    if ($retorno[0]->K_ERROR == 0) { // 0 -> apodo correcto - si existe
                        $correoUsuario = $retorno[0]->K_MSG;
                        try {
                            $result = $mUsuarioPortal->registrarNotificacion(
                                $this->_request->getParam('usuariosuspendido'),
                                $this->_request->getParam('mensaje')
                            );
                            if ($result->K_ERROR == 0) {
                                $this->_helper->layout->setLayout('clear');
                                $template = new Devnet_TemplateLoad('confirm_cancelacion');
                                $template->replace(
                                    array(
                                        '[nombre]' => $this->_request->getParam('usuariosuspendido') ,
                                        '[mensaje]' => $this->_request->getParam('mensaje')
                                    )
                                );
                                //Nuevo envio de mail
                                $correo = Zend_Registry::get('mail');
                                $correo->addTo(
                                    $correoUsuario, $this->_request->getParam('usuariosuspendido')
                                )
                                    ->setSubject('Cancelacion de Suspension')
                                    ->setBodyHtml($template->getTemplate());
                                $correo->send();

                                //Obtenemos los datos de configuracion para el envio de correo
                                $frontController = Zend_Controller_Front::getInstance();
                                $configadmin = $frontController->getParam('bootstrap')
                                    ->getOption('configadmin');                                    

                                //Nuevo envio de mail al administrador
                                $correoAdmin = new Zend_Mail('utf-8');
                                $correoAdmin->addTo($configadmin['email'], $configadmin['administrator'])
                                            ->setSubject('Solicitud de Cancelacion de Suspension')
                                            ->setBodyHtml($template->getTemplate());
                                $correoAdmin->send();

                                $this->json(
                                    array(
                                        'code'=>0,
                                        'msg'=>'Su cancelación esta en proceso , se le ha enviado 
                                            un e-mail'
                                    )
                                );
                            } else {
                                $this->json(array('error'=>1, 'msg'=>'No se pudo enviar el e-mail'));
                            }

                        } catch (Exception $e) {
                            $this->log->err($e->getMessage());
                            $this->json(array('error'=>1, 'msg'=>$e->getMessage()));
                        }
                    } else {
                        // 1 apodo incorrecto - no existe
                        $this->json(array('error'=>1, 'msg'=>'Usuario Incorrecto'));
                    }
                } //else de moderacion
              /*  }*/
            }// ELSE DEL VALIDATOR
        } // ELSE DEL POST
    }// funcion
}// clase
