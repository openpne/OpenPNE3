<?php
/**
 */
class OpenIDTrustLogTable extends Doctrine_Table
{
  public function findByOpenID($openid, $memberId)
  {
    return $this->createQuery()
      ->where('uri_key = ?', md5($openid))
      ->andWhere('member_id = ?', $memberId)
      ->fetchOne();
  }

  public function log($openid, $memberId)
  {
    $log = $this->findByOpenID($openid, $memberId);
    if (!$log)
    {
      $log = new OpenIDTrustLog();
      $log->uri = $openid;
      $log->uri_key = md5($openid);
      $log->member_id = $memberId;
    }
    else
    {
      $log->state(Doctrine_Record::STATE_DIRTY);
    }

    $log->save();

    return $log;
  }

  public function getListPager($memberId, $page = 1, $size = 20)
  {
    $pager = new sfDoctrinePager('OpenIDTrustLog', $size);

    $q = $this->createQuery()->andWhere('member_id = ?', $memberId);

    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
