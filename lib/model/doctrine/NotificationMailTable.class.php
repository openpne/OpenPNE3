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
      ->execute(array(), Doctrine::HYDRATE_NONE);

    foreach ($list as $v)
    {
      $result[] = $v[0];
    }

    return $result;
  }
}
