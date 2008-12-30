<?php

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
    parent::configure();
    $this->isAutoGenerate = false;
    $this->memberConfigSettings['pc_address']['IsConfirm'] = false;
    $this->setMemberConfigWidget('pc_address');
  }

  public function getToken()
  {
    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_token', $this->member->getId());
    if ($memberConfig) {
      return $memberConfig->getValue();
    }
  }

  public function getMailAddress()
  {
    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_pre', $this->member->getId());
    if ($memberConfig) {
      return $memberConfig->getValue();
    }
  }

  protected function sendConfirmMail($token, $to, $params = array())
  {
    $param = array(
      'token'    => $token,
      'authMode' => sfContext::getInstance()->getUser()->getCurrentAuthMode(),
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

    $this->member->setConfig('register_auth_mode', $user->getCurrentAuthMode());

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
