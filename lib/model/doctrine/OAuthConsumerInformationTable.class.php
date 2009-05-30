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
}
