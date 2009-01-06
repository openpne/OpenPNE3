<?php

class communityComponents extends sfComponents
{
  public function executeJoinListBox()
  {
    $this->member = sfContext::getInstance()->getUser()->getMember();
    $c = new Criteria();
    $c->addAscendingOrderByColumn(Propel::getDB()->random(time()));
    $this->row = $this->widget->getConfig('row');
    $this->col = $this->widget->getConfig('col');
    $this->communities = CommunityPeer::retrievesByMemberId($this->member->getId(), $this->row * $this->col, $c);
  }
}
