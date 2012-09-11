<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opActivityQueryBuilder
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class opActivityQueryBuilder
{
  protected
    $table,
    $viewerId = null,
    $communityId = null,
    $inactiveIds,
    $include;

  static public function create()
  {
    return new self();
  }

  public function __construct()
  {
    $this->table = Doctrine::getTable('ActivityData');
    $this->inactiveIds = Doctrine::getTable('Member')->getInactiveMemberIds();

    $this->resetInclude();
  }

  public function setViewerId($viewerId)
  {
    $this->viewerId = $viewerId;

    return $this;
  }

  public function setCommunityId($communityId)
  {
    $this->communityId = $communityId;

    return $this;
  }

  public function resetInclude()
  {
    $this->include = array(
      'self' => false,
      'friend' => false,
      'sns' => false,
      'mention' => false,
      'member' => false,
      'community' => false,
    );

    return $this;
  }

  public function includeSelf()
  {
    $this->include['self'] = true;

    return $this;
  }

  public function includeFriends($targetMemberId = null)
  {
    $this->include['friend'] = $targetMemberId ? $targetMemberId : $this->viewerId;

    return $this;
  }

  public function includeSns()
  {
    $this->include['sns'] = true;

    return $this;
  }

  public function includeMentions()
  {
    $this->include['mention'] = true;

    return $this;
  }

  public function includeMember($memberId)
  {
    $this->include['member'] = $memberId;

    return $this;
  }

  public function buildQuery()
  {
    $query = $this->table->createQuery('a')
      ->leftJoin('a.Member');

    $subQuery = array();

    if ($this->include['self'])
    {
      $subQuery[] = $this->buildSelfQuery($query->createSubquery());
    }

    if ($this->include['friend'])
    {
      $subQuery[] = $this->buildFriendQuery($query->createSubquery(), $this->include['friend']);
    }

    if ($this->include['sns'])
    {
      $subQuery[] = $this->buildAllMemberQuery($query->createSubquery());
    }

    if ($this->include['mention'])
    {
      $subQuery[] = $this->buildMentionQuery($query->createSubquery());
    }

    if ($this->include['member'])
    {
      $subQuery[] = $this->buildMemberQueryWithCheckRel($query->createSubquery(), $this->include['member']);
    }

    $subQuery = array_map(array($this, 'trimSubqueryWhere'), $subQuery);

    $query->andWhere(implode(' OR ', $subQuery));

    if (null === $this->communityId)
    {
      $query->addWhere('a.foreign_table IS NULL OR a.foreign_table <> "community"');
    }
    else
    {
      $query
        ->addWhere('a.foreign_table = "community"')
        ->addWhere('a.foreign_id = ?', $this->communityId)
        ->addWhere('EXISTS (FROM CommunityMember cm WHERE cm.member_id = a.member_id AND cm.community_id = ?)', $this->communityId);
    }

    return $query->orderBy('id DESC');
  }

  protected function buildSelfQuery($query)
  {
    return $this->buildMemberQuery($query, $this->viewerId, ActivityDataTable::PUBLIC_FLAG_PRIVATE);
  }

  protected function buildFriendQuery($query, $memberId)
  {
    $friendsQuery = $query->createSubquery()
      ->select('r.member_id_to')
      ->from('MemberRelationship r')
      ->addWhere('r.member_id_from = ?', $memberId)
      ->addWhere('r.is_friend = true');

    return $this->buildMemberQuery($query, $friendsQuery, ActivityDataTable::PUBLIC_FLAG_FRIEND);
  }

  protected function buildAllMemberQuery($query)
  {
    return $this->buildMemberQuery($query, null, ActivityDataTable::PUBLIC_FLAG_SNS);
  }

  protected function buildMemberQuery($query, $memberId = null, $publicFlag = ActivityDataTable::PUBLIC_FLAG_SNS)
  {
    if (is_array($memberId))
    {
      $query->andWhereIn('a.member_id', $memberId);
    }
    elseif ($memberId instanceof Doctrine_Query)
    {
      $query->andWhere('a.member_id IN ('.$memberId->getDql().')');
    }
    elseif (is_scalar($memberId))
    {
      $query->andWhere('a.member_id = ?', $memberId);
    }

    $query->andWhereIn('a.public_flag', $this->table->getViewablePublicFlags($publicFlag));

    return $query;
  }

  protected function buildMemberQueryWithCheckRel($query, $memberId = null)
  {
    $subQuery = array();

    foreach ((array)$memberId as $id)
    {
      if ($this->viewerId === $id)
      {
        $subQuery[] = $this->buildSelfQuery($query->createSubquery());
      }
      elseif (in_array($id, $this->inactiveIds))
      {
        continue;
      }
      else
      {
        $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->viewerId, $id);

        if ($relation && $relation->isFriend())
        {
          $subQuery[] = $this->buildMemberQuery($query->createSubquery(), $id, ActivityDataTable::PUBLIC_FLAG_FRIEND);
        }
        else
        {
          $subQuery[] = $this->buildMemberQuery($query->createSubquery(), $id, ActivityDataTable::PUBLIC_FLAG_SNS);
        }
      }
    }

    if (!empty($subQuery))
    {
      $subQuery = array_map(array($this, 'trimSubqueryWhere'), $subQuery);
      $query->andWhere(implode(' OR ', $subQuery));
    }

    return $query;
  }

  protected function buildMentionQuery($query)
  {
    $friendQuery = $this->buildFriendQuery($query->createSubquery())
      ->andWhereLike('a.template_param', '|'.$this->viewerId.'|');

    $snsQuery = $this->buildAllMemberQuery($query->createSubquery())
      ->andWhereLike('a.template_param', '|'.$this->viewerId.'|');

    $subQuery = array_map(array($this, 'trimSubqueryWhere'), array($friendQuery, $snsQuery));
    $query->andWhere(implode(' OR ', $subQuery));

    return $query;
  }

  protected function trimSubqueryWhere($subquery)
  {
    return '('.implode(' ', $subquery->getDqlPart('where')).')';
  }
}
