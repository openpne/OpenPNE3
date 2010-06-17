<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This utility class is just for using utility functions of OpenPNE2 to upgrade.
 *
 * Some methods in this class is just ported from OpenPNE2.
 * You may be irritated by K&R Coding Style and old-style PHP coding, me too :)
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE2Util
{
  static public function &get_crypt_blowfish()
  {
      static $singleton;
      if (empty($singleton)) {
          if (OPENPNE_USE_OLD_CRYPT_BLOWFISH) {
              include_once 'Crypt/BlowfishOld.php';
              $singleton = new Crypt_BlowfishOld(ENCRYPT_KEY);
          } else {
              include_once 'Crypt/Blowfish.php';
              $singleton = new Crypt_Blowfish(ENCRYPT_KEY);
          }
      }
      return $singleton;
  }

  static public function t_encrypt($str)
  {
      if (!$str) return '';

      $bf =& self::get_crypt_blowfish();
      $str = $bf->encrypt($str);

      //base64
      $str = base64_encode($str);

      return $str;
  }

  static public function t_decrypt($str)
  {
      if (!$str) return '';

      //base64
      $str = base64_decode($str);

      $bf =& self::get_crypt_blowfish();
      return rtrim($bf->decrypt($str));
  }
}
