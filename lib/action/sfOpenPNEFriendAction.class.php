<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

    if ($this->relation->isFriend() || $this->relation->isFriendPreFrom())
    {
      return sfView::ERROR;
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
    if (!$this->relation->isFriend()) {
      return sfView::ERROR;
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
