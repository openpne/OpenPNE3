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

    if (!MemberConfigPeer::retrieveByNameAndMemberId('pc_address_pre', $member->getId())) {
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

    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_pre', $memberId);
    $memberConfig->setName('pc_address');
    return $memberConfig->save();
  }

  /**
   * Registers E-mail address.
   *
   * @param  string $address
   * @return Member
   */
  public function registerEmailAddress($address)
  {
    $memberConfig = MemberConfigPeer::retrieveByNameAndValue('pc_address_pre', $address);
    if ($memberConfig) {
      $memberConfig->saveToken();
      return $memberConfig->getMember();
    }

    $member = new Member();
    $member->setIsActive(false);

    $memberConfig = new MemberConfig();
    $memberConfig->setName('pc_address');
    $memberConfig->setValue($address);
    $memberConfig->setMember($member);
    $memberConfig->savePre();
    $memberConfig->saveToken();

    return $member;
  }
}
