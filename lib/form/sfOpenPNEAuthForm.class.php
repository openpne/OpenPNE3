<?php

/**
 * sfOpenPNEAuthForm represents a form to login.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class sfOpenPNEAuthForm extends sfForm implements IteratorAggregate
{
  public
    $memberForm,
    $profileForm,
    $configForm;

  const AUTH_MODE_FIELD_NAME = 'authMode';

 /**
  * Configures the current form.
  */
  public function configure()
  {
    $this->widgetSchema->setNameFormat('auth'.$this->getAuthMode().'[%s]');
  }

 /**
  * Returns the current auth mode.
  *
  * The child class must be defined implementation to return the current auth mode.
  */
  abstract public function getAuthMode();

 /**
  * Returns the string representation of the form(s).
  *
  * @return string HTML for the form(s).
  */
  public function __toString()
  {
    $result = '';

    if ($this->memberForm) {
      $result .= $this->memberForm;
    }

    if ($this->configForm) {
      $result .= $this->configForm;
    }

    $result .= parent::__toString();

    if ($this->profileForm) {
      $result .= $this->profileForm;
    }

    return $result;
  }

 /**
  * Adds fields to the form for registering.
  */
  public function setForRegisterWidgets($member = null)
  {
    if (!$member) {
      $member = new Member();
    }

    $this->memberForm = new MemberForm($member);

    $this->profileForm = new MemberProfileForm($member->getMemberProfiles());
    $this->profileForm->setRegisterWidgets();

    $this->configForm = new MemberConfigForm($member);
  }

 /**
  * Binds the form with request parameters.
  *
  * @param sfRequest $request
  */
  public function bindAll($request)
  {
    if ($this->memberForm) {
      $this->memberForm->bind($request->getParameter('member'));
    }

    if ($this->profileForm) {
      $this->profileForm->bind($request->getParameter('profile'));
    }

    if ($this->configForm) {
      $this->configForm->bind($request->getParameter('member_config'));
    }

    $this->bind($request->getParameter('auth'));
  }

 /**
  * Returns true if the form is valid.
  *
  * @return bool true if form is valid, false otherwise.
  */
  public function isValidAll()
  {
    if ($this->memberForm && !$this->memberForm->isValid()) {
      return false;
    }

    if ($this->profileForm && !$this->profileForm->isValid()) {
      return false;
    }

    if ($this->configForm && !$this->configForm->isValid()) {
      return false;
    }

    return $this->isValid();
  }

 /**
  * @todo removes this method.
  */
  public function isUtn()
  {
    return false;
  }

  public function getIterator()
  {
    return $this->getFormFieldSchema();
  }
}
