<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

      $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_token', $this->member->getId());
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
    $mail->setTemplate('global/changePCAddressMail', $options);
    $mail->send($to, OpenPNEConfig::get('admin_mail_address'));
  }
}
