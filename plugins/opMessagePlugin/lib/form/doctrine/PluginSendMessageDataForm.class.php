<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginSendMessageData form.
 *
 * @package   opMessagePlugin
 * @subpackage form
 * @author     Shingo Yamada <s.yamada@tejimaya.com>
 */
abstract class PluginSendMessageDataForm extends BaseSendMessageDataForm
{
  public function setup()  {
    parent::setup();

    unset($this['created_at'], $this['updated_at'], $this['foreign_id']);
    unset($this->widgetSchema['member_id'],
          $this->widgetSchema['is_deleted'],
          $this->widgetSchema['is_send'],
          $this->widgetSchema['message_type_id']);
    $this->widgetSchema['subject'] = new sfWidgetFormInput();
    $this->widgetSchema['thread_message_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['return_message_id'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['subject']->setOption('trim', true);
    $this->validatorSchema['subject']->setOption('required', true);
    $this->validatorSchema['body']->setOption('trim', true);
    $this->validatorSchema['body']->setOption('required', true);
    $this->widgetSchema->setNameFormat('message[%s]');

    if (sfConfig::get('app_message_is_upload_images', true))
    {
      $images = array();
      if (!$this->isNew())
      {
        $images = $this->getObject()->getMessageFile();
      }

      $max = (int)sfConfig::get('app_message_max_image_file_num', 3);
      for ($i = 1; $i <= $max; $i++)
      {
        $key = 'image'.$i;

        if (isset($images[$i - 1]))
        {
          $image = $images[$i - 1];
        }
        else
        {
          $image = new MessageFile();
          $image->setSendMessageData($this->getObject());
        }

        $imageForm = new MessageFileForm($image);
        $imageForm->getWidgetSchema()->setFormFormatterName('list');
        $this->embedForm($key, $imageForm, '<ul id="message_'.$key.'">%content%</ul>');
      }
    }
  }

  public function save(Doctrine_Connection $con = null)
  {
    $message = parent::save($con);

    return $message;
  }

  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);

    foreach ($this->embeddedForms as $key => $form)
    {
      if (!($form->getObject() && $form->getObject()->getFile()))
      {
        unset($this->embeddedForms[$key]);
      }
    }

    if (sfContext::getInstance()->getRequest()->getParameter('is_draft'))
    {
      $object->setIsSend(0);
    }
    else
    {
      $object->setIsSend(1);
    }
    $object->setMemberId(sfContext::getInstance()->getUser()->getMemberId());
    $this->saveSendList($object);

    return $object;
  }

  public function saveSendList(SendMessageData $message)
  {
    $send_member_id = $this->getValue('send_member_id');
    $send = Doctrine::getTable('MessageSendList')->getMessageByReferences($send_member_id, $message->getId());

    if (!$send) {
      $send = new MessageSendList();
      $send->setSendMessageData($message);
      $send->setMemberId($send_member_id);
      $send->save();
    }
  }

/*
  protected function doSave($con = null)
  {
    $max = (int)sfConfig::get('app_message_max_image_file_num', 3);
    for ($i = 1; $i <= $max; $i++)
    {
      $key = 'image'.$i;

      if (!$images[$key]['id'] && !$images[$key]['image'])
      {
        unset($this->embeddedforms[$key]);
      }
    }
    parent::doSave($con);
  }
*/
}
