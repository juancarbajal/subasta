<?php
require 'init.php';
require_once 'BlockIp.php';
/**
 * Description of mediacion
 *
 * @author luis
 */
class blacklistip
{

    public function __construct() {
        try {
            $blacklistmodel = new BlockIp();
            $blacklistmodel->eliminarIps();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
$blacklistip = new blacklistip();