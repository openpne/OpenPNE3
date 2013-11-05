<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class memberComponents extends opMemberComponents
{
  public function executeProfileListBox(opWebRequest $request)
  {
    if ($request->hasParameter('id'))
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
  }

  public function executeSmtProfileListBox(opWebRequest $request)
  {
    if ($request->hasParameter('id'))
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
  }

  public function executeSmtMemberFriendListBox(opWebRequest $request)
  {
    if ($request->hasParameter('id') && 'profile' !== sfContext::getInstance()->getActionName())
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
  }

  public function executeSmtMemberJoinCommunityListBox(opWebRequest $request)
  {
    if ($request->hasParameter('id') && 'profile' !== sfContext::getInstance()->getActionName())
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
  }

}
