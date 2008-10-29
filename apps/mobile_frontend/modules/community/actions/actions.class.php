<?php

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class communityActions extends sfActions
{
  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id');

    $this->isCommunityMember = CommunityMemberPeer::isMember($this->getUser()->getMemberId(), $this->id);
    $this->isAdmin = CommunityMemberPeer::isAdmin($this->getUser()->getMemberId(), $this->id);
    $this->isEditCommunity = $this->isAdmin;
  }

 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->community = CommunityPeer::retrieveByPk($this->id);
    $this->forward404Unless($this->community, 'Undefined community.');
  }

 /**
  * Executes joinlist action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoinlist($request)
  {
    $memberId = $request->getParameter('member_id', $this->getUser()->getMemberId());

    $this->member = MemberPeer::retrieveByPK($memberId);
    $this->forward404Unless($this->member);

    $this->pager = CommunityPeer::getJoinCommunityListPager($memberId, $request->getParameter('page', 1));

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    if ($this->id && !$this->isEditCommunity) {
      $this->forward('default', 'secure');
    }

    $this->community = CommunityPeer::retrieveByPk($this->id);
    $this->form = new CommunityForm($this->community);

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('community'));
      if ($this->form->isValid()) {
        $community = $this->form->save();

        $this->redirect('community/home?id=' . $community->getId());
      }
    }
  }
}
