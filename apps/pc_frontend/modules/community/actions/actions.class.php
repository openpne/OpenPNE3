<?php

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class communityActions extends sfActions
{
  public $id = 0;
  public $isAdmin = false;
  public $isEditCommunity = false;
  public $isCommunityMember = false;

  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id');

    $this->isCommunityMember = CommunityMemberPeer::isMember($this->getUser()->getMemberId(), $this->id);
    $this->isAdmin = CommunityMemberPeer::isAdmin($this->getUser()->getMemberId(), $this->id);
    $this->isEditCommunity = $this->isAdmin;
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    $this->forward('default', 'module');
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

 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->community = CommunityPeer::retrieveByPk($this->id);
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->pager = new sfPropelPager('Community', 20);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes joinlist action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoinlist($request)
  {
    $this->pager = CommunityPeer::getJoinCommunityListPager($request->getParameter('member_id', $this->getUser()->getMemberId()), $request->getParameter('page', 1));

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes memberList action
  *
  * @param sfRequest $request A request object
  */
  public function executeMemberList($request)
  {
    $this->pager = CommunityPeer::getCommunityMemberListPager($this->id, $request->getParameter('page', 1));

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes join action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoin($request)
  {
    $this->forwardIf($this->isCommunityMember, 'default', 'secure');

    CommunityMemberPeer::join($this->getUser()->getMemberId(), $this->id);
    $this->redirect('community/home?id=' . $this->id);
  }

 /**
  * Executes quit action
  *
  * @param sfRequest $request A request object
  */
  public function executeQuit($request)
  {
    $this->forwardUnless($this->isCommunityMember, 'default', 'secure');

    CommunityMemberPeer::quit($this->getUser()->getMemberId(), $this->id);
    $this->redirect('community/home?id=' . $this->id);
  }
}
