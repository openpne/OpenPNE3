<?php

class friendComponents extends sfComponents
{
  public function executeFriendListBox()
  {
    $this->member = sfContext::getInstance()->getUser()->getMember();
    $c = new Criteria();
    $c->addAscendingOrderByColumn(Propel::getDB()->random(time()));
    $this->friends = $this->member->getFriends(9, $c);
  }
}
