<?php

/**
 * sfOpenPNEAuthContainer_LoginID will handle credential for LoginID.
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class sfOpenPNEAuthContainer_LoginID extends sfOpenPNEAuthContainer
{
  /**
   * Fetches data from storage container.
   *
   * @param  sfForm $form
   * @return int    the member id
   */
  public function fetchData($form)
  {
    $login_id = $form->getValue('login_id');
    $password = $form->getValue('password');

    $data = AuthenticationLoginIdPeer::retrieveByLoginId($login_id);

    if (!$data) {
      return false;
    }

    if ($data->getPassword() == md5($password)) {
      return $data->getMemberId();
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
    return true;
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

    $login_id = $form->getValue('login_id');
    $password = md5($form->getValue('password'));

    $auth = new AuthenticationLoginId();
    $auth->setMemberId($memberId);
    $auth->setLoginId($login_id);
    $auth->setPassword($password);

    return $auth->save();
  }
}
