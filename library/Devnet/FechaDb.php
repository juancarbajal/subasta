<?php

class Devnet_FechaDb
{
    /*
     * Ander
     * Para la bd
     * 
     */
    public function convert($fecha,$fec_ini=true)
    {
        if(!empty($fecha)){
//        $date = new Zend_Date($fecha,array('date_format' => 'dd/MM/yy'));
            $date = Zend_Locale_Format::getDate($fecha,array('date_format' => 'dd/MM/yy'));       
//        var_dump($date);exit;
//        echo $date->get('yyyy-MM-dd');exit;
//        var_dump($date);exit;
            $minSecond = ($fec_ini)?'00:00:00':'23:59:59';
            $return = $date['year'].'-'.$date['month'].'-'.$date['day'].' '.$minSecond;
        } else {
            $return='';
        }
        
        return $return;
    }
}
