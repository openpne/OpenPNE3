<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MessageDelete form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Maki Takahashi <maki@jobweb.co.jp>
 */
class MessageDeleteForm extends sfForm
{
  public function configure()
  {
    $message_ids = $this->getOption('message');
    if ($message_ids) {
      foreach ($message_ids as $_message_id) {
        $this->setWidget("message_ids[$_message_id]", new sfWidgetFormInputCheckbox(array(
                                                                                      'value_attribute_value' => $_message_id
                                                                                          ))); 
      }
    }
    $this->setValidator("message_ids", new sfValidatorPass(array('required' => false)));
    $this->setWidget("object_name", new sfWidgetFormInputHidden());
    $this->setValidator("object_name", new sfValidatorChoice(array('choices' => array(
                                                                                      'MessageSendList',
                                                                                      'SendMessageData',
                                                                                      'DeletedMessage'))));
    $this->setDefault("object_name", $this->getOption('object_name'));
    $this->widgetSchema->setNameFormat('message[%s]');
  }

  public function save()
  {
    foreach ($this->getValue('message_ids') ? $this->getValue('message_ids') : array() as $message_id)
    {
      if (sfContext::getInstance()->getRequest()->getParameter('restore')) {
        Doctrine::getTable('DeletedMessage')->restoreMessage($message_id);
      } else {
        Doctrine::getTable('DeletedMessage')->deleteMessage(sfContext::getInstance()->getUser()->getMemberId(),
                                                            $message_id,
                                                            $this->getOption('object_name'));
      }
    }
  }
}
