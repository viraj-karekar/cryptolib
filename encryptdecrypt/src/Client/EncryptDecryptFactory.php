<?php

namespace Drupal\encryptdecrypt\Client;

/**
 * Class EncryptDecryptFactory
 * @package Drupal\encryptdecrypt\Client
 */
class EncryptDecryptFactory {

  /**
   * @param string $lib_name
   * @return CallClient
   */
  public function getClient($lib_name = 'openssl') {
    return $lib_name == 'mcrypt' ? new CallClient (\Drupal::service('encryptdecrypt.mcrypt')) : new CallClient (\Drupal::service('encryptdecrypt.openssl'));
  }

}
