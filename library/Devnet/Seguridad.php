<?php

/*
 * Ander
 */

class Devnet_Seguridad
{
    
    public function limpiarTags($string, $tags = null) {
//        "/><script>alert(1);</script>"
        return strip_tags($string);
//        $items = Array('/[</]+/');
//        return ereg_replace("[<\/]+[\/>]*", " ", $string); ;
//        return ereg_replace("([</])", " ", $string); ;
////        $return = str_replace($extranos, "", $string);
////        $return = preg_replace('([^A-Za-z0-9])', '', $string);
//        var_dump($return);exit;
    }

}
