<?php

/**
 * sfOpenPNEAuthContainer_PCAddress will handle credential for PCAddress.
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEAuthContainer_PCAddress extends sfOpenPNEAuthContainer
{
  /**
   * Fetches data from storage container.
   *
   * @param  sfForm $form
   * @return int    the member id
   */
  public function fetchData($form)
  {
    $pc_address = $form->getValue('pc_address');
    $password = $form->getValue('password');

    $data = MemberConfigPeer::retrieveByNameAndValue('pc_address', $pc_address);
    if (!$data) {
      return false;
    }

    $valid_password = MemberConfigPeer::retrieveByNameAndMemberId('password', $data->getMember()->getId())->getValue();
    if (md5($password) === $valid_password) {
      return $data->getMember()->getId();
    }

    return false;
  }

  /**
   * Returns true if the current state is a beginning of register.
   *
   * @return bool returns true if the current state is a beginning of register, false otherwise
   */
  public function isRegisterBegin($member_id = null)
  {
    $member = MemberPeer::retrieveByPk((int)$member_id);

    if (!$member) {
      return false;
    }

    if (!MemberConfigPeer::retrieveByNameAndMemberId('pc_address', $member->getId())) {
      return false;
    }

    if (!$member->getIsActive()) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Returns true if the current state is a end of register.
   *
   * @return bool returns true if the current state is a end of register, false otherwise
   */
  public function isRegisterFinish($member_id = null)
  {
    $data = MemberPeer::retrieveByPk((int)$member_id);

    if (!$data) {
      return false;
    }

    if ($data->getIsActive()) {
      return false;
    } else {
      return true;
    }
  }

  /**
   * Registers data to storage container.
   *
   * @param  int    $memberId
   * @param  sfForm $form
   * @return bool   true if the data has already been saved, false otherwise
   */
  public function registerData($memberId, $form)
  {
    if (!$memberId) {
      return false;
    }

    $password = md5($form->getValue('password'));

    $authPCAddress = AuthenticationPcAddressPeer::retrieveByMemberId($memberId);
    $authPCAddress->setPassword($password);

    return $authPCAddress->save();
  }

  /**
   * Registers E-mail address.
   *
   * @param  string $address
   * @param  string $session
   * @return int
   */
  public function registerEmailAddress($address, $session)
  {
    $memberConfig = MemberConfigPeer::retrieveByNameAndValue('pc_address', $address);
    if ($memberConfig) {
      $authPCAddress = AuthenticationPcAddressPeer::retrieveByMemberId($memberConfig->getMemberId());
      $authPCAddress->setRegisterSession($session);
      return $authPCAddress->save();
    }

    $member = new Member();
    $member->setIsActive(false);

    $memberConfig = new MemberConfig();
    $memberConfig->setName('pc_address');
    $memberConfig->setValue($address);

    $member->addMemberConfig($memberConfig);
    $member->save();

    $authPCAddress = new AuthenticationPcAddress();
    $authPCAddress->setMember($member);
    $authPCAddress->setRegisterSession($session);
    return $authPCAddress->save();
  }
}
