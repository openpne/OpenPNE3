<?php

/**
 * MemberConfigPcAddress form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigPcAddressForm extends MemberConfigForm
{
  protected $category = 'pcAddress';

  public function saveConfig($name, $value)
  {
    if ($name === 'pc_address') {
      $this->savePreConfig($name, $value);

      $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_token', $this->member->getId());
      $token = $memberConfig->getValue();

      $mail = new sfOpenPNEMailSend();
      $mail->setSubject('メールアドレス変更ページのお知らせ');
      $mail->setTemplate('global/changeMailAddressMail', array(
        'token' => $token,
        'id'    => $this->member->getId(),
        'type'  => $name,
      ));
      $mail->send($value, OpenPNEConfig::get('admin_mail_address'));

      return true;
    }

    parent::saveConfig($name, $value);
  }
}
