<?php

class App_Controller_Action_Helper_Fecha
    extends Zend_Controller_Action_Helper_Abstract
{
    public function formatDb($fecha, $fec_ini=true, $diasMax='')
    {
        $horaMin = ' 00:00:00';
        $horaMax = ' 23:59:59';
        if (!empty($fecha)) {
            $date = Zend_Locale_Format::getDate($fecha, array('date_format' => 'dd/MM/yy'));
            $minSecond = ($fec_ini)?$horaMin:$horaMax;
            $return = $date['year'].'-'.$date['month'].'-'.$date['day'].$minSecond;
        } else {
            if (!empty($diasMax)) {
                $date = date("Y-m-d");
                $minSecond = ($fec_ini)?$horaMin:$horaMax;
                $return = (date("Y-m-d", strtotime("$date -$diasMax day")).$minSecond);
            } else {
                $return = '';
            }            
        }
        
        return $return;
    }
}