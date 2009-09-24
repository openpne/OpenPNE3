<?php
/**
 */
class OAuthConsumerInformationTable extends Doctrine_Table
{
  public function findByKeyString($key)
  {
    return $this->createQuery()
      ->where('key_string = ?', $key)
      ->fetchOne();
  }

  public function getListPager($memberId = null, $page = 1, $size = 20)
  {
    $pager = new sfDoctrinePager('OAuthConsumerInformation', $size);

    if ($memberId)
    {
      $subquery = Doctrine::getTable('OAuthMemberToken')->createQuery('t')
        ->select('t.oauth_consumer_id')
        ->where('t.member_id = ?')
        ->andWhere('t.type = ?');

      $q = $this->createQuery()->andWhere('member_id = ?', $memberId)
        ->orWhere('id IN ('.$subquery->getDql().')', array($memberId, 'access'));

      $pager->setQuery($q);
    }

    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
