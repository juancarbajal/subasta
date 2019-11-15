<?php
/**
 * @author ander
 *
 */
class Admin_formsProducto extends Devnet_Form
{
    private $_form;
    
    public function init() {
        parent::init();
        $this->_form = $this;
    }

    public function getDesactivarProducto()
    {
        $this->setMethod('POST');
        
        $idAViso = $this->createElement('text', 'idproducto');
        $idAViso->setLabel('Id Producto :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50))
                ->addValidator('Int')
                ->setRequired(true);      
        
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Enviar');

        $this->addElements(array($idAViso, $submit));
        
        return $this->_form;
    }

    /**
     *
     * @return Zend_Form
     */
    public function getAgregarDestaque()
    {
        $this->setMethod('POST');
        $destaque = new Destaque();
        
        $destaquesPremium = $destaque->getListForCombo('TIT', array('EST' => '1', 'ID_TIPO_DESTAQUE' => '1'));
        $destaquesListado = $destaque->getListForCombo('TIT', array('EST' => '1', 'ID_TIPO_DESTAQUE' => '2'));
        
        $cboPrem = $this->createElement('radio', 'destaquePremium', array('escape' => false))
                ->setLabel('Destaque Especiales')
                ->setRequired(TRUE);
        foreach ($destaquesPremium as $key => $destaquePremium) {
            $cboPrem->addMultiOption($key, $destaquePremium);
//            $cboPrem->addMultiOption($key , '<span class="help-block">' . $destaquePremium .'</span><img class="destaque_img" src="'
//                    . $this->getView()->baseUrl() . '/f/img/inside/destaque' . $key . '.png "/>');
        }
        $cboLista = $this->createElement('radio', 'destaqueListado', array('escape' => false))
                ->setLabel('Destaques en Listado')
                ->setRequired(TRUE);
        foreach ($destaquesListado as $key => $destaqueListado) {
            $cboLista->addMultiOption($key, $destaqueListado);
        }
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Grabar');

        $this->addElements(array($cboPrem, $cboLista, $submit));
        
        return $this->_form;
    }
    
    /**
     *
     * @return Zend_Form
     */    
    public function getBusqueda()
    {
        $this->setAttrib("horizontal", true); 
        $this->setAttrib('id', 'busqueda-producto');
        /*
         * Pendiente Categoria
         * Pendiente Destaque
         */
        require_once 'TipoProducto.php';
        require_once 'TipoUsuario.php';
        require_once 'EstadoAviso.php';
        
        $tipoProducto = new TipoProducto();
        $tipoUsuario = new TipoUsuario();
        $estadoAvisoTable = new EstadoAviso();
       
        $this->setMethod('GET');
        
        $cbotipoProducto = $this->createElement('select', 'ID_TIPO_PRODUCTO');
        $cbotipoProducto->setLabel('Tipo del Producto :')
            ->addMultiOptions(array('' => '--- Seleccione ---    ') 
                    + $tipoProducto->getListForCombo('DES'));
        
        $cbotipoUsuario = $this->createElement('select', 'idTipoUsuario');
        $cbotipoUsuario->setLabel('Tipo de Usuario :')
            ->addMultiOptions(array('' => '--- Seleccione ---    ') 
                    + $tipoUsuario->getListForCombo('DES', array('EST' => 1)));
        
        $txtAviso = $this->createElement('text', 'idAviso');
        $txtAviso->setLabel('Id Producto :')
                ->addValidator('Int');
        
        $txtUsuario = $this->createElement('text', 'apodo');
        $txtUsuario->setLabel('Nombre Comercial :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50));

        $cboEstado= $this->createElement('select', 'EST');
        $cboEstado->setLabel('Estado del Producto:')
            ->addMultiOptions(array('' => '--- Seleccione ---    ') 
                    + $estadoAvisoTable->getListForCombo('DESCRIPCION', 
                            array('ID_ESTADO_AVISO' => array(1,9,10,12,13,16))));
        
        
        $txtProducto = $this->createElement('text', 'q');
        $txtProducto->setLabel('Nombre del producto :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50));        
        
        $txtFecHasta = $this->createElement('text', 'txtFecHasta');
        $txtFecHasta->setLabel('Fech.Pub Hasta :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50));
        
        $txtFecDesde = $this->createElement('text', 'txtFecDesde');
        $txtFecDesde->setLabel('Fech.Pub Desde :')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50));
        
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Consultar');        
        
        $this->addElements(array($cbotipoProducto, $cbotipoUsuario, $txtAviso, 
            $txtProducto, $txtUsuario, $cboEstado, $txtFecDesde, $txtFecHasta, $submit));
        
        return $this->_form;
    }
    
    /**
     *
     * @param array $rubros
     * @return Zend_Form 
     */
    public function getEditar($rubros = null, $est = null)
    {
        $this->setAttrib("vertical", true); 
        require_once 'Categoria.php';
        require_once 'Moderacion.php';
        require_once 'EstadoAviso.php';
        
        $categoria = new Categoria();
        $estadoAvisoTable = new EstadoAviso();        
        $moderacionAviso = new Moderacion();        

        $token = new Zend_Form_Element_Hash('token');
        $token->setSalt(md5(uniqid(rand(), TRUE)))
                ->setTimeout(300);

        $titulo = $this->_form->createElement('text', 'TIT');
        $titulo->setLabel('Titulo: ')
                ->setRequired(true);

        $html = $this->_form->createElement('textarea', 'HTML');
        $html->setLabel('Descripción del Producto:  ')
                ->setAttrib('rows', 20)
                ->setAttrib('style', 'width: 818px; height: 300px;');

        $rubro = $this->_form->createElement('select', 'rubro');
        $rubro->setLabel('Rubro: ')
                ->addMultiOptions(array('' => '--- Seleccione ---    ')
                    + $categoria->getListForCombo('DES', array('EST' => 1,
                    'NIVEL' => 1)))
                ->setAttrib('style', 'font-size: 11px;')
                ->addValidator('StringLength', false, array(1, 50))
                ->addValidator('Int')                
                ->setRequired(TRUE);
                
                
        $subRubro1 = $this->_form->createElement('select', 'subrubro1');
        $subRubro1->setLabel('Subrubro 1')
                ->setAttrib('size', 20)
                ->setAttrib('style', 'font-size: 11px;')
                ->setRequired(TRUE);
        if ($rubros['subrubro1']) {
            $datos = explode(',', $rubros['subrubro1']);
            $subRubro1->addMultiOptions($categoria->getListForCombo('DES', 
                    array('EST' => 1,
                    'NIVEL' => 2,
                    'ID_PADRE' => $datos[1],)));
        }

        $subRubro2 = $this->_form->createElement('select', 'subrubro2');
        $subRubro2->setLabel('Subrubro 2')
                ->setAttrib('size', 20)
                ->setAttrib('style', 'font-size: 11px;')
                ->setRequired(TRUE);
        if ($rubros['subrubro2']) {
            $datos = explode(',', $rubros['subrubro2']);
            $subRubro2->addMultiOptions($categoria->getListForCombo('DES', 
                    array('EST' => 1,
                    'NIVEL' => 3,
                    'ID_PADRE' => $datos[1],)));
        }        

        $subRubro3 = $this->_form->createElement('select', 'subrubro3');
        $subRubro3->setLabel('Subrubro 3')
                ->setAttrib('size', 20)
                ->setAttrib('style', 'font-size: 11px;')
                ->setRequired(TRUE);
        if ($rubros['subrubro3']) {
            $datos = explode(',', $rubros['subrubro3']);
            $subRubro3->addMultiOptions($categoria->getListForCombo('DES', 
                    array('EST' => 1,
                    'NIVEL' => 4,
                    'ID_PADRE' => $datos[1],)));
        }

        $hidden = $this->_form->createElement('hidden', 'a');
        
        $txtFecFin = $this->createElement('text', 'FEC_FIN');
        $txtFecFin->setLabel('Fecha de vencimiento :')
                ->setAttrib('size', 25)
                ->setAttrib('readonly', 'TRUE')
                ->setAttrib('style', 'cursor:pointer;background-color: #FFFFFF')
                ->addValidator('StringLength', false, array(1, 50));        
        
        $txtHorFin = $this->createElement('hidden', 'hora')
                ->setAttrib('size', 25)
                ->addValidator('StringLength', false, array(1, 50));  
        
        $moderacion = $this->_form->createElement('select', 'avimoderacion');
        $moderacion->setLabel('Moderación: ')
                ->addMultiOptions(array('' => '--- Seleccione ---    ')
                    + $moderacionAviso->getListForCombo('DES'))
                ->setAttrib('style', 'font-size: px;')
                ->addValidator('Int');

        $submit = $this->createElement('submit', 'modificar');
        $submit->setLabel('Modificar')
                ->setAttrib('style', 'font-size:15px')
                ->setAttrib('style', 'width:200px')
                ->setAttrib('style', 'btn btn-primary');
                
        
        $this->_form->addElements(array($token, $titulo, $html, $txtFecFin, $txtHorFin, $rubro, $subRubro1
            , $subRubro2, $subRubro3, $hidden, $moderacion, $submit));
        
        $this->_form = $this->getBusqueda();
        $this->_form->setMethod('POST');
        $this->_form->removeElement('EST');
        $this->_form->removeElement('txtFecHasta');
        $this->_form->removeElement('txtFecDesde');
        $this->_form->removeElement('apodo');
        $this->_form->removeElement('idAviso');
        $this->_form->removeElement('idTipoUsuario');
        $this->_form->removeElement('send');
        $this->_form->removeElement('ID_TIPO_PRODUCTO');
        
        return $this->_form;
    }
}