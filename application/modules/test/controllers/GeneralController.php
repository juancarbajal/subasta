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
class Test_GeneralController extends Devnet_Controller_Action
{
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function conexionAction ()
    {
        try {
            $this->view->data = $this->_consulta();
            $this->view->msg = 'Conexi&oacute;n Satisfactoria';
        } catch (Exception $e) {
            $this->view->msg = 'Error en Conexi&oacute;n - ' . $e->getMessage();
        }
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function ajaxAction ()
    {
        if ($this->_request->isXMLHttpRequest()) {
            $this->json(array('code' => 0 , 'msg' => 'xxxx' , 'data' => array(1 , 2 , 3 , 4 , 5 , 'comida')));
        }
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function autocompleteAction() {
        if ($this->_request->isXMLHttpRequest()) {
            //Recibe el parametro y realiza la busqueda
            $parametro = $this->_request->getParams('q');
            //var_dump($parametro['q']);


            //Envia los resultados a mostrar
            $resultado = new DiccionarioTag();
            $r = $resultado->getAutocomplete($parametro['q']);
            //var_dump($r);
            /*$this->json(array('code'=>0,
                'msg'=>'Sugerencias',
                'data'=>$r)
            );*/
            $this->json(array('code'=>0,
                    'msg'=>'Sugerencias',
                    'data'=>$r));
            $this->view->resultado = $this->json(array('data'=>$r));
            var_dump($this->json(array('data'=>$r)));
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function conexionpdoAction () {
        try {
            $conn = new PDO('dblib:dbname=db_kotear;host=server2008', 'kotear', '12345678');
            $this->view->msg = "Conexión realizada";
            //$this->view->data=$conn->query("SELECT * FROM sysobjects");
        } catch (Zend_Db_Statement_Exception $e) {
            $this->view->msg = "Error en Conexión" . $e->getMessage();
        }

    } //end function
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function cacheAction ()
    {
        //modificar /etc/php5/apache2/conf.d/xcache.ini
        //xcache.size = 2048M
        //xcache.var_size  = 128M
        //$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        //$cc = new Devnet_Time();
        $sc = new Devnet_Time();
        $sc->startTime();
        $data = $this->_consulta();
        echo 'Tiempo sin Cache '. $sc->endTime().'<br/>';
//         $this->view->tiempoSinCache = $sc->endTime();
//         $cc->startTime();
//         if (! $result = $this->cache->load('consulta')) {
//             $this->cache->save($data, 'consulta');
//         } else {
//             $data = $result;
//         }
//         $this->view->tiempoConCache = $cc->endTime();
//         $this->view->data = $data;

        $front = Zend_Controller_Front::getInstance();

        //$front->bootstrap('cachemanager');
        $dbCache = $front->getParam('bootstrap')->getResource('cachemanager')->getCache('search');
        $fileCache = $front->getParam('bootstrap')->getResource('cachemanager')->getCache('file');
        $apcCache = $front->getParam('bootstrap')->getResource('cachemanager')->getCache('database');
        /*
        $dbCache->clean(Zend_Cache::CLEANING_MODE_ALL);
        $fileCache->clean(Zend_Cache::CLEANING_MODE_ALL);
        $apcCache->clean(Zend_Cache::CLEANING_MODE_ALL);
        */
        $cdb = new Devnet_Time();
        $cdb->startTime();
        if (! $result1 = $dbCache->load('consulta')) {
            $dbCache->save($data, 'consulta');
            echo 'Se creo Cache de Base <br/>';
        } else {
            echo 'Usamos Cache de Base <br/>';
        }
        echo 'Tiempo de cache de base ' . $cdb->endTime() .'<br/>';

        $cfile = new Devnet_Time();
        $cfile->startTime();
        if (! $result2 = $fileCache->load('consulta')) {
            $fileCache->save($data, 'consulta');
            echo 'Se creo Cache de Archivo <br/>';
        } else {
            echo 'Usamos Cache de Archivo <br/>';
        }
        echo 'Tiempo de Cache de Archivo ' . $cfile->endTime() .'<br/>';

        $cacp = new Devnet_Time();
        $cacp->startTime();
        if (! $result3 = $apcCache->load('consulta')) {
            $apcCache->save($data, 'consulta');
            echo 'Se creo Cache APC <br/>';
        } else {
            echo 'Usamos Cache APC <br/>';
        }
        echo 'Tiempo de Cache APC ' . $cacp->endTime() .'<br/>';

//      $this->view->tiempoConCache = $cc->endTime();
        /*
        if (! $result = $fileCache->load('consulta')) {
            $searchCache->save($data, 'consulta');
            echo 'Se creo Cache de Busqueda <br/>';
        }
        */
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function sessionAction ()
    {
        //Sesiones
        //$sql="CREATE TABLE SESSION(ID CHAR(32), MODIFIED INTEGER, LIFETIME INTEGER, DATA TEXT, PRIMARY KEY(ID))";
        //Zend_Session::destroy();
        if (! isset($this->session->consultaX)) {
            $this->view->tipo = 'de consulta';
            $this->session->consultaX = $this->_consulta();
        } else
            $this->view->tipo = 'de sesion';
        $this->view->data = $this->session->consultaX;
        if ($this->_request->isXMLHttpRequest()) {
            Zend_Session::destroy();
            $this->json(array("code" => 0 , "msg" => "Sesión Cerrada"));
        }
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function restAction () {

    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function infoAction () {

    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function tableAction ()
    {
        $cc = new Devnet_Time();
        $sc = new Devnet_Time();
        $cc->startTime();
        $aviso = new Aviso();
        $this->view->data = $aviso->primeros10();
        $this->view->conClases = $cc->endTime();
        $sc->startTime();
        $this->view->data = $this->_consulta();
        $this->view->sinClases = $sc->endTime();
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function formAction ()
    {
        $config = new Zend_Config_Ini($this->getFormsPath() . '/acceso.ini', 'form');
        $form = new Devnet_Form($config->registrar, 'Test');
        //$this->view->t=$form->getTranslator();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $this->view->msg = $this->_request->getParam('usuario') . ' ' . $this->_request->getParam('password');
            }
            $this->view->t = $form->getMessages();
        }
        $this->view->loginForm = $form;
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function validarUsuario ($usuario, $clave)
    {
        $auth = Zend_Auth::getInstance();
        global $userConfig;
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db, $userConfig->table, $userConfig->user, $userConfig->pass);
        $authAdapter->setIdentity($usuario);
        $authAdapter->setCredential(md5($usuario . $clave));
        if ($auth->authenticate($authAdapter)->isValid()) {
            $auth->getStorage()->write($authAdapter->getResultRowObject(null, $userConfig->pass));
            $role = strtolower($auth->getIdentity()->ROL);
            return array('code' => 0 , 'msg' => 'Bienvenido' , 'url' => $this->view->url(array('module' => 'default' , 'controller' => 'index' , 'action' => 'menu')));
        } else
            return array('code' => 1 , 'msg' => 'Error en Usuario y Clave');
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function _consulta ()
    {
        //return $this->db->fetchAll("SELECT name FROM sysobjects");
        return $this->db->fetchAll("SELECT * FROM dbo.KO_AVISO");
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function correocodificacionAction () {
        //$config = new Zend_Config_Ini($this->getFormsPath() . '/acceso.ini', 'form');
        //$form = new Devnet_Form($config->registrar, 'Test');
        //$this->view->t=$form->getTranslator();
        if ($this->_request->isPost()) {
            $parametros = $this->_request->getParams();
            //$correo = Zend_Registry::get('mail');
            require_once 'Zend/Mail.php';
            $correo = new Zend_Mail('UTF-8');
            $correo->addTo($parametros['email'], 'Pruebas')->setSubject('Prueba de codificacion')->setBodyHtml($parametros['body']);
            $correo->send();
            //$this->view->t = $form->getMessages();
        }
        //$this->view->loginForm = $form;


    } //end function
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function correoAction ()
    {
        $correo = Zend_Registry::get('mail');
        print_r($correo);
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
        $this->view->tablas = $this->db->fetchAll("SELECT name FROM sysobjects where type='U' order by name");
        $this->view->sql = $this->session->sql;
        if ($this->_request->isPost()) {
        	try{
            $qry = $this->db->query($this->_request->getParam('sql'));
            $qry->setFetchMode(Zend_Db::FETCH_ASSOC);
            $this->view->data = $qry->fetchAll();
            $this->session->sql = $this->_request->getParam('sql');
        	} catch (Exception $e){
                echo $e->getMessage();
        	}
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
            $this->view->caracter1 = $this->utils->encode($this->_request->getParam('texto'));
            $this->view->caracter2 = $this->utils->decode($this->view->caracter1);
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
        $this->view->data = $this->db->fetchAll("SELECT * FROM PR_PRUEBA");
        $this->view->dataUbigeo = $this->db->fetchAll("SELECT * FROM KO_UBIGEO");
        //            $this->db->exec("INSERT INTO KO_UBIGEO(ID_UBIGEO,NOM) VALUES(100,'ñOÑÁ')");
    } //end function
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function execprocedureAction ()
    {
        $this->view->data = $this->db->fetchAll("execute KO_SP_USUARIO_INS 'kiko2', 'kiko2', 'jorge', 'villaran', 'canosoxxdd@gmail.com', 1, '052-952713764', '', '', 'Tacna', 1, '12345'");
    }
    function denunciarAction ()
    {
        require_once 'Calificacion.php';
        $reclamoCalificacion = new Calificacion();
        $parametros['idUsr'] = $this->identity->ID_USR;
        $parametros['apodo'] = $this->identity->APODO;
        $parametros['idTipoNotificacion'] = '4';
        $data['idUsr'] = 5;
        $data['mensaje'] = 'en duro';
        $data['apodo'] = 'koteo';
                $data['idMotivo'] = 31;
                $data['idTipoNotificacion'] = 4;
                $data['idTransaccion'] = 83;
        $retorno = $reclamoCalificacion->registrarNotificacion($data);
        $this->view->mensaje = $retorno;
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function validarformAction ()
    {
        if ($this->_request->isGet()) {
            $errors = array();
            /*
            $usuarioValidator=new Zend_Validate_StringLength(6,12);
                        if (!$usuarioValidator->isValid($this->_request->getParam('usuario')) ){
                $errors['usuario']=$usuarioValidator->getMessages();
                }
                else echo 'ok, no problem';*/
            $usuarioValidator = new Zend_Validate();
            $usuarioValidator->addValidator(new Zend_Validate_StringLength(6, 12));
            //                ->addvalidator(new Zend_Validate_Alnum() ;
            if (! $usuarioValidator->isValid($this->_request->getParams('usuario'))) {
                $errors['usuario'] = $usuarioValidator->getMessages();
            }
            $claveValidator = new Zend_Validate();
            $claveValidator->addValidator(new Zend_Validate_StringLength(4, 12));
            //   ->addValidator(new Zend_Validate_Alnum());
            if (! $claveValidator->isValid($this->_request->getParams('clave'))) {
                $errors['clave'] = $claveValidator->getMessages();
            }
            $this->view->errors = $errors;
        }
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function filterAction ()
    {
        //$filter = new Zend_Filter_Callback('strrev');
        /*$filterReplace = new Zend_Filter_PregReplace(array('/á/'=>'a',
                                                    '/é/'=>'e',
                                                    '/í/'=>'i',
                                                    '/ó/'=>'ó',
                                                    '/ú/'=>'u'));*/
        require_once 'Zend/Filter/Word/SeparatorToDash.php';
        $filterSepDash = new Zend_Filter_Word_SeparatorToDash();
        require_once 'Zend/Filter/Word/DashToUnderscore.php';
        $filterDashUnder = new Zend_Filter_Word_DashToUnderscore();
        require_once 'Zend/Filter/Alpha.php';
        $filterAlpha = new Zend_Filter_Alpha(false);
        //$filterAlpha->setMeansEnglishAlphabet(true);
        require_once 'Zend/Filter/Word/SeparatorToDash.php';
        $filterSeparatorToDash = new Zend_Filter_Word_SeparatorToDash();
        $cadena = 'Hola mundo ? que tal %$%·$%';
        $this->view->data = $filterSeparatorToDash->filter($filterSepDash->filter(trim($cadena)));
    } // end function
    function insertAction() {
        $data=$this->_request->getParam('data');
        try {
            //$this->db->query("insert into prueba(id,nom) values(1,'$data');");
            $this->db->query("EXEC KO_SP_PRUEBA_INS ?, ?", array(1,$data));
            $this->view->msg = 'OK';
        } catch(Exception $e) {
            $this->view->msg = $e->getMessage();
        }
    }

    function encriptarAction() {
        require_once 'KotearPagos.php';
        $kp = new KotearPagos();
        $this->view->data = $kp->encriptar('Hola mundo');
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()SELECT *
FROM KO_AVISO_TAGS KAT
INNER JOIN KO_AVISO KA ON KAT.ID_AVISO = KA.ID_AVISO
WHERE KAT.TAGS = '1gb'
     * @return type desc
     */
    function analizadorAction() {
        require_once 'Devnet/StandardAnalyzer/TokenFilter/SpanishStemmer/PorterStemmer.php';
        $lines = file($this->view->baseUrl() . '/stemm_test.txt');

        $now = time();
        foreach ($lines as $line) {
            $part = split(' ', $line);
            $st = PorterStemmer::Stemm($part[0]);
            if ($st != $part[1]) {
                print "Word: " . $part[0] . ", stem: " . $st . ", ";
                print "expected: " . $part[1];
                print " -- BAD<HR>";
            }
        }
        print "<BR>Stemmed: " . count($lines) . " words in " . (time() - $now) . " secs";

    } // end function


    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function analizadorestandarAction() {


        //$this->buildSampleIndex;
        require_once 'Zend/Search/Lucene.php';
        require_once 'Devnet/StandardAnalyzer/Analyzer/Standard/English.php';

        $q = 'wikipedias paris';

        Zend_Search_Lucene_Analysis_Analyzer::setDefault( new StandardAnalyzer_Analyzer_Standard_English() );

        /* Create a new index object */
        $index = new Zend_Search_Lucene('../var/data/', false);

        /* Here we are going to search over multiple fields. we are just creating the string for right now */
        $title_query 	= "title:($q)";
        $content_query 	= "content:($q)";
        $tags_query 	= "tags:($q)";

        /* Parse the query */
        $query = Zend_Search_Lucene_Search_QueryParser::parse("$title_query $content_query $tags_query");

        /* Execute the query (I am not usually this verbose in my commentary */
        $hits = $index->find($query);

        /* Print out the results */
        foreach($hits as $hit) {
            $id = $hit->docId;
            $title = $hit->title;
            $content = $hit->content;
            $score = $hit->score;

            echo "<p>
                    <b>$id - $title - $score</b><br>
		    <i>$content</i>
		  </p>";
        }


        $lines = file($this->view->baseUrl() . '/stemm_test.txt');

        $now = time();
        foreach ($lines as $line) {
            $part = preg_split(' ', $line);
            $st = stemm_es::stemm($part[0]);
            if ($st != $part[1]) {
                print "Word: " . $part[0] . ", stem: " . $st . ", ";
                print "expected: " . $part[1];
                print " -- BAD<HR>";
            }
        }
        print "<BR>Stemmed: " . count($lines) . " words in " . (time() - $now) . " secs";



    } // end function

        /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function buildsampleAction() {
        $this->buildSampleIndex();

    }

    function buildSampleIndex() {
        /* This function shows the creation of a very simple (and informative!) index. */
        require_once 'Zend/Search/Lucene/Analysis/Analyzer.php';
        require_once 'Devnet/StandardAnalyzer/Analyzer/Standard/English.php';
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(new StandardAnalyzer_Analyzer_Standard_English());
        $index = new Zend_Search_Lucene('../var/data/', true);

        $index->addDocument($this->createDocument("1",
                "Selected Reading from Wikipedia - Doc Holiday",
                "Doc Holliday was born in Griffin, Georgia, to Henry Burroughs Holliday and Alice Jane Holliday (n�e McKey).[1] His father served in both the Mexican-American War and the Civil War."));

        $index->addDocument($this->createDocument("2",
                "Selected Reading from Wikipedia - Open Source Initiative",
                "The Open Source Initiative is an organization dedicated to promoting open-source software. The organization was founded in February 1998, by Bruce Perens and Eric S. Raymond, when Netscape Communications Corporation published the source code for its flagship Netscape Communicator product as free software due to lowering profit margins and competition with Microsoft's Internet Explorer software."));

        $index->addDocument($this->createDocument("3",
                "Selected Reading from Wikipedia - Catacombs of Paris",
                "The Catacombs of Paris are a famous underground ossuary in Paris, France. Organized in a renovated section of the city's vast network of subterranean tunnels and caverns towards the end of the 18th century, it became a tourist attraction on a small scale from the early 19th century, and was open to the public on a regular basis from 1867."));

        $index->addDocument($this->createDocument("4",
                "Selected Reading from Wikipedia - Donald Knuth",
                "Knuth has been called the father of the analysis of algorithms, contributing to the development of, and systematizing formal mathematical techniques for, the rigorous analysis of the computational complexity of algorithms, and in the process popularizing asymptotic notation."));

        $index->addDocument($this->createDocument("5",
                "Selected Reading from Wikipedia - Tuned Mass Damper",
                "A tuned mass damper, also known as an active mass damper (AMD) or harmonic absorber, is a device mounted in structures to prevent discomfort, damage or outright structural failure by vibration. They are most frequently used in power transmission, automobiles, and in buildings."));

        $index->addDocument($this->createDocument("6",
                "Selected Reading from Wikipedia - Theory of Everything",
                "A theory of everything (TOE) is a hypothetical theory of theoretical physics that fully explains and links together all known physical phenomena."));

        $index->commit();
        //return $index;
    }

    function & createDocument($id, $name, $d) {
        require_once 'Zend/Search/Lucene/Document.php';
        $doc = new Zend_Search_Lucene_Document();

        // Create Fields
        $docId 		= Zend_Search_Lucene_Field::Text( 'docId', $id	,	'UTF-8' );
        $title 		= Zend_Search_Lucene_Field::Text( 'title', $name,	'UTF-8' );
        $content 	= Zend_Search_Lucene_Field::Text( 'content', $d,	'UTF-8' );

        //Boost fields
        $title->boost 	= 1.8;

        // Add to doc
        $doc->addField( $docId );
        $doc->addField( $title );
        $doc->addField( $content );
        var_dump($doc);

        return $doc;
    }


    /**
     * Analizador en español, pruebas
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function analizadorkotearAction() {

        //Prueba con BD
        $cbd = new Devnet_Time();
        $cbd->startTime();

        require_once 'Prueba.php';
        $avisos = new Prueba();

        $query = $avisos->getAvisosBusqueda();
        $this->view->tiempoConBaseDatos = $cbd->endTime();

        //Prueba de Busqueda con File
        $queryFile = new Devnet_Time();
        $queryFile->startTime();

        //$this->buildSampleIndex;
        require_once 'Zend/Search/Lucene.php';
        require_once 'Devnet/StandardAnalyzer/Analyzer/Standard/Spanish.php';

        $q = 'laptop';

        $tags = Zend_Search_Lucene_Analysis_Analyzer::setDefault( new StandardAnalyzer_Analyzer_Standard_Spanish() );
        var_dump($tags);
        /* Create a new index object */
        $index = new Zend_Search_Lucene('../var/data/', false);
        echo "Total ".$index->numDocs()." docs\n";

        /* Here we are going to search over multiple fields. we are just creating the string for right now */
        $title_query 	= "title:($q)";
        $content_query 	= "content:($q)";
        $tags_query 	= "tags:($q)";

        /* Parse the query */
        $query = Zend_Search_Lucene_Search_QueryParser::parse("$title_query $content_query $tags_query");
        //printf($query);
        /* Execute the query (I am not usually this verbose in my commentary */
        $hits = $index->find($query);

        for($i = 0; $i < 1000; $i++)
        {
        /* Print out the results */
        foreach($hits as $hit) {
            $id = $hit->docId;
            $title = $hit->title;
            $content = $hit->content;
            $score = $hit->score;

            /*echo "<p>
                    <b>$id - $title - $score</b><br>
		    <i>$content</i>
		  </p>";*/
        }
        }
        echo count($hits);
        $this->view->tiempoBusquedaFile = $queryFile->endTime();
        /*
        $lines = file($this->view->baseUrl() . '/stemm_test.txt');

        $now = time();
        foreach ($lines as $line) {
            $part = split(' ', $line);
            $st = stemm_es::stemm($part[0]);
            if ($st != $part[1]) {
                print "Word: " . $part[0] . ", stem: " . $st . ", ";
                print "expected: " . $part[1];
                print " -- BAD<HR>";
            }
        }
        print "<BR>Stemmed: " . count($lines) . " words in " . (time() - $now) . " secs";
        */
    } // end function

        /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function buildkotearAction() {
        $this->buildKotearIndex();

    }

    function buildKotearIndex() {
        require_once 'Zend/Search/Lucene/Analysis/Analyzer.php';
        require_once 'Devnet/StandardAnalyzer/Analyzer/Standard/Spanish.php';
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(new StandardAnalyzer_Analyzer_Standard_Spanish());
        $index = new Zend_Search_Lucene('../var/data/', true);

        require_once 'Prueba.php';
        $avisos = new Prueba();
        $query = $avisos->getAvisosBusqueda();

        foreach ($query as $aviso) {
            $index->addDocument($this->createDocument($aviso->ID_AVISO,
                    $aviso->TIT, $aviso->HTML
            ));
        }
        $index->commit();

        /*$index->addDocument($this->createDocument("1",
                "Vendo Celulares Samsung con pantalla táctil y doble chip",
                "Un gran precio para este celular Samsung, super economico y no lo encuentras en cualquier parte."));

        $index->addDocument($this->createDocument("2",
                "Remato televisor samsung de 21' pulgadas ideal para los niños y sus juegos",
                "Para Xbox, juegos multimedias, PS3, Atari F560, y ver sus películas favoritas en 3D con toda la familia."));

        $index->addDocument($this->createDocument("3",
                "Selected Reading from Wikipedia - Catacombs of Paris",
                "The Catacombs of Paris are a famous underground ossuary in Paris, France. Organized in a renovated section of the city's vast network of subterranean tunnels and caverns towards the end of the 18th century, it became a tourist attraction on a small scale from the early 19th century, and was open to the public on a regular basis from 1867."));

        $index->addDocument($this->createDocument("4",
                "Selected Reading from Wikipedia - Donald Knuth",
                "Knuth has been called the father of the analysis of algorithms, contributing to the development of, and systematizing formal mathematical techniques for, the rigorous analysis of the computational complexity of algorithms, and in the process popularizing asymptotic notation."));

        $index->addDocument($this->createDocument("5",
                "Selected Reading from Wikipedia - Tuned Mass Damper",
                "A tuned mass damper, also known as an active mass damper (AMD) or harmonic absorber, is a device mounted in structures to prevent discomfort, damage or outright structural failure by vibration. They are most frequently used in power transmission, automobiles, and in buildings."));

        $index->addDocument($this->createDocument("6",
                "Selected Reading from Wikipedia - Theory of Everything",
                "A theory of everything (TOE) is a hypothetical theory of theoretical physics that fully explains and links together all known physical phenomena."));

        $index->commit();*/
        //return $index;
    }

    /**
     * Stemmer
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function stemmerAction() {
        require_once 'Devnet/StandardAnalyzer/SpanishStemmer.php';
        $texto = 'vendo LAPTOPS  vendíamos vendéis  nos koteao   vendí    toshibas a vosotros y para los niños pantallas de 12" pulg. 12x12 ';

        $stemm = new StandardAnalyzer_SpanishStemmer();
        $tags = $stemm->getTags($texto);
        var_dump($tags);
        $cadena = implode(' ',$tags);
        echo $cadena;
    } // end function

    /**
     * Generacion de tags de los avisos registrados
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function generaciontagsAction() {
        require_once 'Devnet/StandardAnalyzer/SpanishStemmer.php';
        $stemm = new StandardAnalyzer_SpanishStemmer();

        require_once 'Prueba.php';
        $avisos = new Prueba();
        // Modelo para los avisos de migracion
        $query = $avisos->getAvisosBusqueda();
        foreach ($query as $aviso) {
            //Repeticion de palabras
            $tags = implode(' ',$stemm->getTags($this->view->utils->repeticionesPalabras($aviso->TIT)));
            $url = $this->view->utils->convertSEO($aviso->TIT);
            $this->view->cadena = $aviso->TIT . ' - ' . $tags . ' - ' . $url;
            echo $aviso->TIT . ' - ' . $tags . ' - ' . $url . '<br/>';
            if (strlen($tags) > 1) {
                $this->db->query("UPDATE KO_AVISO SET TAG = '". $tags."', URL = '". $url."' WHERE ID_AVISO = ?", $aviso->ID_AVISO);
            }
            else {
                $this->db->query("UPDATE KO_AVISO SET TAG = '". $tags."', URL = '". $url."' WHERE ID_AVISO = ?", $aviso->ID_AVISO);
            }
        }
    } // end function

    /**
     * Generacion de tags de los avisos registrados
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function busquedatagsAction() {
        require_once 'Devnet/StandardAnalyzer/SpanishStemmer.php';
        $stemm = new StandardAnalyzer_SpanishStemmer();

        require_once 'Prueba.php';
        $busqueda = "laptops";
        $avisos = new Prueba();


        $search = $this->db->fetchAll("SELECT
        DISTINCT
	KA.ID_AVISO,
	KA.TIT,
	KA.SUBTIT,
	KA.URL,
	DATEDIFF(DAY,DATEADD(DAY,CAST(KE.DES AS INT),KA.FEC_PUB),GETDATE()) AS CADUCIDAD,
	COUNT(KO.ID_OFERTA) AS NUM_OFERTAS,
	COUNT(1) AS COINCIDENCIAS,
	SUM(KAT.TITULO) as relevancia,
	SUM(KDS.MONTO) as PRI,
	KA.VISITAS,
	KA.PRECIO,
	KM.SIMB,
	KU.NOM+' '+KU.APEL AS USUARIO,
	KP.PUNTAJE,
	KTU.ID_TIPO_USUARIO,
	KDS.ID_DESTAQUE,
	KS.DES AS ESTILO,
	KDS.DES,
	CASE WHEN KF.ID_FOTO IS NULL THEN 'none.gif' ELSE CAST(KU.ID_USR AS VARCHAR(30))+'/'+KF.NOM END AS IMAGEN,
	KDS.MONTO as precio_destaque,
	KA.FEC_REG as fec_registro_aviso

	FROM KO_AVISO_TAGS KAT
	LEFT JOIN (select ltrim(rtrim(lower(TAG)))as tag from @Tabtag) P ON P.tag=KAT.TAGS
	INNER JOIN KO_AVISO KA ON KAT.ID_AVISO = KA.ID_AVISO and (KA.EST=1  OR KA.EST=2)
	INNER JOIN KO_USUARIO KU ON KU.ID_USR=KA.ID_USR
	INNER JOIN KO_AVISO_DESTAQUE KAD ON  KAD.ID_AVISO=KA.ID_AVISO 	INNER JOIN KO_DESTAQUE KDS ON KDS.ID_DESTAQUE=KAD.ID_DESTAQUE AND KDS.ID_TIPO_DESTAQUE=2
	INNER JOIN KO_ESTILO KS ON KS.ID_ESTILO=KDS.ID_ESTILO
	INNER JOIN KO_DURACION KE ON KE.ID_DURACION=KA.ID_DURACION
	LEFT JOIN KO_FOTO KF ON KF.ID_AVISO=KA.ID_AVISO AND KF.PRIO=1
	LEFT JOIN KO_OFERTA KO ON KO.ID_AVISO=KA.ID_AVISO AND KO.EST=1
	LEFT JOIN KO_REPUTACION KP ON KP.ID_USR=KU.ID_USR
	LEFT JOIN KO_USUARIO_PORTAL KUP ON KUP.ID_USR=KU.ID_USR
	LEFT JOIN KO_TIPO_USUARIO KTU ON KTU.ID_TIPO_USUARIO=KUP.ID_TIPO_USUARIO
	LEFT JOIN KO_USUARIO_RANGO KUR ON KUR.ID_TIPO_USUARIO=KTU.ID_TIPO_USUARIO
	LEFT JOIN KO_UBIGEO KB ON KB.ID_UBIGEO=KA.ID_UBIGEO
	LEFT JOIN KO_TIPO_PRODUCTO KTP ON KTP.ID_TIPO_PRODUCTO=KA.ID_TIPO_PRODUCTO
	LEFT JOIN KO_MONEDA KM ON KM.ID_MONEDA=KA.ID_MONEDA

	GROUP BY KA.ID_AVISO,KA.TIT,KA.SUBTIT,KA.VISITAS, KA.URL,KE.DES,KO.ID_OFERTA,KA.FEC_PUB,KA.PRECIO,KM.SIMB
	,KU.NOM,KU.APEL,KP.PUNTAJE,KTU.ID_TIPO_USUARIO,KDS.ID_DESTAQUE,
	KS.DES,KDS.DES,KF.ID_FOTO,KDS.MONTO,KA.FEC_REG
	,KU.ID_USR,KF.NOM
	ORDER BY COUNT( 1 ) DESC , relevancia DESC,
        PRI DESC , KA.VISITAS DESC");

        $query = $avisos->getAvisosBusqueda();
        foreach ($query as $aviso) {
            if ($aviso->TAG <> ' ') {
            $tags = $stemm->getTags($aviso->TIT);
            $cadena = implode(' ',$tags);
            if ($cadena <> ' ') {
            $this->db->query("UPDATE KO_AVISO SET TAG = '". $cadena."' WHERE ID_AVISO = ?", $aviso->ID_AVISO);
                }
            }
        }
    } // end function


    /**
     * Generacion de tags de los avisos registrados
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function paginatorAction() {
        // Set pagination settings
        $page = $this->_getParam('page', 1);
        $itemCountPerPage = 15;
        $pageRange = 20;

        require_once 'Prueba.php';
        $avisos = new Prueba();
        $query = $avisos->getAvisosBusqueda();

        // Create paginator
        $paginator = Zend_Paginator::factory($query);
        $paginator->setItemCountPerPage($itemCountPerPage)
                ->setCurrentPageNumber($page)
                ->setPageRange($pageRange);

        // Create paginator control partial view
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('general/search_pagination_control.phtml');

        // Assign paginator to view
        $this->view->paginator = $paginator;
    } // end function

    public function clientAction() {
        $client = new Zend_Soap_Client($this->_WSDL_URI);

        $this->view->add_result = $client->math_add(11, 55);
        $this->view->not_result = $client->logical_not(true);
        $this->view->sort_result = $client->simple_sort(
       array("d" => "lemon", "a" => "orange",
             "b" => "banana", "c" => "apple"));
        }
    public function clienteSoapAction(){
        $this->_helper->layout->setLayout('clear');
        require_once 'Zend/Soap/Client.php';
        try{
            $wsdlUrl = 'http://192.168.1.69/kotear_ws/permisos/index.php?wsdl';
            $client = new Zend_Soap_Client($wsdlUrl);
            //var_dump($client);
            $this->saludo = $client->saludame('Brady', 'es');

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        /*$wsdlUrl = 'http://200.4.199.84/wsconsultageneral/service.asmx?WSDL';

        $cliente = new Zend_Soap_Client($wsdlUrl);
        var_dump($cliente);*/
        //$this->mensaje = $cliente->saludame('Brady', 'es');
        //$this->mensaje = 'hola';
        //var_dump($this->mensaje);
        /*try{
        $client = new Zend_Soap_Client('http://200.4.199.84/wsconsultageneral/service.asmx?WSDL');
        $result1 = $client->ConsultarTipoCambio();
        echo $result1;
        } catch (Exception $e) {
        echo $e;
        }*/
    }
    public function xmlAction(){
        $this->_helper->layout->setLayout('clear');
        //BusquedaBackup();
        $data = $this->db->fetchAll("EXECUTE KO_SP_BUSQUEDA_BACKUP '-1', 'prueb', '', '-1', '', '-1', '-1','-1', '-1', '-1', '-1', '-1', '-1', '-1', '1', '30', '0'");
        $this->view->data = $data;
    }
    public function xhtmlAction(){
        return 0;
    }
    public function queryBlobAction(){
      $db = Zend_Registry::get('db');
      //$rs = $db->fetchAll("SELECT CONTENIDO FROM IK_PLANTILLA");
      $db->query("SET TEXTSIZE 30000;");
      $rs = $db->fetchAll(" EXEC IK_SP_PLANTILLA_SEL PPA");

      print_r($rs);die();
    }
    public function templateAction(){
      $template = new Devnet_TemplateLoad('enviopregunta');
      echo $template->getTemplate();die();
    }
    public function editFileAction(){
        if ( $this->_request->getParam('opc')=='1' ){ 
            //Cargar archivo
            $this->view->archivo=$this->_request->getParam('archivo');
            if (!file_exists($this->view->archivo)){
                $this->view->error = 'El archivo no existe: '.$this->view->archivo;
            }
            $this->view->texto= file_get_contents($this->view->archivo);
        } //end if $this->_request->getParam('opc')=='1'
        if ( $this->_request->getParam('opc')=='2' ){
            $result = file_put_contents($this->_request->getParam('archivo'),$this->_request->getParam('texto')); 
        } //end if $this->_request->getParam('opc')=='2'
        
    }
}

?>