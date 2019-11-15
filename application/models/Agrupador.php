<?php
require_once 'Base/Agrupador.php';
/**
 * @author
 *
 */
class Agrupador extends Base_Agrupador
{
    /**
     * Captura la lista de categorias relacionadas a determinada Categoria
     * @param integer $idCategoria
     */
    function getLevels ($idCategoria)
    {
        return $this->getAdapter()->fetchAll("EXEC KO_SP_AGRUPADOR_SEL ?", array($idCategoria));
    }

    /**
     * Captura la lista de categorias por Aviso
     * @param integer $idAviso
     */
    function getLevelsByAviso ($idAviso)
    {
        $cache =  Zend_Registry::get('cache');
        if (!$result = $cache->load('levelsByAviso'.$idAviso)) {
            $result = $this->getAdapter()->fetchAll("EXEC KO_SP_AGRUPADOR_POR_AVISO_SEL ?", array($idAviso));
            $cache->save($result, 'levelsByAviso'.$idAviso);
        }
        return $result;
    }
}