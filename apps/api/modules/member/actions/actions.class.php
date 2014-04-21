<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class memberActions extends opJsonApiActions
{
  public function executeCommunity(sfWebRequest $request)
  {
    if (isset($request['member_id']))
    {
      $memberId = $request['member_id'];
    }
    elseif (isset($request['id']))
    {
      $memberId = $request['id'];
    }
    else
    {
      $memberId = $this->getUser()->getMemberId();
    }

    $query = Doctrine::getTable('Community')->createQuery('c')
      ->innerJoin('c.CommunityMember cm WITH cm.is_pre = false AND cm.member_id = ?', $memberId)
      ->limit(sfConfig::get('op_json_api_limit', 20));

    if (isset($request['keyword']))
    {
      $query->whereLike('c.name', $request['keyword']);
    }

    $this->communities = $query->execute();

    $this->setTemplate('array', 'community');
  }

  public function executeSearch(sfWebRequest $request)
  {
    $query = Doctrine::getTable('Member')->createQuery('m')
      ->andWhere('m.is_active = true');

    if (isset($request['target']))
    {
      if (!isset($request['target_id']))
      {
        $this->forward400('target_id parameter not specified.');
      }
      $targetId = $request['target_id'];

      if ('friend' === $request['target'])
      {
        $query->andWhere('EXISTS (FROM MemberRelationship mr WHERE m.id = mr.member_id_to AND mr.member_id_from = ? AND mr.is_friend = true)', $targetId);
      }
      if ('community' === $request['target'])
      {
        $query->andWhere('EXISTS (FROM CommunityMember cm WHERE m.id = cm.member_id AND cm.community_id = ?)', $targetId);
      }
    }

    if (isset($request['keyword']))
    {
      $query->andWhereLike('m.name', $request['keyword']);
    }

    $size = 20;
    $pager = new opNonCountQueryPager('Member', $size);
    $query = $query->orderBy('id desc');
    $pager->setQuery($query);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->members = $pager->getResults();
    $this->pager = $pager;

    $this->setTemplate('array');
  }

  public function executeFriendAccept(sfWebRequest $request)
  {
    if (isset($request['member_id']))
    {
      $targetMemberId = $request['member_id'];
    }
    elseif (isset($request['id']))
    {
      $targetMemberId = $request['id'];
    }
    else
    {
      $this->forward400('member_id parameter not specified.');
    }

    $memberId = $this->getUser()->getMemberId();

    $preRequest = Doctrine::getTable('MemberRelationship')->createQuery()
      ->addWhere('member_id_from = ?', $targetMemberId)
      ->addWhere('member_id_to = ?', $memberId)
      ->addWhere('is_friend_pre = true')
      ->fetchOne();

    if (!$preRequest)
    {
      $this->forward404('Invalid member_id.');
    }

    if (!isset($request['reject']))
    {
      $preRequest->setFriend();
    }
    else
    {
      $preRequest->removeFriendPre();
    }

    $preRequest->free(true);

    return $this->renderJSON(array('status' => 'success'));
  }

  public function executeFriendRequest(sfWebRequest $request)
  {
    $memberId = $this->getUser()->getMemberId();

    if (isset($request['member_id']))
    {
      $targetMemberId = $request['member_id'];
    }
    elseif (isset($request['id']))
    {
      $targetMemberId = $request['id'];
    }
    else
    {
      $this->forward400('member_id parameter not specified.');
    }

    if ($memberId === $targetMemberId)
    {
      $this->forward400('Friend request to myself is not allowed.');
    }

    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($memberId, $targetMemberId);
    if (!$relation)
    {
      $relation = new MemberRelationship();
      $relation->setMemberIdFrom($memberId);
      $relation->setMemberIdTo($targetMemberId);
    }

    if (isset($request['unlink']))
    {
      if (!$relation->isFriend())
      {
        $this->forward400('This member is not your friend.');
      }

      $relation->removeFriend();
    }
    else
    {
      if ($relation->isAccessBlocked())
      {
        $this->forward403('Friend request is blocked.');
      }
      if ($relation->isFriend())
      {
        $this->forward400('This member already belongs to your friend.');
      }
      if ($relation->isFriendPreFrom())
      {
        $this->forward400('Friend request is already sent.');
      }

      $relation->setFriendPre();
    }

    $relation->free(true);

    return $this->renderJSON(array('status' => 'success'));
  }
}
