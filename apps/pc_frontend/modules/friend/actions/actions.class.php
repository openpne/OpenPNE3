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
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->redirectIf(($id == $this->getUser()->getMemberId()), 'member/home');

    $this->member = MemberPeer::retrieveByPk($id);
    $this->isFriend = FriendPeer::isFriend($this->getUser()->getMemberId(), $id);

    return sfView::SUCCESS;
  }

 /**
  * Executes link action
  *
  * @param sfRequest $request A request object
  */
  public function executeLink($request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->redirectIf(($id == $this->getUser()->getMemberId()), 'member/home');
    $this->redirectIf(FriendPeer::isFriend($this->getUser()->getMemberId(), $id), 'friend/home?id=' . $id);

    FriendPeer::link($this->getUser()->getMemberId(), $id);
    $this->redirect('friend/home?id=' . $id);
  }

 /**
  * Executes unlink action
  *
  * @param sfRequest $request A request object
  */
  public function executeUnlink($request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->redirectIf(($id == $this->getUser()->getMemberId()), 'member/home');
    $this->redirectUnless(FriendPeer::isFriend($this->getUser()->getMemberId(), $id), 'friend/home?id=' . $id);

    FriendPeer::unlink($this->getUser()->getMemberId(), $id);
    $this->redirect('friend/home?id=' . $id);
  }
}
