<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthForm represents a form to login.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class opAuthForm extends BaseForm
{
  public
    $memberForm,
    $profileForm,
    $configForm;

  protected
    $adapter = null;

  const AUTH_MODE_FIELD_NAME = 'authMode';

  /**
   * Constructor.
   *
   * @param opAuthAdapter $adapter  An opAuthAdapter object
   * @param array  $defaults    An array of field default values
   * @param array  $options     An array of options
   * @param string $CRFSSecret  A CSRF secret (false to disable CSRF protection, null to use the global CSRF secret)
   *
   * @see sfForm
   */
  public function __construct(opAuthAdapter $adapter, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->adapter = $adapter;

    parent::__construct($defaults, $options, false);

    $this->setWidget('next_uri', new opWidgetFormInputHiddenNextUri());
    $this->setValidator('next_uri', new opValidatorNextUri());
  }

 /**
  * Configures the current form.
  */
  public function configure()
  {
    $this->widgetSchema->setNameFormat('auth'.$this->getAuthMode().'[%s]');
  }

  public function getMember()
  {
    $member = $this->getValue('member');
    if ($member instanceof Member)
    {
      return $member;
    }

    return false;
  }

  public function getAuthAdapter()
  {
    return $this->adapter;
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
    unset($this['next_uri']);

    if (!$member)
    {
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
