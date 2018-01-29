<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * base actions class for the opMessagePlugin.
 *
 * @package    OpenPNE
 * @subpackage message
 * @author     Maki TAKAHASHI <maki@jobweb.co.jp>
 */
class opMessagePluginActions extends sfActions
{
  protected function isDraftOwner()
  {
    if (!$this->message) {
      return true;
    }
    if ($this->message->getMemberId() !== $this->getUser()->getMemberId()) {
      return false;
    }
    if ($this->message->getIsDeleted() === 1) {
      return false;
    }
    if ($this->message->getIsSend() === 1) {
      return false;
    }
    return true;
  }
  
  protected function isReadable($type)
  {
    if (!$this->message) {
      return false;
    }
    if ($this->message->getIsSender($this->getUser()->getMemberId()) === 0
        && $this->message->getIsReceiver($this->getUser()->getMemberId()) === 0) {
        return false; 
    }
    switch ($type) {
      case "receive":
        if ($this->message->getIsReceiver($this->getUser()->getMemberId()) === 0) {
          return false;
        }
        $read_message = Doctrine::getTable('MessageSendList')->getMessageByReferences(
                                                  $this->getUser()->getMemberId(), $this->message->getId());
        if (!$read_message) {
          return false;
        }
        if ($read_message->getIsRead() == 0) {
          $read_message->readMessage();
        }
        if ($read_message->getIsDeleted())
        {
          return false;
        }
        return $read_message;
      case "send":
        if ($this->message->getIsSender($this->getUser()->getMemberId()) === 0) {
          return false;
        }
        if ($this->message->getIsDeleted())
        {
          return false;
        }
        return true;
      case "dust":
        $deleted_message = Doctrine::getTable('DeletedMessage')->getDeletedMessageByMessageId(
                                                  $this->getUser()->getMemberId(), $this->message->getId());
        if (!$deleted_message) {
          $send_list = Doctrine::getTable('MessageSendList')->getMessageByReferences(
                                                  $this->getUser()->getMemberId(), $this->message->getId());
          $deleted_message = Doctrine::getTable('DeletedMessage')->getDeletedMessageByMessageSendListId(
                                                  $this->getUser()->getMemberId(), $send_list->getId());
        }
        if (!$deleted_message) {
          return false;
        }
        if ($deleted_message->getIsDeleted())
        {
          return false;
        }
        return $deleted_message;
    }
  }
}
