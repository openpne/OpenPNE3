<?php

class RankingPeer
{
  protected static function getDateCriteria(Criteria $criteria, $colname, $day = -1)
  {
    $nowTime = time();
    $fromTime = mktime(0, 0, 0, date("m", $nowTime), date("d", $nowTime) + $day, date("Y", $nowTime));
    $toTime = mktime(0, 0, -1, date("m", $nowTime), date("d", $nowTime) + $day + 1, date("Y", $nowTime));
    $criterion = $criteria->getNewCriterion($colname, $fromTime, Criteria::GREATER_EQUAL);
    $criterion->addAnd($criteria->getNewCriterion($colname, $toTime, Criteria::LESS_EQUAL));
    $criteria->add($criterion);
  }

  /**
  * get friend access ranking
  * @param $max
  * @param $page
  * @return array
  */
  public static function getAccessRanking($max, $page)
  {
    if (!class_exists('AshiatoPeer'))
    {
      throw new LogicException("opAshiatoPlugin hasn't installed");
    }

    $cnt_member_id_to = 'COUNT(*)';
    $c = new Criteria();
    self::getDateCriteria($c, AshiatoPeer::UPDATED_AT);
    $c->addSelectColumn($cnt_member_id_to);
    $c->addSelectColumn(AshiatoPeer::MEMBER_ID_TO);
    $c->addGroupByColumn(AshiatoPeer::MEMBER_ID_TO);
    $c->addDescendingOrderByColumn($cnt_member_id_to);
    $c->setOffset($page * $max);
    $c->setLimit($max);
    $stmt = AshiatoPeer::doSelectStmt($c);

    $list = array();
    $list['rank'] = array();
    $list['count'] = array();
    $list['model'] = array();
    for ($i = 0, $rank = 1, $cnt = -1; $row = $stmt->fetch(PDO::FETCH_NUM); $i++)
    {
      if ($i && ($cnt != $row[0])) { $rank++; }
      $list['count'][$i] = $cnt = $row[0];
      $list['model'][$i] = MemberPeer::retrieveByPK($row[1]);
      $list['rank'][$i] = $rank;
    }
    $list['number'] = $i;
    return $list;
  }

 /**
  * get friend ranking
  * @param $max
  * @param $page
  * @return array
  */
  public static function getFriendRanking($max, $page)
  {
    $cnt_member_id_to = 'COUNT(*)';
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    $c->addJoin(MemberRelationshipPeer::MEMBER_ID_TO, MemberPeer::ID);
    $c->add(MemberPeer::IS_ACTIVE, true);
    $c->addSelectColumn($cnt_member_id_to);
    $c->addSelectColumn(MemberRelationshipPeer::MEMBER_ID_TO);
    $c->addGroupByColumn(MemberRelationshipPeer::MEMBER_ID_TO);
    $c->addDescendingOrderByColumn($cnt_member_id_to);
    $c->setOffset($page * $max);
    $c->setLimit($max);
    $stmt = MemberRelationshipPeer::doSelectStmt($c);

    $list = array();
    $list['rank'] = array();
    $list['count'] = array();
    $list['model'] = array();
    for ($i = 0, $rank = 1, $cnt = -1; $row = $stmt->fetch(PDO::FETCH_NUM); $i++)
    {
      if ($i && ($cnt != $row[0])) { $rank++; }
      $list['count'][$i] = $cnt = $row[0];
      $list['model'][$i] = MemberPeer::retrieveByPK($row[1]);
      $list['rank'][$i] = $rank;
    }
    $list['number'] = $i;
    return $list;
  }

  /**
  * get community ranking
  * @param $max
  * @param $page
  * @return array
  */
  public static function getCommunityRanking($max, $page)
  {
    $cnt_add = 'COUNT(*)';
    $c = new Criteria();
    $c->addSelectColumn($cnt_add);
    $c->addSelectColumn(CommunityMemberPeer::COMMUNITY_ID);
    $c->addGroupByColumn(CommunityMemberPeer::COMMUNITY_ID);
    $c->addDescendingOrderByColumn($cnt_add);
    $c->setOffset($page * $max);
    $c->setLimit($max);
    $stmt = CommunityMemberPeer::doSelectStmt($c);

    $list = array();
    $list['rank'] = array();
    $list['count'] = array();
    $list['model'] = array();
    $list['admin'] = array();
    for ($i = 0, $rank = 1, $cnt = -1; $row = $stmt->fetch(PDO::FETCH_NUM); $i++)
    {
      if ($i && ($cnt != $row[0])) { $rank++; }
      $list['count'][$i] = $cnt = $row[0];
      $list['model'][$i] = CommunityPeer::retrieveByPK($row[1]);
      $list['rank'][$i] = $rank;
      $list['admin'][$i] = CommunityMemberPeer::getCommunityAdmin($row[1]);
      $list['admin'][$i] = MemberPeer::retrieveByPk($list['admin'][$i]->getMemberId());
    }
    $list['number'] = $i;
    return $list;
  }

  /**
  * get community topic comment ranking
  * @param $max
  * @param $page
  * @return array
  */
  public static function getTopicRanking($max, $page)
  {
    if (!class_exists('CommunityTopicCommentPeer'))
    {
      throw new LogicException("opCommunityTopicPlugin hasn't installed");
    }

    $cnt_add = 'COUNT(*)';
    $c = new Criteria();
    self::getDateCriteria($c, CommunityTopicCommentPeer::CREATED_AT);
    $c->addJoin(CommunityTopicCommentPeer::COMMUNITY_TOPIC_ID, CommunityTopicPeer::ID);
    $c->addSelectColumn($cnt_add);
    $c->addSelectColumn(CommunityTopicPeer::COMMUNITY_ID);
    $c->addGroupByColumn(CommunityTopicPeer::COMMUNITY_ID);
    $c->addDescendingOrderByColumn($cnt_add);
    $c->setOffset($page * $max);
    $c->setLimit($max);
    $stmt = CommunityTopicCommentPeer::doSelectStmt($c);

    $list = array();
    $list['rank'] = array();
    $list['count'] = array();
    $list['model'] = array();
    $list['admin'] = array();
    for ($i = 0, $rank = 1, $cnt = -1; $row = $stmt->fetch(PDO::FETCH_NUM); $i++)
    {
      if ($i && ($cnt != $row[0])) { $rank++; }
      $list['count'][$i] = $cnt = $row[0];
      $list['model'][$i] = CommunityPeer::retrieveByPK($row[1]);
      $list['rank'][$i] = $rank;
      $list['admin'][$i] = CommunityMemberPeer::getCommunityAdmin($row[1]);
      $list['admin'][$i] = MemberPeer::retrieveByPk($list['admin'][$i]->getMemberId());
    }
    $list['number'] = $i;
    return $list;
  }
}
