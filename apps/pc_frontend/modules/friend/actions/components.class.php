<?php

class friendComponents extends sfComponents
{
  public function executeFriendListBox()
  {
    $this->member = sfContext::getInstance()->getUser()->getMember();
    $c = new Criteria();
    $c->addAscendingOrderByColumn(Propel::getDB()->random(time()));
    $this->row = $this->widget->getConfig('row');
    $this->col = $this->widget->getConfig('col');
    $this->friends = $this->member->getFriends($this->row * $this->col, $c);
  }
}
