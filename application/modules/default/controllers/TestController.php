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
class TestController 
    extends Devnet_Controller_Action
{
    
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    
    public function conexionAction()
    {
        try{
            $this->view->data=$this->_consulta();
            $this->view->msg='Conexi&oacute;n Satisfactoria';
        } catch(Exception $e) {
                $this->view->msg='Error en Conexi&oacute;n - '.$e->getMessage();
        }
    }

    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function ajaxAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
                $this->json(array('code'=>0,'msg'=>'xxxx','data'=>array(1,2,3,4,5,'comida')));
        }
    }

    public function ajax2Action()
    {
        if ($this->_request->isXMLHttpRequest()) {
                $this->json(array('code'=>0,'msg'=>'xxxx','data'=>array(1,2,3,4,5,'comida')));
        }

    }
    
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function conexionpdoAction () 
    {
        try{
            $conn = new PDO('dblib:dbname=db_kotear;host=server2008', 'kotear', '12345678');
            $this->view->msg= "Conexión realizada";
            
            //$this->view->data=$conn->query("SELECT * FROM sysobjects");
            
        } catch (Zend_Db_Statement_Exception $e){
            $this->view->msg= "Error en Conexión".$e->getMessage();            
        }
        

    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function cacheAction() 
    {
        //modificar /etc/php5/apache2/conf.d/xcache.ini
        //xcache.size = 2048M
        //xcache.var_size  = 128M
        //$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        $fntTime= new Devnet_Time();
        $fntTimeTwo= new Devnet_Time();
        //Prueba sin Cache
        $fntTimeTwo->startTime();
        $data=$this->_consulta();
        $this->view->tiempoSinCache=$fntTimeTwo->endTime();
        $fntTime->startTime();
        if (!$result=$this->cache->load('consulta')) {
            $this->cache->save($data, 'consulta');
        } else {
            $data= $result;
        }
        $this->view->tiempoConCache=$fntTime->endTime();
        $this->view->data=$data;
    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function sessionAction() 
    {
        //Sesiones
        //$sql="CREATE TABLE SESSION(ID CHAR(32), MODIFIED INTEGER, LIFETIME INTEGER, 
        //  DATA TEXT, PRIMARY KEY(ID))";
        //Zend_Session::destroy();
        if (!isset($this->session->consultaX)) {
                $this->view->tipo='de consulta';
                $this->session->consultaX=$this->_consulta();
        } else $this->view->tipo='de sesion';
        $this->view->data=$this->session->consultaX;
        if ($this->_request->isXMLHttpRequest()) {
                Zend_Session::destroy();
                $this->json(array("code"=>0,"msg"=>"Sesión Cerrada"));
        }
    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function restAction() 
    {

    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function infoAction() 
    {

    }
    
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function tableAction() 
    {
        $fctTime= new Devnet_Time();
        $fctTimeTwo= new Devnet_Time();
        
        $fctTime->startTime();
        $aviso=new Aviso();
        $this->view->data=$aviso->primeros10();
        $this->view->conClases=$fctTime->endTime();
        
        $fctTimeTwo->startTime();
        $this->view->data=$this->_consulta();
        $this->view->sinClases=$fctTimeTwo->endTime();
    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function formAction() 
    {	    
        $config = new Zend_Config_Ini($this->getFormsPath().'/acceso.ini', 'form');
        $form = new Devnet_Form($config->registrar, 'Test');
        //$this->view->t=$form->getTranslator();
        if ($this->_request->isPost()) {    
            if ($form->isValid($this->_request->getParams())) {
                $this->view->msg = $this->_request->getParam('usuario') . ' '
                    . $this->_request->getParam('password');

            }
            $this->view->t=$form->getMessages();
        }
        $this->view->loginForm = $form;  
    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function validarUsuario($usuario,$clave) 
    {
        $auth= Zend_Auth::getInstance();        
        global $userConfig;
        $authAdapter=new Zend_Auth_Adapter_DbTable(
            $this->db, $userConfig->table, $userConfig->user, $userConfig->pass
        );
        $authAdapter->setIdentity($usuario);
        $authAdapter->setCredential(md5($usuario.$clave));    
        if ($auth->authenticate($authAdapter)->isValid()) {
          $auth->getStorage()->write($authAdapter->getResultRowObject(null, $userConfig->pass));
              $role=strtolower($auth->getIdentity()->ROL);
            return array(
                'code'=> 0,
                'msg' => 'Bienvenido',
                'url' => $this->view->url(array('module'=>'default', 'controller'=>'index', 'action'=>'menu'))
            );
        } else
            return array('code'=>1, 'msg'=>'Error en Usuario y Clave');
    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function _consulta() 
    {
    //return $this->db->fetchAll("SELECT name FROM sysobjects");
        return $this->db->fetchAll("SELECT * FROM KO_CATEGORIA");
    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function correoAction() 
    {
        $correo=Zend_Registry::get('mail');
        $correo->addTo('juancarbajal@gmail.com', 'Juan')
            ->setSubject('Hola mundo')
            ->setBodyHtml('Prueba de envio por resource');
        $correo->send();
        /*$op=$this->getConfigIni();
        $correo= new Devnet_Mail($op->mail->toArray());
        $correo->setFrom('devnet2010@gmail.com','Prueba De')
            ->addTo('juancarbajal@gmail.com','Prueba Para')
            ->setSubject('Hola Mundo')
            ->setBodyHtml('Texto de Envio');
        $correo->send();
        */    
    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function consultaAction () 
    {   
        $this->view->tablas=$this->db->fetchAll("SELECT name FROM sysobjects where type='U' order by name");
        $this->view->sql=$this->session->sql;
        if ($this->_request->isPost()) {
            $qry=$this->db->query($this->_request->getParam('sql'));
            $qry->setFetchMode(Zend_Db::FETCH_ASSOC);
            $this->view->data=$qry->fetchAll();    
            $this->session->sql=$this->_request->getParam('sql');
        } 
    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function generarAction () 
    { 
        $time = explode('.', microtime(true));
        $this->view->generado = $time[0];     
    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function caracterAction () 
    { 
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isPost()) {
            $this->view->caracter1=$this->utils->encode($this->_request->getParam('texto'));
            $this->view->caracter2=$this->utils->decode($this->view->caracter1);
        } 
        
    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function unicodeAction () 
    { 
        $this->_helper->layout->setLayout('clear');

        $this->view->data=$this->db->fetchAll("SELECT * FROM PR_PRUEBA");
        $this->view->dataUbigeo=$this->db->fetchAll("SELECT * FROM KO_UBIGEO");
        //            $this->db->exec("INSERT INTO KO_UBIGEO(ID_UBIGEO,NOM) VALUES(100,'ñOÑÁ')");
    } //end function
    
    function zenfilterAction()
    {
        $filterTwo = new Devnet_Filter_Alnum();
        $valor = $filterTwo->filter($this->_request->getParam("valor"), '_');
        echo $valor."<p>";
    }
}