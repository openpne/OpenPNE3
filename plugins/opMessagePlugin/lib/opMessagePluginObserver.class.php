<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMessagePluginObserver
 *
 * @package opMessagePlugin
 * @author  Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMessagePluginObserver
{
  public static $targetCategories = array(
    'friend_confirm', 'community_confirm', 'community_admin_request',
  );

  static function injectMessageFormField(sfEvent $event)
  {
    $form = $event->getSubject();
    if (!($form instanceof FriendLinkForm) && !($form instanceof opChangeCommunityAdminRequestForm) && !($form instanceof opCommunityJoiningForm))
    {
      return null;
    }

    $form->setWidget('message', new sfWidgetFormTextarea());
    $form->setValidator('message', new opValidatorString(array('rtrim' => true, 'required' => false)));
    $form->getWidgetSchema()->setLabel('message', 'Message(Arbitrary)');
  }

  public static function filterConfirmation(sfEvent $event, $list)
  {
    if (!in_array($event['category'], self::$targetCategories))
    {
      return $list;
    }

    $getMessageCallback = array(__CLASS__, 'get'.sfInflector::camelize($event['category']).'Message');
    if (!is_callable($getMessageCallback))
    {
      return $list;
    }

    foreach ($list as $k => $v)
    {
      $obj = call_user_func($getMessageCallback, $event, $v);
      if (!$obj)
      {
        continue;
      }

      $list[$k]['list']['Message'] = array('text' => $obj->body);
    }

    return $list;
  }

  static public function listenToPostActionEventSendFriendLinkRequestMessage($arguments)
  {
    if ($arguments['result'] == sfView::SUCCESS)
    {
      $request         = $arguments['actionInstance']->getRequest();
      $friendLinkParam = $request->getParameter('friend_link');
      $toMemberId      = $request->getParameter('id');
      $toMember        = Doctrine::getTable('Member')->find($toMemberId);
      $fromMember      = sfContext::getInstance()->getUser()->getMember();
      $fromMemberName  = $fromMember->getName();

      $param = $arguments['actionInstance']->getRequest()->getParameter('friend_link');

      $sender = new opMessageSender();
      $sender->setToMember($toMember)
        ->setSubject(sfContext::getInstance()->getI18N()->__('%Friend% link request message'))
        ->setBody($friendLinkParam['message'])
        ->setMessageType('friend_link')
        ->send();
    }
  }

  static public function listenToPostActionEventSendCommunityJoiningRequestMessage($arguments)
  {
    if ($arguments['result'] == sfView::SUCCESS)
    {
      $community = $arguments['actionInstance']->community;
      //'poricy' is for keeping backward compatibility
      if ('close' !== $community->getConfig('register_poricy') && 'close' !== $community->getConfig('register_policy'))
      {
        return false;
      }

      $request = $arguments['actionInstance']->getRequest();
      $param = $request->getParameter('community_join');

      $memberId = sfContext::getInstance()->getUser()->getMemberId();

      $communityMember = Doctrine::getTable('CommunityMember')->findOneByMemberIdAndCommunityId($memberId, $community->id);

      $sender = new opMessageSender();
      $sender->setToMember($community->getAdminMember())
        ->setSubject(sfContext::getInstance()->getI18N()->__('%Community% joining request message'))
        ->setBody($param['message'])
        ->setMessageType('community_joining_request')
        ->setIdentifier($communityMember->id)
        ->send();
    }
    else
    {
      $community = $arguments['actionInstance']->community;
      //'poricy' is for keeping backward compatibility
      if ('close' !== $community->getConfig('register_poricy') && 'close' !== $community->getConfig('register_policy'))
      {
        // Injected message field is not useful in this community
        unset($arguments['actionInstance']->form['message']);
      }
    }
  }

  static public function listenToPostActionEventSendTakeOverCommunityRequestMessage($arguments)
  {
    if ($arguments['result'] == sfView::SUCCESS)
    {
      $community = $arguments['actionInstance']->community;
      $member = $arguments['actionInstance']->member;

      $request = $arguments['actionInstance']->getRequest();
      $param = $request->getParameter('admin_request');

      $sender = new opMessageSender();
      $sender->setToMember($member)
        ->setSubject(sfContext::getInstance()->getI18N()->__('%Community% taking over request message'))
        ->setBody($param['message'])
        ->setMessageType('community_taking_over')
        ->setIdentifier($community->id)
        ->send();
    }
  }

  static public function listenToPostActionEventSendCommunitySubAdminRequestMessage($arguments)
  {
    if ($arguments['result'] == sfView::SUCCESS)
    {
      $community = $arguments['actionInstance']->community;
      $member = $arguments['actionInstance']->member;

      $form  = $arguments['actionInstance']->form;
      $param = $form->getValues();

      $sender = new opMessageSender();
      $sender->setToMember($member)
        ->setSubject(sfContext::getInstance()->getI18N()->__('%Community% sub admin request message'))
        ->setBody($param['message'])
        ->setMessageType('community_sub_admin_request')
        ->setIdentifier($community->id)
        ->send();
    }
  }

  protected static function getCommunityAdminRequestMessage(sfEvent $event, $params = array())
  {
    $currentMemberId = sfContext::getInstance()->getUser()->getMemberId();
    $community = Doctrine::getTable('Community')->find($params['id']);

    return Doctrine::getTable('SendMessageData')->getMessageByTypeAndIdentifier($community->getAdminMember()->id, $currentMemberId, 'community_taking_over', $params['id']);
  }

  protected static function getCommunitySubAdminRequestMessage(sfEvent $event, $params = array())
  {
    $currentMemberId = sfContext::getInstance()->getUser()->getMemberId();
    $community = Doctrine::getTable('Community')->find($params['id']);

    return Doctrine::getTable('SendMessageData')->getMessageByTypeAndIdentifier($community->getAdminMember()->id, $currentMemberId, 'community_sub_admin_request', $params['id']);
  }

  protected static function getFriendConfirmMessage(sfEvent $event, $params = array())
  {
    $currentMemberId = sfContext::getInstance()->getUser()->getMemberId();

    return Doctrine::getTable('SendMessageData')->getMessageByTypeAndIdentifier($params['id'], $currentMemberId, 'friend_link');
  }

  protected static function getCommunityConfirmMessage(sfEvent $event, $params = array())
  {
    $communityMember = Doctrine::getTable('CommunityMember')->find($params['id']);
    $currentMemberId = sfContext::getInstance()->getUser()->getMemberId();

    return Doctrine::getTable('SendMessageData')->getMessageByTypeAndIdentifier($communityMember->member_id, $currentMemberId, 'community_joining_request', $params['id']);
  }
}
