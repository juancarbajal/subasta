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
require_once ('Base/Aviso.php');
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

class Busqueda
    extends Base_Aviso
{

    /**
     * Devuelve los avisos resultados de una busqueda, validando reglas de negocio
     * y generando totales de categorias y ubigeos
     * @param varchar $cadena string  para la busqueda (realizado despues de los filtros)
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getBusquedaAvisosPerformance ($array, $defaultFiltro, $procesarBusquedaTipo)
    {
        // Validamos si es busqueda por modulo o por avisos
        if (isset($array['mod']) && ($array['mod'] == $defaultFiltro)) {
            // Procesamos la busqueda por aviso
            if ($procesarBusquedaTipo == 'CENTRAL' && $procesarBusquedaTipo == 'PRUEBA-BORRAR') {
                // Realizamos el proceso rapido
                return $this->getAdapter()->fetchAll(
                    'EXECUTE KO_SP_BUSQUEDA_CENTRAL ?, ?, ?',
                    array(
                        ($array['q'] == $defaultFiltro) ? '':$array['q'] . '*',
                        ($array['categs'] == $defaultFiltro) ? -1:$array['categs'],
                        ($array['adulto'] == $defaultFiltro) ? 0:$array['adulto']
                     )
                );
            } else {
                // Realizamos el proceso de filtrados
                return $this->getAdapter()->fetchAll(
                    'EXECUTE KO_SP_BUSQUEDA_FILTRO ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                    array(
                        ($array['id'] == $defaultFiltro) ? -1:$array['id'],
                        ($array['q'] == $defaultFiltro) ? '':$array['q'] . '*',
                        ($array['exclude'] == $defaultFiltro) ? '':$array['exclude'],
                        ($array['categs'] == $defaultFiltro) ? -1:$array['categs'],
                        ($array['usuario'] == $defaultFiltro) ? '':$array['usuario'],
                        ($array['apodo'] == $defaultFiltro) ? '':$array['apodo'],
                        ($array['tv'] == $defaultFiltro) ? -1:$array['tv'],
                        ($array['rep'] == $defaultFiltro) ? -1:$array['rep'],
                        ($array['pmin'] == $defaultFiltro) ? -1:$array['pmin'],
                        ($array['pmax'] == $defaultFiltro) ? -1:$array['pmax'],
                        ($array['ubic'] == $defaultFiltro) ? -1:$array['ubic'],
                        ($array['tm'] == $defaultFiltro) ? -1:$array['tm'],
                        ($array['tp'] == $defaultFiltro) ? -1:$array['tp'],
                        ($array['ta'] == $defaultFiltro) ? -1:$array['ta'],
                        ($array['mod'] == $defaultFiltro) ? -1:$array['mod'],
                        ($array['ord'] == $defaultFiltro) ? -1:$array['ord'],
                        ($array['page'] == $defaultFiltro) ? 1:$array['page'],
                        ($array['nr'] == $defaultFiltro) ? 30:$array['nr'],
                        ($array['adulto'] == $defaultFiltro) ? 0:$array['adulto']
                    )
                );
            }
        } else {
            // Procesamos la busqueda por modulos
            return $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_BUSQUEDA_FILTRO_MODULOS ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    , ?',
                array(($array['id'] == $defaultFiltro) ? -1:$array['id'],
                       ($array['q'] == $defaultFiltro) ? '':$array['q'] . '*',
                       ($array['exclude'] == $defaultFiltro) ? '':$array['exclude'],
                       ($array['categs'] == $defaultFiltro) ? -1:$array['categs'],
                       ($array['usuario'] == $defaultFiltro) ? '':$array['usuario'],
                       ($array['apodo'] == $defaultFiltro) ? '':$array['apodo'],
                       ($array['tv'] == $defaultFiltro) ? -1:$array['tv'],
                       ($array['rep'] == $defaultFiltro) ? -1:$array['rep'],
                       ($array['pmin'] == $defaultFiltro) ? -1:$array['pmin'],
                       ($array['pmax'] == $defaultFiltro) ? -1:$array['pmax'],
                       ($array['ubic'] == $defaultFiltro) ? -1:$array['ubic'],
                       ($array['tm'] == $defaultFiltro) ? -1:$array['tm'],
                       ($array['tp'] == $defaultFiltro) ? -1:$array['tp'],
                       ($array['ta'] == $defaultFiltro) ? -1:$array['ta'],
                       ($array['mod'] == $defaultFiltro) ? -1:$array['mod'],
                       ($array['ord'] == $defaultFiltro) ? -1:$array['ord'],
                       ($array['page'] == $defaultFiltro) ? 1:$array['page'],
                       ($array['nr'] == $defaultFiltro) ? 30:$array['nr'],
                       ($array['adulto'] == $defaultFiltro) ? 0:$array['adulto']
                 )
            );
        }
    }

    /**
     * Devuelve los avisos resultados de una busqueda
     * @param varchar $cadena string  para la busqueda (realizado despues de los filtros)
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getBusquedaAvisos ($array, $defaultFiltro)
    {
        if ($array['mod'] == $defaultFiltro) {
            // Realizamos la busqueda normal
            return $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_BUSQUEDA ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                array(($array['id'] == $defaultFiltro) ? -1:$array['id'],
                       ($array['q'] == $defaultFiltro) ? '':$array['q'] . '*',
                       ($array['exclude'] == $defaultFiltro) ? '':$array['exclude'],
                       ($array['categs'] == $defaultFiltro) ? -1:$array['categs'],
                       ($array['apodo'] == $defaultFiltro) ? '':$array['apodo'],
                       ($array['tv'] == $defaultFiltro) ? -1:$array['tv'],
                       ($array['rep'] == $defaultFiltro) ? -1:$array['rep'],
                       ($array['pmin'] == $defaultFiltro) ? -1:$array['pmin'],
                       ($array['pmax'] == $defaultFiltro) ? -1:$array['pmax'],
                       ($array['ubic'] == $defaultFiltro) ? -1:$array['ubic'],
                       ($array['tm'] == $defaultFiltro) ? -1:$array['tm'],
                       ($array['tp'] == $defaultFiltro) ? -1:$array['tp'],
                       ($array['ta'] == $defaultFiltro) ? -1:$array['ta'],
                       ($array['mod'] == $defaultFiltro) ? -1:$array['mod'],
                       ($array['ord'] == $defaultFiltro) ? -1:$array['ord'],
                       ($array['page'] == $defaultFiltro) ? 1:$array['page'],
                       ($array['nr'] == $defaultFiltro) ? 30:$array['nr'],
                       ($array['adulto'] == $defaultFiltro) ? 0:$array['adulto']
                )
            );
        } else {
            return $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_BUSQUEDA_MODULO ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                array(($array['id'] == $defaultFiltro) ? -1:$array['id'],
                   ($array['q'] == $defaultFiltro) ? '':$array['q'] . '*',
                   ($array['exclude'] == $defaultFiltro) ? '':$array['exclude'],
                   ($array['categs'] == $defaultFiltro) ? -1:$array['categs'],
                   ($array['apodo'] == $defaultFiltro) ? '':$array['apodo'],
                   ($array['tv'] == $defaultFiltro) ? -1:$array['tv'],
                   ($array['rep'] == $defaultFiltro) ? -1:$array['rep'],
                   ($array['pmin'] == $defaultFiltro) ? -1:$array['pmin'],
                   ($array['pmax'] == $defaultFiltro) ? -1:$array['pmax'],
                   ($array['ubic'] == $defaultFiltro) ? -1:$array['ubic'],
                   ($array['tm'] == $defaultFiltro) ? -1:$array['tm'],
                   ($array['tp'] == $defaultFiltro) ? -1:$array['tp'],
                   ($array['ta'] == $defaultFiltro) ? -1:$array['ta'],
                   ($array['mod'] == $defaultFiltro) ? -1:$array['mod'],
                   ($array['ord'] == $defaultFiltro) ? -1:$array['ord'],
                   ($array['page'] == $defaultFiltro) ? 1:$array['page'],
                   ($array['nr'] == $defaultFiltro) ? 30:$array['nr'],
                   ($array['adulto'] == $defaultFiltro) ? 0:$array['adulto']
                )
            );

        }
    }
    
    public function suggestFull($paramaQ)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('suggest_' . sha1($paramaQ))) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_AVISO_SUGGEST ?", $paramaQ);
            $cache->save($result, 'suggest_' . sha1($paramaQ));
        }        
        return $result;
    }
}
