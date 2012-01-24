<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * push actions.
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class pushActions extends opJsonApiActions
{
  public function executeSearch(sfWebRequest $request)
  {
    $this->notifications = $this->getNotifications();

    $this->setTemplate('array');
  }

  public function executeCount(sfWebRequest $request)
  {
    $notifications = $this->getNotifications();

    $this->count = array(
      'link'    => 0,
      'message' => 0,
      'other'   => 0,
    );

    foreach ($notifications as $notification)
    {
      if (array_key_exists($notification['category'], $this->count))
      {
        $category = $notification['category'];
      }
      else
      {
        $category = 'other';
      }

      if ($notification['unread'])
      {
        $this->count[$category]++;
      }
    }

    $this->setTemplate('count');
  }

  public function executeRead(sfWebRequest $request)
  {
    $this->forward400Unless($request['id'], 'id parameter not specified.');

    $member = $this->getUser()->getMember();

    $ret = opNotificationCenter::setRead($member, $request['id']);

    if ($ret)
    {
      return $this->renderJSON(array('status' => 'success'));
    }
    else
    {
      return $this->renderJSON(array('status' => 'error'));
    }
  }

  protected function getNotifications(Member $member = null)
  {
    if (!$member)
    {
      $member = $this->getUser()->getMember();
    }

    $notifications = $member->getConfig('notification_center');

    if (!$notifications)
    {
      $notifications = array();
    }
    else
    {
      $notifications = unserialize($notifications);
    }

    return $notifications;
  }
}
