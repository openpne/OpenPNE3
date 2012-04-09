<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opNotificationCenter
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */
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

    array_unshift($notifications, $notificationItem);
    $notificationLimit = sfConfig::get('op_notification_limit', 20);

    if ($notificationLimit < count($notifications))
    {
      $notifications = array_slice($notifications, 0, $notificationLimit);
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

  static public function getNotifications(Member $member = null)
  {
    if (is_null($member))
    {
      $member = sfContext::getInstance()->getUser()->getMember();
    }

    $notificationObject = Doctrine::getTable('MemberConfig')
      ->findOneByMemberIdAndName($member->getId(), 'notification_center');

    if (!$notificationObject)
    {
      $notifications = array();
    }
    else
    {
      $notifications = unserialize($notificationObject->getValue());
      $notificationObject->free(true);
    }

    return $notifications;
  }
}
