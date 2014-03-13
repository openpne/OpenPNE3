<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Plugin Send Message form.
 *
 * @package   opMessagePlugin
 * @subpackage form
 * @author     Shingo Yamada <s.yamada@tejimaya.com>
 */
class SendMessageForm extends PluginSendMessageDataForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget("send_member_id", new sfWidgetFormInputHidden());
    $this->setValidator("send_member_id", new sfValidatorInteger());
    $this->setValidator("subject", new opValidatorString(array('rtrim' => true)));
    $this->setValidator("body", new opValidatorString(array('rtrim' => true)));
    $this->setDefault("send_member_id", $this->getOption('send_member_id'));
  }

  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);
    $object->setMessageType(Doctrine::getTable('MessageType')->getMessageTypeIdByName('message'));
    $object->setCreatedAt(date('Y-m-d H:i:s'));

    return $object;
  }
}
