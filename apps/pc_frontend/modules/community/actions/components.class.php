<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class communityComponents extends opCommunityComponents
{
  public function executeJoinListBox(opWebRequest $request)
  {
    if ($request->hasParameter('id') && $request->getParameter('module') == 'member' && $request->getParameter('action') == 'profile')
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
    $this->row = $this->gadget->getConfig('row');
    $this->col = $this->gadget->getConfig('col');
    $this->crownIds = Doctrine::getTable('CommunityMember')->getCommunityIdsOfAdminByMemberId($this->member->getId());
    $this->communities = Doctrine::getTable('Community')->retrievesByMemberId($this->member->getId(), $this->row * $this->col, true);
  }

  public function executeSmtCommunityListBox(opWebRequest $request)
  {
    $this->id = $request->getParameter('id');

    $memberId = $this->getUser()->getMemberId();
    $communityMember = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($memberId, $this->id);

    if ($communityMember)
    {
      $this->isCommunityMember = !$communityMember->getIsPre();
      $this->isCommunityPreMember = $communityMember->getIsPre();

      $positions = Doctrine::getTable('CommunityMemberPosition')->getPositionsByMemberIdAndCommunityId($memberId, $this->id);
      $this->isAdmin = in_array('admin', $positions);
      $this->isSubAdmin = in_array('sub_admin', $positions);
      $this->isEditCommunity = $this->isAdmin || $this->isSubAdmin;
    }
    else
    {
      $this->isCommunityMember = false;
      $this->isCommunityPreMember = false;
      $this->isAdmin = false;
      $this->isSubAdmin = false;
      $this->isEditCommunity = false;
    }

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->communityAdmin = $this->community->getAdminMember();
    $this->communitySubAdmins = $this->community->getSubAdminMembers();
  }

  public function executeSmtCommunityMemberJoinListBox(opWebRequest $request)
  {
    $this->id = $request->getParameter('id');
    $this->community = Doctrine::getTable('Community')->find($this->id);
  }
}
