<?php

/**
 * sfOpenPNEAuthForm represents a form to login.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
abstract class sfOpenPNEAuthForm extends sfForm
{
  public $memberForm;
  public $profileForm;

  public function configure()
  {
    $this->widgetSchema->setNameFormat('auth[%s]');
  }

  public function __toString()
  {
    $result = '';

    if ($this->memberForm) {
      $result .= $this->memberForm;
    }

    $result .= parent::__toString();

    if ($this->profileForm) {
      $result .= $this->profileForm;
    }

    return $result;
  }

  public function setForRegisterWidgets()
  {
    $this->memberForm = new MemberForm();
    $this->profileForm = new ProfileForm();
    $this->profileForm->setRegisterWidgets();
  }

  public function bindAll($request)
  {
    if ($this->memberForm) {
      $this->memberForm->bind($request->getParameter('member'));
    }

    if ($this->profileForm) {
      $this->profileForm->bind($request->getParameter('profile'));
    }

      $this->bind($request->getParameter('auth'));
  }

  public function isValidAll()
  {
    if ($this->memberForm && !$this->memberForm->isValid()) {
      return false;
    }

    if ($this->profileForm && !$this->profileForm->isValid()) {
      return false;
    }

    return $this->isValid();
  }
}
