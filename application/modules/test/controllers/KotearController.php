<?php
class Test_KotearController {
	public $dbKotear;
	function init() {
		$frontController = Zend_Controller_Front::getInstance();
        $db = $frontController->getParam('bootstrap')->getOption('kotearPagos');
		$this->dbKotear = Zend_Db::factory($db['db']);
	}
	public function pruebaAction(){
		$this->dbKotear->fetchAll('select * from KO_TABLA');
	}
}