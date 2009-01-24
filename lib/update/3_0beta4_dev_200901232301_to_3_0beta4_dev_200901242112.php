<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opUpdate_3_0beta4_dev_200901232301_to_3_0beta4_dev_200901242112 extends opUpdate
{
  public function update()
  {
    $c = new Criteria();
    $c->add(NavigationPeer::URI, 'friend/link');
    $c->addOr(NavigationPeer::URI, 'friend/unlink');
    NavigationPeer::doDelete($c);
  }
}
