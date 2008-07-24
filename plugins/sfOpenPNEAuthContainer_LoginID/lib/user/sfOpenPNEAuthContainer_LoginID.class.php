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
}
