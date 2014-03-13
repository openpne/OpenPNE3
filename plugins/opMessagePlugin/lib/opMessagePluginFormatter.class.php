<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMessagePluginFormatter
 *
 * @package    OpenPNE
 * @subpackage opMessagePlugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMessagePluginFormatter
{
  public function decorateCommunityTakingOverBody(SendMessageData $message)
  {
    $id = $message->getForeignId();
    $community = Doctrine::getTable('Community')->find($id);
    if (!$community)
    {
      return $this->body;
    }

    $params = array(
      'fromMember' => $message->getMember(),
      'message'    => $message->body,
      'community'  => $community,
    );

    return opMessageSender::decorateBySpecifiedTemplate('communityTakingOverMessage', $params);
  }

  public function decorateCommunitySubAdminRequestBody(SendMessageData $message)
  {
    $id = $message->getForeignId();
    $community = Doctrine::getTable('Community')->find($id);
    if (!$community)
    {
      return $this->body;
    }

    $params = array(
      'fromMember' => $message->getMember(),
      'message'    => $message->body,
      'community'  => $community,
    );

    return opMessageSender::decorateBySpecifiedTemplate('communitySubAdminRequestMessage', $params);
  }

  public function decorateCommunityJoiningRequestBody(SendMessageData $message)
  {
    $id = $message->getForeignId();
    $communityMember = Doctrine::getTable('CommunityMember')->find($id);
    if (!$communityMember)
    {
      return $message->body;
    }

    $params = array(
      'fromMember' => $message->getMember(),
      'message'    => $message->body,
      'community'  => $communityMember->getCommunity(),
    );

    return opMessageSender::decorateBySpecifiedTemplate('communityJoiningRequestMessage', $params);
  }

  public function decorateFriendLinkBody(SendMessageData $message)
  {
    $params = array(
      'fromMember' => $message->getMember(),
      'message'    => $message->body,
    );

    return opMessageSender::decorateBySpecifiedTemplate('friendLinkMessage', $params);
  }

  public function __call($method, $arguments)
  {
    $prefix = 'decorate';
    $suffix = 'Body';
    if (substr($method, 0, strlen($prefix)) === $prefix
      && substr($method, -(strlen($suffix))) === $suffix)
    {
      $event = new sfEvent($this, 'op_message_plugin.decorate_body', array('method' => $method, 'arguments' => $arguments));
      $this->notifyUntil($event);
      if ($event->isProcessed())
      {
        return $event->getReturnValue();
      }
    }

    throw new sfException(sprintf('Call to undefined method %s::%s.', get_class($this), $method));
  }

  public function notifyUntil($event)
  {
    $dispatcher = sfContext::getInstance()->getEventDispatcher();
    foreach ($dispatcher->getListeners($event->getName()) as $listener)
    {
      $message = call_user_func($listener, $event['arguments'][0]);
      if ($message)
      {
        $event->setProcessed(true);
        $event->setReturnValue($message);
        break;
      }
    }
  }
}
