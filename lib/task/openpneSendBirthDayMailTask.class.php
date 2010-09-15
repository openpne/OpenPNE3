<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneSendBirthDayMailTask extends opBaseSendMailTask
{
  protected function configure()
  {
    parent::configure();
    $this->namespace        = 'openpne';
    $this->name             = 'send-birthday-mail';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [openpne:send-birthday-mail|INFO] task does things.
Call it with:

  [php symfony openpne:send-birthday-mail|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    parent::execute($arguments, $options);

    opApplicationConfiguration::unregisterZend();
    $birthday = Doctrine::getTable('Profile')->retrieveByName('op_preset_birthday');
    if (!$birthday)
    {
      throw new sfException('This project doesn\'t have the op_preset_birthday profile item.');
    }

    $profiles = Doctrine::getTable('MemberProfile')->createQuery()
      ->where('profile_id = ?', $birthday->id)
      ->andWhere('DATE_FORMAT(value_datetime, ?) = ?', array('%m-%d', date('m-d', strtotime('+ 1 week'))))
      ->execute();
    opApplicationConfiguration::registerZend();

    $context = sfContext::createInstance($this->createConfiguration('pc_frontend', 'prod'));
    $i18n = $context->getI18N();

    foreach ($profiles as $profile)
    {
      $birthMember = $profile->getMember();
      foreach ($birthMember->getFriends() as $member)
      {
        $params = array(
          'member'      => $member,
          'birthMember' => $birthMember,
          'subject'     => $i18n->__('There is your %my_friend% that its birthday is coming soon'),
        );
        opMailSend::sendTemplateMail('birthday', $member->getEmailAddress(), opConfig::get('admin_mail_address'), $params, $context);
      }
    }
  }
}
