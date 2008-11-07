<?php

/**
 * sfOpenPNEFriendAction
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class sfOpenPNEFriendAction extends sfActions
{
  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id', $this->getUser()->getMemberId());
    $this->isFriend = FriendPeer::isFriend($this->getUser()->getMemberId(), $this->id);
    $this->isFriendPreFrom = FriendPrePeer::isFriendPre($this->id, $this->getUser()->getMemberId());
    $this->isFriendPreTo = FriendPrePeer::isFriendPre($this->getUser()->getMemberId(), $this->id);
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
    if ($this->isFriend)
    {
      return sfView::ERROR;
    }

    if ($this->isFriendPreTo)
    {
      return sfView::ERROR;
    }

    $this->redirectToHomeIfIdIsNotValid();

    $friendPre = new FriendPre();
    $friendPre->setMemberIdFrom($this->getUser()->getMemberId());
    $friendPre->setMemberIdTo($this->id);
    $friendPre->save();

    $this->redirect('member/profile?id='.$this->id);
  }

 /**
  * Executes linkAccept action
  *
  * @param sfRequest $request A request object
  */
  public function executeLinkAccept($request)
  {
    if (!$this->isFriendPreFrom)
    {
      return sfView::ERROR;
    }

    $this->redirectToHomeIfIdIsNotValid();

    $friendPre = FriendPrePeer::retrieveByMemberIdToAndMemberIdFrom($this->getUser()->getMemberId(), $this->id);
    $this->forward404Unless($friendPre);

    FriendPeer::link($friendPre->getMemberIdTo(), $friendPre->getMemberIdFrom());
    $friendPre->delete();

    $this->redirect('member/profile?id=' . $this->id);
  }

 /**
  * Executes linkReject action
  *
  * @param sfRequest $request A request object
  */
  public function executeLinkReject($request)
  {
    if (!$this->isFriendPreFrom)
    {
      return sfView::ERROR;
    }

    $this->redirectToHomeIfIdIsNotValid();

    $friendPre = FriendPrePeer::retrieveByMemberIdToAndMemberIdFrom($this->getUser()->getMemberId(), $this->id);
    $this->forward404Unless($friendPre);

    $friendPre->delete();

    $this->redirect('@homepage');
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
  protected function redirectToHomeIfIdIsNotValid()
  {
    $this->redirectUnless($this->id, 'member/home');
    $this->redirectIf(($this->id == $this->getUser()->getMemberId()), 'member/home');
  }
}
