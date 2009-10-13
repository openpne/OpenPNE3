<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * invitefriend actions.
 *
 * @package    OpenPNE
 * @subpackage invitefriend
 * @author     Masato Nagasawa <nagasawa@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class invitefriendActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    if (!$request->hasParameter('id'))
    {
      return sfView::ERROR;
    }
    $this->id = $request->getParameter('id');

    $this->member = MemberPeer::retrieveByPk($this->id);
    if (!$this->member)
    {
      return sfView::ERROR;
    }

    $relation = MemberRelationshipPeer::retrieveByFromAndTo($this->id, $this->getUser()->getMemberId());
    if (!$relation || !$relation->getIsFriend())
    {
      return sfView::ERROR;
    }

    sfConfig::set('sf_nav_type', 'friend');

    $this->receiveMembers = InviteFriendPeer::getNotFriendMembers($this->getUser()->getMemberId(), $this->id);
    if (!$this->receiveMembers)
    {
      return sfView::ALERT;
    }

    $this->form = new InviteFriendForm(
      array(),
      array('name' => $this->member->getName(), 'members' => $this->receiveMembers)
    );

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('invite_mail'));
      if ($this->form->isValid())
      {
        $postdata = $request->getParameter('invite_mail');
        InviteFriendPeer::sendMessageByList(
          $this->getUser()->getMemberId(),
          $postdata['introduce_to'],
          $postdata['message'],
          $this->id
        );
        $this->redirect('member/profile?id=' . $this->id);
      }
    }

    return sfView::INPUT;
  }
}
