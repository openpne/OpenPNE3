<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityConfigPeer extends BaseCommunityConfigPeer
{
  public static function retrieveByNameAndCommunityId($name, $communityId)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);
    $c->add(self::COMMUNITY_ID, $communityId);

    $result = self::doSelectOne($c);

    return $result;
  }
}
