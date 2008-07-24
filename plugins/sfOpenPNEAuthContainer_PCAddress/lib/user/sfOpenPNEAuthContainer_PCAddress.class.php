<?php

/**
 * sfOpenPNEAuthContainer_PCAddress will handle credential for OpenPNE by PCAddress.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class sfOpenPNEAuthContainer_PCAddress extends sfOpenPNEAuthContainer
{
  public function fetchData($form)
  {
    $email = $form->getValue('email');
    $password = $form->getValue('password');

    $data = AuthenticationPcAddressPeer::retrieveByPcAddress($email);

    if (!$data) {
      return false;
    }

    if ($data->getPassword() == md5($password)) {
      return $data->getMemberId();
    }

    return false;
  }
}
