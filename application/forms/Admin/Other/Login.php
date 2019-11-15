<?php
/**
 * @author ander
 *
 */
class Application_Form_Admin_Login extends Devnet_Form
{
    private $_form;
    
    public function init() {
        parent::init();
        $this->_form = $this;
        $this->setAttrib("horizontal", true); 
    }

    public function getDesactivarUsuario()
    {
        $this->setMethod('POST');
        
        $idAViso = $this->createElement('text', 'idUsuario');
        $idAViso->setLabel('Id Usuario :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50))
                ->addValidator('Int')
                ->setRequired(true);
        
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Consultar');

        $this->addElements(array($idAViso, $submit));
        
        return $this->_form;
    }
    
    public function getLogin()
    {
        $this->setMethod('POST')
                ->setAttrib('id', 'loginAdmin');
        
        $user = $this->createElement('text', 'user');
        $user->setLabel('Usuario :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50))
                ->setRequired(true);      
        
        $pass = $this->createElement('password', 'pass');
        $pass->setLabel('Clave :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50))
                ->setValue("")
                ->setRequired(true);      
        
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Enviar');
        
        $token = new Zend_Form_Element_Hash('token');
        $token->setSalt(md5(uniqid(rand(), TRUE)))
            ->setTimeout(300);

        $this->addElements(array($user, $pass, $token, $submit));
        
        return $this->_form;
    }    
    
    public function getBusqueda()
    {
        require_once 'TipoDocumento.php';
        
        $tipoDocumento = new TipoDocumento();
        
        $this->setMethod('GET')
                ->setAttrib('id', 'busqueda-usuario');
        
        $user = $this->createElement('text', 'apod');
        $user->setLabel('Nombre Comercial :')
                ->setAttrib('size', 30)
                ->addValidator('StringLength', false, array(1, 100));
        
        $email = $this->createElement('text', 'mail');
        $email->setLabel('Email :')
                ->setAttrib('size', 30)
                ->addValidator('StringLength', false, array(1, 100));
        
        
        $numDoc = $this->createElement('text', 'numDoc');
        $numDoc->setLabel('Numero de Documento:')
                ->setAttrib('size', 30)
                ->addValidator('StringLength', false, array(1, 100));        
        
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Consultar');

        $this->addElements(array($user, $email, $tipoDoc, $numDoc, $submit));
        
        return $this->_form;
    }
    
    public function getAdmin($tipo = null)
    {
        $this->setMethod('POST');
        $this->setAttrib("vertical", true); 
        
        require_once 'Rol.php';
        $rol = new Rol();
        
        $username = $this->_form->createElement('text', 'USERNAME');
        $username->setLabel('Usuario: ')
                ->setRequired(true);
        
        $clave = $this->_form->createElement('password', 'PASSWORD');
        $clave->setLabel('Clave: ')
                ->setRequired(true);
        
        $check = $this->_form->createElement('checkbox', 'EST');
        $check->setLabel('Estado: ');
        
        $roles = $this->_form->createElement('select', 'ROL_ID');
        $roles->setLabel('Rol: ');
        $roles->addMultiOptions($rol->getListForCombo('nombre'));
        
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Enviar');
        
        $token = new Zend_Form_Element_Hash('token');
        $token->setSalt(md5(uniqid(rand(), TRUE)))
                ->setTimeout(300);        

        $this->addElements(array($username, $clave, $check, $roles, $token, $submit));
        
        return $this->_form;        
        
    }
}