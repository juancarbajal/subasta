<?php

class App_Controller_Action_Helper_Clave
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
   * Genera contraseña
   * 
   * @param string $rawPassword
   * @param string $algo Algoritmo usado para generar la contraseña. md5, sha1
   * @return string
   */
  public function generateClave($rawPassword, $algo='sha1')
  {
    $salt = substr(md5(rand(0, 999999) + time()), 6, 5);
    $passw = '';

    if ($algo == 'sha1') {
      $passw = $algo . '$' . $salt . '$' . sha1($salt . $rawPassword);
    } else {
      $passw = $algo . '$' . $salt . '$' . md5($salt . $rawPassword);
    }
    return $passw;
  }

  /**
   * Retorna true si el password es correcto
   * 
   * @param string $rawPassword
   * @param string $encPassword
   * @return bool
   */
  public function checkClave($rawPassword, $encPassword)
  {
    $parts = explode('$', $encPassword);
    if (count($parts) != 3) {
      return false;
    }

    $algo = strtolower($parts[0]);
    $salt = $parts[1];
    $encPass = $parts[2];

    $credentialEnc = '';
    if ($algo == 'sha1') {
      $credentialEnc = sha1($salt . $rawPassword, false);
    } else {
      $credentialEnc = md5($salt . $rawPassword, false);
    }
    return $credentialEnc == $encPass;
  }
}