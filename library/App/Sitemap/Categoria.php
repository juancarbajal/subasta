<?php
/**
 * Sitemap class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */

/**
 * Sitemap class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class App_Sitemap_Categoria
{
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function listarUrl()
    {
        $url["1909"] = array("loc"=>"sitemap_accesorios_repuestos");
        $url["1656"] = array("loc"=>"sitemap_adultos");
        $url["217"] = array("loc"=>"sitemap_animales_mascotas");
        $url["219"] = array("loc"=>"sitemap_antiguedades_arte");
        $url["4473"] = array("loc"=>"sitemap_artistas_peruanos");
        $url["220"] = array("loc"=>"sitemap_autos_motos");
        $url["1621"] = array("loc"=>"sitemap_bebes_ninos");
        $url["229"] = array("loc"=>"sitemap_camaras_fotografia");
        $url["237"] = array("loc"=>"sitemap_celulares_telefonia");
        $url["243"] = array("loc"=>"sitemap_coleccionables_hobbies");
        $url["208"] = array("loc"=>"sitemap_consolas_videojuegos");
        $url["226"] = array("loc"=>"sitemap_deportes_fitness");
        $url["241"] = array("loc"=>"sitemap_electrodomesticos");
        $url["228"] = array("loc"=>"sitemap_electronica_audio");
        $url["3489"] = array("loc"=>"sitemap_entradas_espectaculos");
        $url["230"] = array("loc"=>"sitemap_hogar_muebles");
        $url["238"] = array("loc"=>"sitemap_industrias_oficinas");
        $url["221"] = array("loc"=>"sitemap_informatica");
        $url["209"] = array("loc"=>"sitemap_inmuebles");
        $url["232"] = array("loc"=>"sitemap_instrumentos_musicales");
        $url["233"] = array("loc"=>"sitemap_joyas_relojes");
        $url["234"] = array("loc"=>"sitemap_juegos_juguetes");
        $url["244"] = array("loc"=>"sitemap_libros_revistas");
        $url["235"] = array("loc"=>"sitemap_musica_peliculas");
        $url["247"] = array("loc"=>"sitemap_negocios_servicios");
        $url["3473"] = array("loc"=>"sitemap_otras_categorias");
        $url["1381"] = array("loc"=>"sitemap_reproductores_mp3");
        $url["231"] = array("loc"=>"sitemap_ropa_accesorios");
        $url["236"] = array("loc"=>"sitemap_salud_belleza");
        $url["246"] = array("loc"=>"sitemap_turismo");
        
        return $url;
    }

}