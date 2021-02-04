<?php
namespace Drupal\encryptdecrypt\Backend;
/**
 * EncryptionRepository interface
 */
interface BackendInterface {

  /**
   * To encrypt the string.
   *
   * @param $text
   * @return string
   */
  public function encrypt($text);

  /**
   * To decrypt the string.
   *
   * @param $text
   * @return string
   */
  public function decrypt($text);

}
