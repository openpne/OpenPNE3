<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Invite form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class InviteForm extends MemberConfigPcAddressForm
{
  public function configure()
  {
    $this->setWidget('mail_address', new sfWidgetFormInput());
    $this->setValidator('mail_address', new sfValidatorEmail());

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validate'),
    )));
  }

  public function validate($validator, $values, $arguments = array())
  {
    if (opToolkit::isMobileEmailAddress($values['mail_address']))
    {
      $mailValidator = new sfValidatorMobileEmail();
      $values['mobile_address'] = $mailValidator->clean($values['mail_address']);
    }
    else
    {
      $mailValidator = new opValidatorPCEmail();
      $values['pc_address'] = $mailValidator->clean($values['mail_address']);
    }

    return $values;
  }

  public function saveConfig($name, $value)
  {
    if ('pc_address' === $name || 'mobile_address' === $name)
    {
      $this->savePreConfig($name, $value);
      $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($name.'_token', $this->member->getId());
      $token = $memberConfig->getValue();
      $this->sendConfirmMail($token, $value, array(
        'id'   => $this->member->getId(),
        'type' => $name,
      ));
    }
  }

  protected function sendConfirmMail($token, $to, $params = array())
  {
    $param = array(
      'token'    => $token,
      'authMode' => $this->getOption('authMode', sfContext::getInstance()->getUser()->getCurrentAuthMode()),
      'isMobile' => opToolkit::isMobileEmailAddress($to),
    );

    $mail = new sfOpenPNEMailSend();
    $mail->setSubject(OpenPNEConfig::get('sns_name').'の招待状が届いています');
    $mail->setTemplate('global/requestRegisterURLMail', $param);
    $mail->send($to, OpenPNEConfig::get('admin_mail_address'));
  }

  public function save()
  {
    parent::save();

    $user = sfContext::getInstance()->getUser();

    $this->member->setConfig('register_auth_mode', $this->getOption('authMode', $user->getCurrentAuthMode()));

    if ($this->getOption('is_link'))
    {
      $fromMemberId = $user->getMemberId();
      $toMemberId = $this->member->getId();
      $relation = MemberRelationshipPeer::retrieveByFromAndTo($fromMemberId, $toMemberId);
      if (!$relation)
      {
        $relation = new MemberRelationship();
        $relation->setMemberIdFrom($fromMemberId);
        $relation->setMemberIdTo($toMemberId);
      }
      $relation->setFriend();
    }
  }
}
