<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class communityActions extends opJsonApiActions
{
  public function executeSearch(sfWebRequest $request)
  {
    $query = Doctrine::getTable('Community')->createQuery();

    if (isset($request['keyword']))
    {
      $query->andWhereLike('name', $request['keyword']);
    }

    $this->communities = $query
      ->limit(sfConfig::get('op_json_api_limit', 20))
      ->execute();

    $this->setTemplate('array');
  }

  public function executeMember(sfWebRequest $request)
  {
    if (isset($request['community_id']))
    {
      $communityId = $request['community_id'];
    }
    elseif (isset($request['id']))
    {
      $communityId = $request['id'];
    }
    else
    {
      $this->forward400('community_id parameter not specified.');
    }

    $this->members = Doctrine::getTable('Member')->createQuery('m')
      ->addWhere('EXISTS (FROM CommunityMember cm WHERE m.id = cm.member_id AND cm.is_pre = false AND cm.community_id = ?)', $communityId)
      ->limit(sfConfig::get('op_json_api_limit', 20))
      ->execute();

    $this->setTemplate('array', 'member');
  }

  public function executeJoin(sfWebRequest $request)
  {
    $memberId = $this->getUser()->getMemberId();

    if (isset($request['community_id']))
    {
      $communityId = $request['community_id'];
    }
    elseif (isset($request['id']))
    {
      $communityId = $request['id'];
    }
    else
    {
      $this->forward400('community_id parameter not specified.');
    }

    $community = Doctrine::getTable('Community')->find($communityId);
    if (!$community)
    {
      $this->forward404('This community does not exist.');
    }

    $communityJoinPolicy = $community->getConfig('register_policy');

    $communityMember = Doctrine::getTable('CommunityMember')
      ->retrieveByMemberIdAndCommunityId($memberId, $community->getId());

    if ($request['leave'])
    {
      if (!$communityMember || $communityMember->hasPosition('admin'))
      {
        $this->forward400('You can\'t leave this community.');
      }

      Doctrine::getTable('CommunityMember')->quit($memberId, $communityId);
    }
    else
    {
      if ($communityMember)
      {
        if ($communityMember->getIsPre())
        {
          $this->forward400('You are already sent request to join this community.');
        }
        else
        {
          $this->forward400('You are already this community\'s member.');
        }
      }

      Doctrine::getTable('CommunityMember')->join($memberId, $communityId, $communityJoinPolicy);
    }

    return $this->renderJSON(array('status' => 'success'));
  }
}
