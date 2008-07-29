<?php

/**
 * friend actions.
 *
 * @package    OpenPNE
 * @subpackage friend
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class friendActions extends sfActions
{
  public $id = 0;

  public function preExecute()
  {
    $this->getUser()->removeCredential('friend');
    $this->getUser()->removeCredential('non-friend');

    $this->id = $this->getRequestParameter('id');
    if (FriendPeer::isFriend($this->getUser()->getMemberId(), $this->id)) {
      $this->getUser()->addCredential('friend');
    } else {
      $this->getUser()->addCredential('non-friend');
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
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->redirectToHomeIfIdIsNotValid();
    $this->member = MemberPeer::retrieveByPk($this->id);

    return sfView::SUCCESS;
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->pager = FriendPeer::getFriendListPager($request->getParameter('member_id', $this->getUser()->getMemberId()), $request->getParameter('page', 1));

    return sfView::SUCCESS;
  }

 /**
  * Executes link action
  *
  * @param sfRequest $request A request object
  */
  public function executeLink($request)
  {
    $this->redirectToHomeIfIdIsNotValid();
    FriendPeer::link($this->getUser()->getMemberId(), $this->id);
    $this->redirect('friend/home?id=' . $this->id);
  }

 /**
  * Executes unlink action
  *
  * @param sfRequest $request A request object
  */
  public function executeUnlink($request)
  {
    $this->redirectToHomeIfIdIsNotValid();
    FriendPeer::unlink($this->getUser()->getMemberId(), $this->id);
    $this->redirect('friend/home?id=' . $this->id);
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
