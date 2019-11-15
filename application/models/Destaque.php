<?php
require_once 'Base/Destaque.php';
class Destaque
    extends Base_Destaque
{
    function tieneDestaquesActivos($idAviso)
    {
        $response = $this->getAdapter()->fetchAll(
            'SELECT dbo.KO_FN2_TIENE_DESTAQUES (?,0)', array($idAviso)
        );
        if ($response[0]->computed > 0) {
            // Tiene destaques activos
            return true;
        } else {
            return false;
        }
    }
    function tieneDestaquesCargos($idAviso)
    {
        $response = $this->getAdapter()->fetchAll(
            'SELECT dbo.KO_FN2_TIENE_DESTAQUES (?,1)', array($idAviso)
        );
        if ($response[0]->computed > 0) {
            // Tiene destaques con cargos en kotear pagos
            return true;
        } else {
            return false;
        }
    }
    function validaDestaque($idAviso, $estadoAviso)
    {
        $response = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_AVISO_DESTAQUE_VALIDA ?, ?', array($idAviso, $estadoAviso)
        );
        return $response[0];
    }

    function validaDestaqueModificacion($idAviso, $idDestaque)
    {
        // Validamos que los destaques del aviso no sean diferentes
        $response = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_AVISO_DESTAQUE_VALIDAMODIFICACION ?, ?', array($idAviso, $idDestaque)
        );
        return $response[0];
    }
    function listaDesatque($tipodestaque)
    {
        $response= $this->getAdapter()->fetchAll('EXEC', array($tipodestaque));
        return $response[0];
    }
    
    public function getDestaque($idDestaque)
    {
        return $this->getAdapter()->fetchRow(
            'SELECT * FROM KO_DESTAQUE WHERE EST = 1 AND ID_DESTAQUE=?', array($idDestaque)
        );
    }
    
    public function getCombinacionDestaque($idDestaque)
    {
        switch ($idDestaque) {
            case 2:#Sin Destaque
                return array(1,2);
                break;
            case 5:#Silver
                return array(5,2);
                break;
            case 9:#Oro
                return array(3,9);
                break;
            case 10:#platinium
                return array(3,10);
                break;
        }
    }
    
    public function getDestaquePorAviso($idAviso)
    {
        $destaques = $this->getAdapter()->fetchAll('EXECUTE KO_SP_AVISO_DESTAQUE_SEL ?', array($idAviso));
        
        $destaqueId = 0;
        foreach ($destaques as $destaque) {
            if ($destaque->ID_DESTAQUE > $destaqueId) {
                $destaqueId = $destaque->ID_DESTAQUE;
            }
        }
        return $destaqueId;
    }
    
    //->Ander
    function getDestaquesActivas()
    {
        $cache = Zend_Registry::get('cache');
        $nameCache = 'DestaquesActivos';
        if (!$result = $cache->load($nameCache)) {
            $result = $this->getAdapter()->fetchAssoc('EXECUTE KO_SP_DESTAQUE_ACTIVO_QRY');
            $cache->save($result, $nameCache);
        }        
        return $result;
    }
    
}