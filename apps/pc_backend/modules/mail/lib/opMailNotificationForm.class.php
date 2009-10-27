<?php

/**
 * Mail Notification Form
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMailNotificationForm extends sfForm
{
  public function configure()
  {
    $formatter = $this->widgetSchema->getFormFormatter();

    $config = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mail_template.yml'));
    $choices = array(
      '1' => $formatter->translate('Notify'),
      '0' => $formatter->translate('Don\'t Notify')
    );

    $disabledList = Doctrine::getTable('NotificationMail')->getDisabledNotificationNames();

    foreach ($config as $target => $mails)
    {
      foreach ($mails as $k => $v)
      {
        $fieldName = $target.'_'.$k;
        if (empty($v['configurable']) || !$v['configurable'])
        {
          continue;
        }

        $this->setWidget($fieldName, new sfWidgetFormChoice(array('choices' => $choices, 'expanded' => true)));
        $this->setValidator($fieldName, new sfValidatorChoice(array('choices' => array_keys($choices))));
        $this->setDefault($fieldName, !in_array($fieldName, $disabledList));
        $this->widgetSchema->setLabel($fieldName, $v['caption']);
      }
    }

    $this->widgetSchema->setNameFormat('notification[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $k => $v)
    {
      $notification = Doctrine::getTable('NotificationMail')->findOneByName($k);
      if (!$notification)
      {
        $notification = Doctrine::getTable('NotificationMail')->create(array('name' => $k));
      }
      $notification->is_enabled = (bool)$v;
      $notification->save();
    }
  }
}
