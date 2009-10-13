<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * blog actions.
 *
 * @package    OpenPNE
 * @subpackage blog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class blogActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request t
  */
  public function executeIndex($request)
  {
    $this->blogList = BlogPeer::getBlogListOfAllMember(sfConfig::get('app_blog_action_size'));
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }

 /**
  * Executes friend action
  *
  * @param sfRequest $request A request object
  */
  public function executeFriend($request)
  {
    $this->blogList = BlogPeer::getBlogListOfFriend(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_action_size')
    );
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }

 /**
  * Executes user action
  *
  * @param sfRequest $request A request object
  */
  public function executeUser($request)
  {
    $this->member = $this->getUser()->getMember();
    $this->blogList = BlogPeer::getBlogListOfMember(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_action_size')
    );
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }
 /**
  * Executes profile action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfile($request)
  {
    $this->id = $request->getParameter('id');
    $this->member = MemberPeer::retrieveByPk($this->id);
    if (!$this->member)
    {
      return sfView::ERROR;
    }
    $this->blogList = BlogPeer::getBlogListOfMember(
      $this->id,
      sfConfig::get('app_blog_action_size')
    );
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }
}
