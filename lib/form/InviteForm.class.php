<?php

/**
 * Invite form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class InviteForm extends MemberConfigForm
{
  public function configure()
  {
    parent::configure();

    $settings = $this->getSettings();

    $this->setWidgets(array('pc_address' => $this->generateWidget($settings['pc_address'])));
    $this->setValidators(array('pc_address' => $this->generateValidator($settings['pc_address'])));
    $this->widgetSchema->setLabels(array('pc_address' => $settings['pc_address']['Caption']));

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array('InviteForm', 'isUniquePCAddress'))));

    $this->widgetSchema->setNameFormat('pc_address[%s]');
  }

  public function register()
  {
    $address = $this->getValue('pc_address');
    $memberConfig = MemberConfigPeer::retrieveByNameAndValue('pc_address_pre', $this->getValue('pc_address'));
    if ($memberConfig) {
      $memberConfig->saveToken();
      $token = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_token', $memberConfig->getMember()->getId());
      return $token;
    }

    $member = new Member();
    $member->setIsActive(false);

    $memberConfig = new MemberConfig();
    $memberConfig->setName('pc_address');
    $memberConfig->setValue($address);
    $memberConfig->setMember($member);
    $memberConfig->savePre();
    $memberConfig->saveToken();

    $token = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_token', $member->getId());
    return $token;
  }

  public static function isUniquePCAddress($validator, $value, $arguments = array())
  {
    $data = MemberConfigPeer::retrieveByNameAndValue('pc_address', $value['pc_address']);

    if (!$data || !$data->getMember()->getIsActive()) {
      return $value;
    }

    throw new sfValidatorError($validator, 'This E-mail address already exists.');
  }
}
