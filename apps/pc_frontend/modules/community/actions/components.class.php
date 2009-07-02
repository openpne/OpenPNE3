<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class communityComponents extends sfOpenPNECommunityComponents
{
  public function executeJoinListBox($request)
  {
    if ($request->hasParameter('id') && $request->getParameter('module') == 'member' && $request->getParameter('action') == 'profile')
    {
      $this->member = MemberPeer::retrieveByPk($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
    $c = new Criteria();
    $c->addAscendingOrderByColumn(Propel::getDB()->random(time()));
    $this->row = $this->gadget->getConfig('row');
    $this->col = $this->gadget->getConfig('col');
    $this->crownIds = CommunityMemberPeer::getCommunityIdsOfAdminByMemberId($this->member->getId());
    $this->communities = CommunityPeer::retrievesByMemberId($this->member->getId(), $this->row * $this->col, $c);
  }
}
