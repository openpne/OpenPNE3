<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class friendComponents extends sfOpenPNEFriendComponents
{
  public function executeFriendListBox()
  {
    $this->member = sfContext::getInstance()->getUser()->getMember();
    $this->row = $this->gadget->getConfig('row');
    $this->col = $this->gadget->getConfig('col');
    $this->friends = $this->member->getFriends($this->row * $this->col, true);
  }
}
