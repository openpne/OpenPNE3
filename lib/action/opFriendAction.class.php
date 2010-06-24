<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opFriendAction
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@php.net>
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class opFriendAction extends sfActions
{
  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id', $this->getUser()->getMemberId());

    $this->relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->getUser()->getMemberId(), $this->id);
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

    if (!$this->size)
    {
      $this->size = 20;
    }
    $this->pager = Doctrine::getTable('MemberRelationship')->getFriendListPager($this->id, $request->getParameter('page', 1), $this->size);

    if (!$this->pager)
    {
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
    $this->redirectUnless(opConfig::get('enable_friend_link'), '@error');
    $this->redirectIf($this->relation->isAccessBlocked(), '@error');

    if ($this->relation->isFriend())
    {
      $this->getUser()->setFlash('error', 'This member already belongs to %my_friend%.');
      $this->getUser()->setFlash('error_params', array('%my_friend%' => Doctrine::getTable('SnsTerm')->get('my_friend')->pluralize()));
      $this->redirect('@member_profile?id='.$this->id);
    }
    if ($this->relation->isFriendPreFrom())
    {
      $this->getUser()->setFlash('error', '%Friend% request is already sent.');
      $this->redirect('@member_profile?id='.$this->id);
    }

    $this->form = new FriendLinkForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('friend_link'));
      if ($this->form->isValid())
      {
        $this->getUser()->setFlash('notice', 'You have requested %friend% link.');
        $this->redirectToHomeIfIdIsNotValid();
        $this->relation->setFriendPre();
        $this->redirect('@member_profile?id='.$this->id);
      }
    }

    $this->member = Doctrine::getTable('Member')->find($this->id);

    return sfView::INPUT;
  }

 /**
  * Executes unlink action
  *
  * @param sfRequest $request A request object
  */
  public function executeUnlink($request)
  {
    $this->redirectToHomeIfIdIsNotValid();
    if (!$this->relation->isFriend())
    {
      $this->getUser()->setFlash('error', 'This member is not your %friend%.');
      $this->redirect('friend/manage');
    }

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $this->relation->removeFriend();
      $this->redirect('friend/manage');
    }

    $this->member = Doctrine::getTable('Member')->find($this->id);
    return sfView::INPUT;
  }

 /**
  * Redirects to your home if ID is yours or it is empty.
  */
  protected function redirectToHomeIfIdIsNotValid()
  {
    $this->redirectUnless($this->id, '@homepage');
    $this->redirectIf(($this->id == $this->getUser()->getMemberId()), '@homepage');
  }

 /**
  * Executes manage action
  */
  public function executeManage($request)
  {
    $this->pager = Doctrine::getTable('MemberRelationship')->getFriendListPager($this->getUser()->getMemberId(), $request->getParameter('page', 1));

    if (!$this->pager) {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

  /**
   * Executes show member iamges action
   * 
   * @param sfRequest $request A request object
   */
  public function executeShowImage($request)
  {
    $this->forward404Unless($this->id);

    $this->member = Doctrine::getTable('Member')->find($this->id);
    $this->forward404Unless($this->member, 'Undefined member.');

    return sfView::SUCCESS;
  }

 /**
  * Executes show friend activities action
  * 
  * @param sfWebRequest $request A request object
  */
  public function executeShowActivity($request)
  {
    if (!isset($this->size))
    {
      $this->size = 20;
    }

    $page = $request->getParameter('page', 1);
    if ($page == 1 && opConfig::get('is_allow_post_activity'))
    {
      $activityData = new ActivityData();
      $activityData->setBody($request->getParameter('body'));
      $this->form = new ActivityDataForm($activityData);
    }

    $this->pager = Doctrine::getTable('ActivityData')->getFriendActivityListPager(null, $page, $this->size);
  }
}
