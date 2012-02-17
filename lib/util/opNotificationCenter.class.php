<?php

class opNotificationCenter
{
  static public function notify(Member $from, Member $to, $body, array $options = null)
  {
    $notificationItem = array(
      'id' => microtime(),
      'body' => $body,
      'member_id_from' => $from->getId(),
      'created_at' => time(),
      'unread' => true,
      'category' => $options['category'] ? $options['category'] : 'other',
      'url' => $options['url'] ? $options['url'] : null,
      'icon_url' => $options['icon_url'] ? $options['icon_url'] : null,
    );

    $notificationObject = Doctrine::getTable('MemberConfig')
      ->findOneByMemberIdAndName($to->getId(), 'notification_center');

    if (!$notificationObject)
    {
      $notificationObject = new MemberConfig();
      $notificationObject->setMemberId($to->getId());
      $notificationObject->setName('notification_center');

      $notifications = array();
    }
    else
    {
      $notifications = unserialize($notificationObject->getValue());
    }

    $notifications[] = $notificationItem;

    if (sfConfig::get('op_notification_limit', 20) > count($notifications))
    {
      array_slice($notifications, -1, -sfConfig::get('op_notification_limit', 20));
    }

    $notificationObject->setValue(serialize($notifications));
    $notificationObject->save();
    $notificationObject->free(true);
  }

  public function setRead(Member $target, $notificationId)
  {
    $notificationObject = Doctrine::getTable('MemberConfig')
      ->findOneByMemberIdAndName($target->getId(), 'notification_center');

    if (!$notificationObject)
    {
      return false;
    }
    else
    {
      $notifications = unserialize($notificationObject->getValue());
    }

    $success = false;

    foreach ($notifications as &$notification)
    {
      if ($notificationId === $notification['id'])
      {
        $notification['unread'] = false;
        $success = true;
      }
    }
    unset($notification);

    $notificationObject->setValue(serialize($notifications));
    $notificationObject->save();
    $notificationObject->free(true);

    return $success;
  }
}
