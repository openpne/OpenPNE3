<?php
/**
 */
class OAuthTokenManagerTable extends Doctrine_Table
{
  public function findByKeyString($key, $type = 'request')
  {
    return $this->createQuery()
      ->where('key_string = ?', $key)
      ->andWhere('type = ?', $type)
      ->fetchOne();
  }
}
