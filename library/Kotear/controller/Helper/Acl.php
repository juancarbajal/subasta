<?php
/**
 * @author jcarbajal
 *
 */
class Kotear_Controller_Helper_Acl
{
    /**
     * @var unknown_type
     */
    public $acl;
    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->acl = new Zend_Acl();
    }
    /**
     * Asignación de Roles
     * @return void
     */
    public function setRoles ()
    {
        $this->acl->addRole(new Zend_Acl_Role('visitante'));
        $this->acl->addRole(new Zend_Acl_Role('usuario'), 'visitante');
    }
    /**
     * Asignación de Recursos
     * @return void
     */
    public function setResources ()
    {
        /*$this->acl->add(new Zend_Acl_Resource(view));
        $this->acl->add(new Zend_Acl_Resource(edit));
        $this->acl->add(new Zend_Acl_Resource(delete));*/
    }
    /**
     * Asignación de privilegios 
     */
    public function setPrivilages ()
    {
        $this->acl->allow(visitante, null, view);
        $this->acl->allow(editor, array(view , edit));
        $this->acl->allow(admin);
    }
    /**
     * Asignación de ACL en el registro de sistema 
     */
    public function setAcl ()
    {
        Zend_Registry::set('acl', $this->acl);
    }
}