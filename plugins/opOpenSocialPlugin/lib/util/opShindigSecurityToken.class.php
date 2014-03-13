<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opShindigSecurityToken
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @see        BasicSecurityToken
 */
class opShindigSecurityToken extends BasicSecurityToken
{
  /**
   * @see BasicSecurityToken::createFromToken()
   */
  static public function createFromToken($token, $maxAge)
  {
    return new opShindigSecurityToken($token, $maxAge, SecurityToken::$ANONYMOUS, SecurityToken::$ANONYMOUS, null, null, null, null, null);
  }

  /**
   * @see BasicSecurityToken::createFromValues()
   */
  static public function createFromValues($owner, $viewer, $app, $domain, $appUrl, $moduleId, $containerId)
  {
    return new opShindigSecurityToken(null, null, $owner, $viewer, $app, $domain, $appUrl, $moduleId, $containerId);
  }

  protected function getCrypter() {
    return new opShindigBlobCrypter();
  }
}
