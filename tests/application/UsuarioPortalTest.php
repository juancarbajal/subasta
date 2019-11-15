<?php
set_include_path(implode(PATH_SEPARATOR, 
        			     array('/home/jcarbajal/Sistemas/kotear/library',
        				 '/home/jcarbajal/Sistemas/kotear/application/models',
        				 get_include_path())));
require_once 'library/Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);
/*require_once 'application/models/UsuarioPortal.php';
require_once 'PHPUnit/Framework/TestCase.php';*/
/**
 * UsuarioPortal test case.
 */
class UsuarioPortalTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UsuarioPortal
     */
    private $UsuarioPortal;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated UsuarioPortalTest::setUp()
        $this->UsuarioPortal = new UsuarioPortal(/* parameters */);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated UsuarioPortalTest::tearDown()
        $this->UsuarioPortal = null;
        parent::tearDown();
    }
    /**
     * Constructs the test case.
     */
    public function __construct ()
    {    // TODO Auto-generated constructor
    }
    /**
     * Tests UsuarioPortal->insert()
     */
    public function testInsert ()
    {
        // TODO Auto-generated UsuarioPortalTest->testInsert()
        $this->markTestIncomplete("insert test not implemented");
        //$this->UsuarioPortal->insert(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->update()
     */
    public function testUpdate ()
    {
        // TODO Auto-generated UsuarioPortalTest->testUpdate()
        $this->markTestIncomplete("update test not implemented");
        //$this->UsuarioPortal->update(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->existeApodo()
     */
    public function testExisteApodo ()
    {
        // TODO Auto-generated UsuarioPortalTest->testExisteApodo()
        $this->markTestIncomplete("existeApodo test not implemented");
        $this->UsuarioPortal->existeApodo('jcarbajal');
    }
    /**
     * Tests UsuarioPortal->existeEmail()
     */
    public function testExisteEmail ()
    {
        // TODO Auto-generated UsuarioPortalTest->testExisteEmail()
        //$this->markTestIncomplete("existeEmail test not implemented");
        $this->UsuarioPortal->existeEmail('jcarbajal@3devnet.com');
    }
    /**
     * Tests UsuarioPortal->updEmail()
     */
    public function testUpdEmail ()
    {
        // TODO Auto-generated UsuarioPortalTest->testUpdEmail()
        $this->markTestIncomplete("updEmail test not implemented");
        //$this->UsuarioPortal->updEmail(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->find()
     */
    public function testFind ()
    {
        // TODO Auto-generated UsuarioPortalTest->testFind()
        $this->markTestIncomplete("find test not implemented");
        $this->UsuarioPortal->find(12);
    }
    /**
     * Tests UsuarioPortal->findByApodo()
     */
    public function testFindByApodo ()
    {
        // TODO Auto-generated UsuarioPortalTest->testFindByApodo()
        $this->markTestIncomplete("findByApodo test not implemented");
        $this->UsuarioPortal->findByApodo('jcarbajal');
    }
    /**
     * Tests UsuarioPortal->validarUsuario()
     */
    public function testValidarUsuario ()
    {
        // TODO Auto-generated UsuarioPortalTest->testValidarUsuario()
        $this->markTestIncomplete("validarUsuario test not implemented");
        $this->UsuarioPortal->validarUsuario('jcarbajal','122344');
    }
    /**
     * Tests UsuarioPortal->getPassword()
     */
    public function testGetPassword ()
    {
        // TODO Auto-generated UsuarioPortalTest->testGetPassword()
        $this->markTestIncomplete("getPassword test not implemented");
        //$this->UsuarioPortal->getPassword(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->setPassword()
     */
    public function testSetPassword ()
    {
        // TODO Auto-generated UsuarioPortalTest->testSetPassword()
        $this->markTestIncomplete("setPassword test not implemented");
        //$this->UsuarioPortal->setPassword(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->getTipoUsuario()
     */
    public function testGetTipoUsuario ()
    {
        // TODO Auto-generated UsuarioPortalTest->testGetTipoUsuario()
        $this->markTestIncomplete("getTipoUsuario test not implemented");
        //$this->UsuarioPortal->getTipoUsuario(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->getVendedorSemana()
     */
    public function testGetVendedorSemana ()
    {
        // TODO Auto-generated UsuarioPortalTest->testGetVendedorSemana()
        $this->markTestIncomplete("getVendedorSemana test not implemented");
        //$this->UsuarioPortal->getVendedorSemana(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->getUsuarioPublicaciones()
     */
    public function testGetUsuarioPublicaciones ()
    {
        // TODO Auto-generated UsuarioPortalTest->testGetUsuarioPublicaciones()
        $this->markTestIncomplete("getUsuarioPublicaciones test not implemented");
        //$this->UsuarioPortal->getUsuarioPublicaciones(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->cambioEstadoPorApodo()
     */
    public function testCambioEstadoPorApodo ()
    {
        // TODO Auto-generated UsuarioPortalTest->testCambioEstadoPorApodo()
        $this->markTestIncomplete("cambioEstadoPorApodo test not implemented");
        //$this->UsuarioPortal->cambioEstadoPorApodo(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->setCodConfPassword()
     */
    public function testSetCodConfPassword ()
    {
        // TODO Auto-generated UsuarioPortalTest->testSetCodConfPassword()
        $this->markTestIncomplete("setCodConfPassword test not implemented");
        //$this->UsuarioPortal->setCodConfPassword(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->getCodConfPassword()
     */
    public function testGetCodConfPassword ()
    {
        // TODO Auto-generated UsuarioPortalTest->testGetCodConfPassword()
        $this->markTestIncomplete("getCodConfPassword test not implemented");
        //$this->UsuarioPortal->getCodConfPassword(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->setCodConf()
     */
    public function testSetCodConf ()
    {
        // TODO Auto-generated UsuarioPortalTest->testSetCodConf()
        $this->markTestIncomplete("setCodConf test not implemented");
        //$this->UsuarioPortal->setCodConf(/* parameters */);
    }
    /**
     * Tests UsuarioPortal->getCodConf()
     */
    public function testGetCodConf ()
    {
        // TODO Auto-generated UsuarioPortalTest->testGetCodConf()
        $this->markTestIncomplete("getCodConf test not implemented");
        //$this->UsuarioPortal->getCodConf(/* parameters */);
    }
}

