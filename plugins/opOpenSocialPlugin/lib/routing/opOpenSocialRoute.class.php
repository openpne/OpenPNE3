<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocial route
 *
 *
 * @package  opOpenSocialPlugin
 * @author   Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialRoute extends sfRoute implements opAPIRouteInterface
{
  const
    API_NAME = 'opensocial',
    API_CAPTION = "OpenSocial API: get member / member's friend item(s)";

  public function getAPIName()
  {
    return self::API_NAME;
  }

  public function getAPICaption()
  {
    return self::API_CAPTION;
  }
}
