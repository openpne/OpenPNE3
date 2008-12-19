<?php

class communityComponents extends sfComponents
{
  public function executeJoinListBox()
  {
    $this->member = sfContext::getInstance()->getUser()->getMember();
    $c = new Criteria();
    $c->addAscendingOrderByColumn(Propel::getDB()->random(time()));
    $this->communities = CommunityPeer::retrievesByMemberId($this->member->getId(), 9, $c);
  }
}
