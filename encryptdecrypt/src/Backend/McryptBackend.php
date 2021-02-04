<?php

namespace Drupal\encryptdecrypt\Backend;

use Drupal\encryptdecrypt\Backend\BackendInterface;

/**
 * Class McryptBackend
 * @package Drupal\encryptdecrypt\Backend
 */
class McryptBackend implements BackendInterface {

  /**
   * @var string
   */
  private $key;

  /**
   * @var false|string
   */
  private $iv;

  /**
   * McryptBackend constructor.
   */
  public function __construct() {
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $this->key = md5( "encrypt_decrypt", true);
    $this->iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);
  }

  /**
   * @inheritDoc
   */
  public function encrypt($text) {
    $cipher_text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $text, MCRYPT_MODE_CBC, $this->iv);
    return base64_encode($cipher_text);
  }

  /**
   * @inheritDoc
   */
  public function decrypt($text){
    $text_decode = base64_decode($text);
    return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $text_decode, MCRYPT_MODE_CBC, $this->iv);
  }

}
