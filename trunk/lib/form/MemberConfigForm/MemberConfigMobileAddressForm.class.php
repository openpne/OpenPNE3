<?php

/**
 * MemberConfigMobileAddress form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigMobileAddressForm extends MemberConfigForm
{
  protected $category = 'mobileAddress';

  public function saveConfig($name, $value)
  {
    if ($name === 'mobile_address')
    {
      $this->savePreConfig($name, $value);

      $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('mobile_address_token', $this->member->getId());
      $token = $memberConfig->getValue();
      $this->sendConfirmMail($token, $value, array(
        'id'   => $this->member->getId(),
        'type' => $name,
      ));

      return true;
    }

    parent::saveConfig($name, $value);
  }

  protected function sendConfirmMail($token, $to, $params = array())
  {
    $options = array_merge(array('token' => $token), $params);

    $mail = new sfOpenPNEMailSend();
    $mail->setSubject('メールアドレス変更ページのお知らせ');
    $mail->setTemplate('global/changeMobileAddressMail', $options);
    $mail->send($to, OpenPNEConfig::get('admin_mail_address'));
  }
}
