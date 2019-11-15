<?php
require_once 'Base/Modulo.php';
class Modulo 
    extends Base_Modulo
{

    /**
     * Visualiza los items relacionados con el modulo tipo 1 de acuerdo a los criterios
     * Código del Módulo tipo 1
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function getModuloFooter($tipoModulo)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('moduloItems' . $tipoModulo)) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_AVISO_OFERTA_IMPERDIBLE ");
            $cache->save($result, 'moduloItems' . $tipoModulo);
        }
        return $result;
    }
    
    /**
     * Visualiza los items relacionados con el modulo tipo 1 de acuerdo a los criterios
     * Código del Módulo tipo 1
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function getModuloItems($tipoModulo)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('moduloItems' . $tipoModulo)) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_MODULO_ITEM_SEL ?", $tipoModulo);
            $cache->save($result, 'moduloItems' . $tipoModulo);
        }
        return $result;
    }

    /**
     * Visualiza los datos de un modulo
     * Código del Módulo tipo 2
     * Código de la categoría, en caso sea
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function getModulo($idModulo)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('modulo' . $idModulo)) {
            $result = $this->getAdapter()->fetchAll(
                'SELECT TIT, ORDEN, NUM_REG FROM IK_MODULO WHERE ID_MODULO = ?', array($idModulo)
            );
            $cache->save($result, 'modulo' . $idModulo);
        }
        return $result[0];
    }

    /**
     * Visualiza los avisos relacionados con el modulo tipo 2 de acuerdo a los criterios
     * Código del Módulo tipo 2
     * Código de la categoría, en caso sea
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function getModuloAvisos($idModulo, $idCategoria=0)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('moduloAvisos' . $idModulo . $idCategoria)) {
            $result = $this->getAdapter()->fetchAll(
                "EXECUTE KO_SP_AVISO_DESTACADOS_SEL ?, ?", array($idModulo, $idCategoria)
            );
            $cache->save($result, 'moduloAvisos' . $idModulo . $idCategoria);
        }
        return $result;
    }
}
