<?php

class App_View_Helper_S extends Zend_View_Helper_HtmlElement
{

    /**
     * @param  String
     * @return string
     */
    public function S($file)
    {
        
        /**
         * @todo cache to avoid file reading frequently
         * @tutorial use a post-commit hook  to execute: git log -1 --format=%h > last_commit
         */
        $lc_file = APPLICATION_PATH.'/last_commit';
        if (is_readable($lc_file)){
            $vqs = trim(file_get_contents($lc_file));
        } else {
            $static = !isset($config->confpaginas->staticVersion)?1:$config->confpaginas->staticVersion;
            $vqs    = date('Ymd').$static;
        }
        
        return $file.'?v='.$vqs;
    }
    
}