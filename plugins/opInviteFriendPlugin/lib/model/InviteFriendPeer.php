<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class InviteFriendPeer
{
  public static function __($text)
  {
    return sfContext::getInstance()->getI18N()->__($text);
  }

  public static function sendMessage($send_id, $receive_id, $message, $target_id)
  {
    $messageData = new SendMessageData();
    $messageData->setMemberId($send_id);
    $url = sfContext::getInstance()->getController()->genUrl('member/profile?id=' . $target_id, true);
    $body = self::__('The member introduce message had received.') . "\n"
          . "\n"
          . self::__('Message:') . "\n"
          . $message . "\n"
          . "\n"
          . self::__("This member's URL:") . "\n"
          . $url;
    $messageData->setSubject(self::__('The member introduce message'));
    $messageData->setBody($body);
    $messageData->setIsSend(true);
    $messageData->setMessageType(MessageTypePeer::getMessageTypeIdByName('invite_friend'));
    $messageData->save();

    $messageSendList = new MessageSendList();
    $messageSendList->setMemberId($receive_id);
    $messageSendList->setMessageId($messageData->getId());
    $messageSendList->save();
  }

  public static function sendMessageByList($send_id, $array_receive_id, $message, $target_id)
  {
    foreach($array_receive_id as $receive_id)
    {
      self::sendMessage($send_id, intval($receive_id), $message, $target_id);
    }
  }

  public static function getNotFriendMembers($send_id, $target_id)
  {
    $relations = MemberRelationshipPeer::retrievesByMemberIdFrom($send_id);
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::MEMBER_ID_FROM, $send_id);
    $c->add(MemberRelationshipPeer::MEMBER_ID_TO, $target_id, Criteria::NOT_EQUAL);
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    $relations = MemberRelationshipPeer::doSelect($c);

    $members = array();
    foreach($relations as $relation)
    {
      $re = MemberRelationshipPeer::retrieveByFromAndTo($target_id, $relation->getMemberIdTo());
      if (!$re || $re && !$re->getIsFriend())
      {
        $member = MemberPeer::retrieveByPk($relation->getMemberIdTo());
        if($member && $member->getIsActive())
        {
          $members[] = $member;
        }
      }
    }

    return count($members) ? $members : false;
  }
}
