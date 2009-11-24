<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
    if ($name === 'pc_address')
    {
      $this->savePreConfig($name, $value);

      $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('pc_address_token', $this->member->getId(), true);
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
    $params = array_merge(array(
      'token'   => $token,
      'subject' => 'メールアドレス変更ページのお知らせ',
    ), $params);

    sfOpenPNEMailSend::sendTemplateMail('changeMailAddress', $to, opConfig::get('admin_mail_address'), $params);
  }
}
