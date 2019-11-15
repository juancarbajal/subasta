<?php

require_once 'Base/Migracion.php';
class Migracion
    extends Base_Migracion
{
    function listarAvisos()
    {
        return $this->getAdapter()->fetchAll('select top 1 * from ttrabajo.PARAMETRO_FOTO');
    }
    function listarFotos($top)
    {
        return $this->getAdapter()->fetchAll(
            'select top '.$top.' * from ttrabajo.PARAMETRO_FOTO where DESCARGADO is null'
        );
    }
    function listarFotosRedimencionadas($top)
    {
        return $this->getAdapter()->fetchAll(
            'select top '.$top.'* from ttrabajo.PARAMETRO_FOTO where REDIMENSIONADO is null'
        );
    }

    function listarFotos2($numpag,$cantpag)
    {
        return $this->getAdapter()->fetchAll('exec pagFoto ?,?', array($numpag,$cantpag));
    }
    function listafotosTotalDescargar()
    {
        return $this->getAdapter()->fetchAll('select count(*) as cant from ttrabajo.PARAMETRO_FOTO ');
    }
    function listafotosTotalDescargadas()
    {
        return $this->getAdapter()->fetchAll(
            'select count(*) as cant from ttrabajo.PARAMETRO_FOTO where DESCARGADO is not null'
        );
    }
    function listaFotosNoRedimencionadas()
    {
        return $this->getAdapter()->fetchAll(
            'select count(*) as cant from ttrabajo.PARAMETRO_FOTO where REDIMENSIONADO is null'
        );
    }

    function listarHtml($top)
    {
        return $this->getAdapter()->fetchAll(
            'select top '.$top.' ID_AVISO,URL_ACTUAL from ttrabajo.PARAMETRO_FOTO group by 
                ID_AVISO,URL_ACTUAL'
        );
    }
    function listarHtml2($numpag,$cantpag)
    {
    return $this->getAdapter()->fetchAll('exec pagAviso ? , ? ', array($numpag,$cantpag));
    }
    function actualizarEstaHtml($idAviso)
    {
        $this->getAdapter()->fetchAll('exec ttrabajo.Actualiza_EstadoCargaHTML ?', array($idAviso));
    }
    function cantidadRegTotalHtml()
    {
        $return =$this->getAdapter()->fetchAll('select COUNT(ID_AVISO) as cont from ttrabajo.KO_AVISO');
        return $return[0]->cont;
    }
    function cantidadRegActualizadoHtml()
    {
        $return =$this->getAdapter()->fetchAll(
            'select COUNT(ID_AVISO) as cont from ttrabajo.KO_AVISO where HTML is not null'
        );
        return $return[0]->cont;
    }
    function cantidadRegFaltaActualizadoHtml()
    {
        $return =$this->getAdapter()->fetchAll(
            'select COUNT(ID_AVISO) as cont from ttrabajo.KO_AVISO where HTML is null'
        );
        return $return[0]->cont;
    }
    function actualizaDescFoto($idFoto,$est)
    {
        return $this->getAdapter()->fetchAll(
            'exec ttrabajo.Actualiza_EstadoCargaFOTO ?,?', array($idFoto,$est)
        );
    }
    function actualizaRedimencion($idFoto,$est)
    {
        return $this->getAdapter()->fetchAll(
            'exec ttrabajo.Actualiza_EstadoRedimencionFOTO ?,?', array($idFoto,$est)
        );
    }

}