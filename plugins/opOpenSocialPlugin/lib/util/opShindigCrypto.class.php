<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

include_once('Crypt/Blowfish.php');

/**
 * opShindigCrypto
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opShindigCrypto
{
  public static function encrypt($key, $text)
  {
    if (extension_loaded('mcrypt'))
    {
      return Crypto::aes128cbcEncrypt($key, $text);
    }
    $iv = substr(md5(uniqid(rand(), true)), 0, 8);
    $blowfish = Crypt_Blowfish::factory('cbc', $key, $iv);
    $encrypted = $blowfish->encrypt(base64_encode($text));
    return $iv.$encrypted;
  }

  public static function decrypt($key, $text)
  {
    if(extension_loaded('mcrypt'))
    {
      return Crypto::aes128cbcDecrypt($key, $text);
    }
    $iv =  substr($text, 0, 8);
    $encrypted = substr($text, 8, strlen($text));
    $blowfish = Crypt_Blowfish::factory('cbc', $key, $iv);
    return base64_decode($blowfish->decrypt($encrypted));
  }
}
