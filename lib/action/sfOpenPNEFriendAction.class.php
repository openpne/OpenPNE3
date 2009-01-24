<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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

    $this->relation = MemberRelationshipPeer::retrieveByFromAndTo($this->getUser()->getMemberId(), $this->id);
    if (!$this->relation) {
      $this->relation = new MemberRelationship();
      $this->relation->setMemberIdFrom($this->getUser()->getMemberId());
      $this->relation->setMemberIdTo($this->id);
    }
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->redirectIf($this->relation->isAccessBlocked(), '@error');

    $this->pager = MemberRelationshipPeer::getFriendListPager($this->id, $request->getParameter('page', 1));

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
    $this->redirectIf($this->relation->isAccessBlocked(), '@error');

    if ($this->relation->isFriend())
    {
      $this->getUser()->setFlash('error', 'This member already belongs to my friends.');
      $this->redirect('member/profile?id='.$this->id);
    }
    if ($this->relation->isFriendPreFrom())
    {
      $this->getUser()->setFlash('error', 'My friends request is already sent.');
      $this->redirect('member/profile?id='.$this->id);
    }

    $this->redirectToHomeIfIdIsNotValid();

    $this->relation->setFriendPre();

    $this->redirect('member/profile?id='.$this->id);
  }

 /**
  * Executes linkAccept action
  *
  * @param sfRequest $request A request object
  */
  public function executeLinkAccept($request)
  {
    if (!$this->relation->isFriendPreTo())
    {
      return sfView::ERROR;
    }

    $this->redirectToHomeIfIdIsNotValid();

    $this->relation->setFriend();

    $this->redirect('member/profile?id='.$this->id);
  }

 /**
  * Executes linkReject action
  *
  * @param sfRequest $request A request object
  */
  public function executeLinkReject($request)
  {
    if (!$this->relation->isFriendPreTo())
    {
      return sfView::ERROR;
    }

    $this->redirectToHomeIfIdIsNotValid();

    $this->relation->removeFriend();

    $this->redirect('@homepage');
  }

 /**
  * Executes unlink action
  *
  * @param sfRequest $request A request object
  */
  public function executeUnlink($request)
  {
    if (!$this->relation->isFriend())
    {
      $this->getUser()->setFlash('error', 'This member is not your friend.');
      $this->redirect('friend/manage');
    }
    $this->redirectToHomeIfIdIsNotValid();
    $this->relation->removeFriend();
    $this->redirect('friend/manage');
  }

 /**
  * Redirects to your home if ID is yours or it is empty.
  */
  protected function redirectToHomeIfIdIsNotValid()
  {
    $this->redirectUnless($this->id, 'member/home');
    $this->redirectIf(($this->id == $this->getUser()->getMemberId()), 'member/home');
  }

 /**
  * Executes manage action
  */
  public function executeManage($request)
  {
    $this->pager = MemberRelationshipPeer::getFriendListPager($this->getUser()->getMemberId(), $request->getParameter('page', 1));

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }
}
