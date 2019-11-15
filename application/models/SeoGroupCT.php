<?php
require_once 'Base/SeoGroupCT.php';

class SeoGroupCT extends Base_SeoGroupCT
{
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function listSEOGroupParam()
    { //OBTENER LOS DATOS DE LA TABLA KO_AVISO Y DE LAS TABLAS CON RELACION DE UNO A MUCHOS

        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('listSEO')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_SEO_GROUP_PARAM_SEL');
            $cache->save($result, 'listSEO');
        }
        return $result;
    }
}