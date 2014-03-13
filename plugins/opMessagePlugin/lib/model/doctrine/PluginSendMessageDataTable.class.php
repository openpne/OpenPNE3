<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginSendMessageDataTable
 *
 * @package    opMessagePlugin
 * @subpackage model
 * @author     Maki Takahashi <maki@jobweb.jp>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PluginSendMessageDataTable extends Doctrine_Table
{
 /**
  * add send message query
  *
  * @param Doctrine_Query $q
  * @param integer  $memberId
  */
  public function addSendMessageQuery($q, $memberId = null)
  {
    if (is_null($memberId))
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }
    $q = $q->where('member_id = ?', $memberId)
      ->andWhere('is_deleted = ?', false)
      ->andWhere('is_send = ?', true);
    return $q;
  }

  public function getHensinMassage($memberId, $messageId)
  {
    $obj = $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('is_send = ?', true)
      ->andWhere('return_message_id = ?', $messageId)
      ->fetchOne();
    if (!$obj) {
      return null;
    }
    return $obj;
  }

  /**
   * 送信メッセージ一覧
   * @param $memberId
   * @param $page
   * @param $size
   * @return Message object（の配列）
   */
  public function getSendMessagePager($memberId = null, $page = 1, $size = 20)
  {
    $q = $this->addSendMessageQuery($this->createQuery(), $memberId);
    $q->orderBy('created_at DESC');
    $pager = new sfDoctrinePager('SendMessageData', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  /**
   * 下書きメッセージ一覧
   * @param $member_id
   * @param $page
   * @param $size
   * @return Message object（の配列）
   */
  public function getDraftMessagePager($member_id, $page = 1, $size = 20)
  {
    $q = $this->createQuery()
      ->andWhere('member_id = ?', $member_id)
      ->andWhere('is_deleted = ?', false)
      ->andWhere('is_send = ?', false)
      ->orderBy('created_at DESC');

    $pager = new sfDoctrinePager('SendMessageData', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getPreviousSendMessageData(SendMessageData $message, $myMemberId)
  {
    $q = $this->addSendMessageQuery($this->createQuery(), $myMemberId);
    $q->andWhere('id < ?', $message->id)
      ->orderBy('id DESC');

    return $q->fetchOne();
  }

  public function getNextSendMessageData(SendMessageData $message, $myMemberId)
  {
    $q = $this->addSendMessageQuery($this->createQuery(), $myMemberId);
    $q->andWhere('id > ?', $message->id)
      ->orderBy('id ASC');

    return $q->fetchOne();
  }

 /**
  * send message
  *
  * Available options:
  *
  *  * type      : The message type   (default: 'message')
  *  * identifier: The identifier of foreign table (default: 0)
  *  * is_read   : A default value of is_read flag (default: false)
  *  * fromMember: The message sender (default: my member object)
  *
  * @param mixed   $toMembers  a Member instance or array of Member instance
  * @param string  $subject    a subject of the message
  * @param string  $body       a body of the message
  * @param array   $options    options
  * @return SendMessageData
  */
  public static function sendMessage($toMembers, $subject, $body, $options = array())
  {
    $options = array_merge(array(
      'type'       => 'message',
      'identifier' => 0,
      'is_read'    => false,
    ), $options);

    if ($toMembers instanceof Member)
    {
      $toMembers = array($toMembers);
    }
    elseif (!is_array($toMembers))
    {
      throw new InvalidArgumentException();
    }

    $sendMessageData = new SendMessageData();
    if (!isset($options['fromMember']))
    {
      $options['fromMember'] = sfContext::getInstance()->getUser()->getMember();;
    }
    $sendMessageData->setMember($options['fromMember']);
    $sendMessageData->setSubject($subject);
    $sendMessageData->setBody($body);
    $sendMessageData->setForeignId($options['identifier']);
    $sendMessageData->setMessageType(Doctrine::getTable('MessageType')->getMessageTypeIdByName($options['type']));
    $sendMessageData->setIsSend(1);

    foreach ($toMembers as $member)
    {
      $send = new MessageSendList();
      $send->setSendMessageData($sendMessageData);
      $send->setMember($member);
      $send->setIsRead($options['is_read']);
      $send->save();
    }

    return $sendMessageData;
  }

  public function getMessageByTypeAndIdentifier($memberIdFrom, $memberIdTo, $messageTypeName = 'message', $identifier = 0)
  {
    $type = Doctrine::getTable('MessageType')->getMessageTypeIdByName($messageTypeName);
    if (!$type)
    {
      return false;
    }

    $q = $this->createQuery('m')
      ->select('m.id')
      ->where('m.message_type_id = ?')
      ->andWhere('m.member_id = ?')
      ->andWhere('m.foreign_id = ?');

    $obj = Doctrine::getTable('MessageSendList')->createQuery('ms')
      ->where('ms.member_id = ?', $memberIdTo)
      ->andWhere('ms.message_id IN ('.$q->getDql().')', array($type->id, $memberIdFrom, $identifier))
      ->orderBy('ms.created_at DESC')
      ->fetchOne();

    if (!$obj)
    {
      return false;
    }

    return $obj->getSendMessageData();
  }
}
