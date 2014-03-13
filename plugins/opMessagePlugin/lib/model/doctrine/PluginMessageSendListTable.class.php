<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class PluginMessageSendListTable extends Doctrine_Table
{
  /**
   * add receive message query
   *
   * @param Doctrine_Query $q
   * @param integer  $memberId
   */
  public function addReceiveMessageQuery(Doctrine_Query $q, $memberId = null)
  {
    if (is_null($memberId))
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $q = $q->where('member_id = ?', $memberId)
      ->andWhere('is_deleted = ?', false)
      ->andWhere('message_id IN (SELECT m2.id FROM SendMessageData m2 WHERE m2.is_send = ?)', true);

    return $q;
  }

  /**
   * 受信メッセージ一覧
   * @param $memberId
   * @param $page
   * @param $size
   * @return MessageSendList object（の配列）
   */
  public function getReceiveMessagePager($memberId = null, $page = 1, $size = 20)
  {
    $q = $this->addReceiveMessageQuery($this->createQuery(), $memberId);
    $q->orderBy('created_at DESC');

    $pager = new sfDoctrinePager('SendMessageData', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  /**
   * 未読メッセージ数を返す
   * @param $member_id
   * @return int 
   */
  public function countUnreadMessage($member_id)
  {
    $q = $this->createQuery()
      ->where('member_id = ?', $member_id)
      ->andWhere('is_deleted = ?', false)
      ->andWhere('is_read = ?', false)
      ->andWhere('message_id IN (SELECT m2.id FROM SendMessageData m2 WHERE m2.is_send = ?)', true);
    return $q->count();
  }

  /**
   * member_idとmessage_idから本人宛のメッセージであることを確認する
   * @param $memberId
   * @param $messageId
   * @return int
   */
  public function getMessageByReferences($memberId, $messageId)
  {
    $obj = $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andwhere('message_id = ?', $messageId)
      ->fetchOne();
    if (!$obj) {
      return null;
    }
    return $obj;
  }

  /**
   * 宛先リストを取得する
   * @return array
   */
  public function getMessageSendList($messageId)
  {
    $q = $this->createQuery()
      ->where('message_id = ?', $messageId);

    return $q->execute();
  }

  public function getPreviousSendMessageData(SendMessageData $message, $myMemberId)
  {
    $q = $this->addReceiveMessageQuery($this->createQuery(), $myMemberId);
    $q->andWhere('message_id < ?', $message->id)
      ->orderBy('message_id DESC');

    $list = $q->fetchOne();
    if ($list)
    {
      return $list->getSendMessageData();
    }

    return false;
  }

  public function getNextSendMessageData(SendMessageData $message, $myMemberId)
  {
    $q = $this->addReceiveMessageQuery($this->createQuery(), $myMemberId);
    $q->andWhere('message_id > ?', $message->id)
      ->orderBy('message_id ASC');

    $list = $q->fetchOne();
    if ($list)
    {
      return $list->getSendMessageData();
    }

    return false;
  }
}
