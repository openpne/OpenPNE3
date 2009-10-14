<?php
/**
 */
class NotificationMailTable extends Doctrine_Table
{
  public function getDisabledNotificationNames()
  {
    $result = array();

    $list = $this->createQuery()
      ->select('name')
      ->where('is_enabled = ?', false)
      ->fetchArray();

    foreach ($list as $v)
    {
      $result[] = $v['name'];
    }

    return $result;
  }
}
