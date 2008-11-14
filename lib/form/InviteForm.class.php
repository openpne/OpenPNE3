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
}
