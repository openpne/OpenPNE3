<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
