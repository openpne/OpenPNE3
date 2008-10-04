<?php

/**
 * friend actions.
 *
 * @package    OpenPNE
 * @subpackage friend
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class friendActions extends sfActions
{
  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id', $this->getUser()->getMemberId());
    $this->isFriend = FriendPeer::isFriend($this->getUser()->getMemberId(), $this->id);
    if ($this->id == $this->getUser()->getMemberId()) {
      sfConfig::set('sf_navi_type', 'default');
    }
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
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->pager = FriendPeer::getFriendListPager($this->id, $request->getParameter('page', 1));

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes link action
  *
  * @param sfRequest $request A request object
  */
  public function executeLink($request)
  {
    if ($this->isFriend) {
      return sfView::ERROR;
    }
    $this->redirectToHomeIfIdIsNotValid();
    FriendPeer::link($this->getUser()->getMemberId(), $this->id);
    $this->redirect('member/profile?id=' . $this->id);
  }

 /**
  * Executes unlink action
  *
  * @param sfRequest $request A request object
  */
  public function executeUnlink($request)
  {
    if (!$this->isFriend) {
      return sfView::ERROR;
    }
    $this->redirectToHomeIfIdIsNotValid();
    FriendPeer::unlink($this->getUser()->getMemberId(), $this->id);
    $this->redirect('member/profile?id=' . $this->id);
  }

 /**
  * Redirects to your home if ID is yours or it is empty.
  */
  private function redirectToHomeIfIdIsNotValid()
  {
    $this->redirectUnless($this->id, 'member/home');
    $this->redirectIf(($this->id == $this->getUser()->getMemberId()), 'member/home');
  }
}
