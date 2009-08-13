<?php
/**
 */
class OAuthAbstractTokenTable extends Doctrine_Table
{
  public function findByKeyString($key, $type = 'request', $q = null)
  {
    if (!$q)
    {
      $q = $this->createQuery();
    }

    return $q->andWhere('key_string = ?', $key)
      ->andWhere('type = ?', $type)
      ->fetchOne();
  }
}
