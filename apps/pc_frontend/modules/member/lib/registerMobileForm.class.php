<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * registerMobile form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class registerMobileForm extends MemberConfigMobileAddressForm
{
  public function configure()
  {
    $this->setWidget('mobile_address', new sfWidgetFormInput());
    $this->setValidator('mobile_address', new sfValidatorMobileEmail());
    $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'isUnique'),
        'arguments' => array('name' => 'mobile_address'),
      )));
  }

  public function save()
  {
    $token = md5(uniqid(mt_rand(), true));

    $this->member->setConfig('register_mobile_token', $token);
    $this->member->setConfig('mobile_address_pre', $this->getValue('mobile_address'));

    $param = array(
      'token' => $token,
      'id' => $this->member->getId(),
    );
    $mail = new opMailSend();
    $mail->setSubject(opConfig::get('sns_name').'携帯登録');
    $mail->setTemplate('member/registerMobileMail', $param);
    $mail->send($this->getValue('mobile_address'), opConfig::get('admin_mail_address'));
  }
}
