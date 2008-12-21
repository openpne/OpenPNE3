<?php

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
