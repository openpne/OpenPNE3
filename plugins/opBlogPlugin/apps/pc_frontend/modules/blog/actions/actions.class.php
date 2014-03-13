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
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
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
    $this->blogRssCacheList = Doctrine::getTable('BlogRssCache')->getAllMembers(
      sfConfig::get('app_blog_action_size')
    );

    if (!count($this->blogRssCacheList))
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
    $this->blogRssCacheList = Doctrine::getTable('BlogRssCache')->getFriendBlogListByMemberId(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_action_size')
    );

    if (!count($this->blogRssCacheList))
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
    if (!$request->hasParameter('id'))
    {
      $this->member = $this->getUser()->getMember();
    }
    else
    {
      $this->member = $this->getRoute()->getObject();

      $relation = Doctrine::getTable('MemberRelationship')
        ->retrieveByFromAndTo($this->getUser()->getMemberId(), $this->member->getId());

      $this->forward404If($relation && $relation->isAccessBlocked());
    }

    $this->blogRssCacheList = Doctrine::getTable('BlogRssCache')->findByMemberId(
      $this->member->getId(),
      sfConfig::get('app_blog_action_size')
    );

    if (!count($this->blogRssCacheList))
    {
      return sfView::ALERT;
    }
  }
}
