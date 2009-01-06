<?php

/**
 * Subclass for representing a row from the 'community' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Community extends BaseCommunity
{
  public function getImageFileName()
  {
    if ($this->getFile())
    {
      return $this->getFile()->getName();
    }
    return '';
  }

  public function getConfig($configName)
  {
    $config = CommunityConfigPeer::retrieveByNameAndCommunityId($configName, $this->getId());

    if (!$config)
    {
      return null;
    }

    return $config->getValue();
  }

  public function getMembers($limit = null, Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    if (!is_null($limit)) {
      $c->setLimit($limit);
    }
    $c->add(CommunityMemberPeer::COMMUNITY_ID, $this->getId());
    $c->addJoin(MemberPeer::ID, CommunityMemberPeer::MEMBER_ID);
    return MemberPeer::doSelect($c);
  }

}
