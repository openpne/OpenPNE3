<?php

/**
 * sfOpenPNEAuthContainer_LoginID will handle credential for OpenPNE by LoginID.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class sfOpenPNEAuthContainer_LoginID extends sfOpenPNEAuthContainer
{
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

  public function isRegisterBegin($member_id = null)
  {
    return true;
  }

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
