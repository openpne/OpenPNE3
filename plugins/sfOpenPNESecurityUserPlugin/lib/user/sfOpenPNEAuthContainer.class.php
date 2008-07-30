<?php

/**
 * sfOpenPNEAuthContainer_PCAddress will handle credential for OpenPNE.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
abstract class sfOpenPNEAuthContainer
{
  /**
   * Fetches data from storage container.
   *
   * @param  sfForm $form
   * @return int    memberId
   */
  abstract public function fetchData($form);

  /**
   * Registers data to storage container.
   *
   * @param  sfForm $form
   * @param  int    $memberId
   * @return bool
   */
  abstract public function registerData($memberId, $form);

  public function register($form, $memberId = 0)
  {
    $member = true;
    $profile = true;

    if ($form->memberForm) {
      $member = $form->memberForm->save();
      $memberId = $member->getId();
    }

    if ($form->profileForm) {
      $profile = $form->profileForm->save($memberId);
    }

    $auth = $this->registerData($memberId, $form);

    if ($member && $profile && $auth) {
      return $memberId;
    }

    return false;
  }

  /**
   * Is beginning to register of SNS.
   *
   * @return bool
   */
  abstract public function isRegisterBegin($member_id = null);

  /**
   * Is finished registering of SNS.
   *
   * @return bool
   */
  abstract public function isRegisterFinish($member_id = null);

  /**
   * Gets an action path to register
   *
   * @return string
   */
  public function getRegisterEndAction()
  {
    return sfConfig::get('sf_openpne_register_end_action');
  }
}
