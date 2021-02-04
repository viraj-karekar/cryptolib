<?php

namespace Drupal\encryptdecrypt\Backend;

use Drupal\encryptdecrypt\Backend\BackendInterface;

/**
 * Class OpenSSLBackend
 * @package Drupal\encryptdecrypt\Backend
 */
class OpenSSLBackend implements BackendInterface {
  /**
   * @var string
   */
  private $method;

  /**
   * @var string
   */
  private $key;

  /**
   * OpenSSLBackend constructor.
   */
  public function __construct() {
    $this->method = 'AES-128-CBC';
    $this->key = md5( "encrypt_decrypt", true);
  }

  /**
   * @inheritDoc
   */
  public function encrypt($text) {
    $cipher_text = openssl_encrypt($text, $this->method, $this->key);
    return base64_encode($cipher_text);
  }

  /**
   * @inheritDoc
   */
  public function decrypt($text) {
    $text_decode = base64_decode($text);
    return openssl_decrypt($text_decode, $this->method, $this->key);
  }

}
