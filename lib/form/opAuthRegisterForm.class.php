<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthRegisterForm represents a form to register.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opAuthRegisterForm extends BaseForm
{
  public
    $memberForm,
    $profileForm,
    $configForm;

  protected
    $member = null;

  /**
   * Constructor.
   *
   * @param array  $defaults    An array of field default values
   * @param array  $options     An array of options
   * @param string $CRFSSecret  A CSRF secret (false to disable CSRF protection, null to use the global CSRF secret)
   *
   * @see sfForm
   */
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    if (isset($options['member']) && $options['member'] instanceof Member)
    {
      $this->setMember($options['member']);
    }
    else
    {
      $this->setMember(Doctrine::getTable('Member')->createPre());
    }

    $this->memberForm = new MemberForm($this->getMember(), array(), false);
    $this->profileForm = new MemberProfileForm($this->getMember()->getProfiles(), array(), false);
    $this->profileForm->setRegisterWidgets();
    $this->configForm = new MemberConfigForm($this->getMember(), array(), false);

    parent::__construct($defaults, $options, false);

    $this->setValidator('mobile_uid', new sfValidatorPass());
    $this->setValidator('mobile_cookie_uid', new sfValidatorPass());

    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateMobileUID'))));

    $this->widgetSchema->setNameFormat('auth[%s]');
  }

  public function renderFormTag($url, array $attributes = array())
  {
    $result = parent::renderFormTag($url, $attributes);

    if (sfConfig::get('app_is_mobile') && opConfig::get('retrieve_uid'))
    {
      $pos = strpos($result, '>');
      $head = substr($result, 0, $pos);
      $foot = substr($result, $pos);

      $result = $head.' utn'.$foot;
    }

    return $result;
  }

  /**
   * Returns the current member.
   *
   * @return Member
   */
  public function getMember()
  {
    return $this->member;
  }

  /**
   * Sets the member.
   *
   * @param Member
   */
  public function setMember(Member $member)
  {
    $this->member = $member;
  }

 /**
  * Returns the string representation of the form(s).
  *
  * @return string HTML for the form(s).
  */
  public function __toString()
  {
    $result = (string)$this->memberForm
            . (string)$this->configForm
            . parent::__toString()
            . (string)$this->profileForm;
    return $result;
  }

 /**
  * Binds the form with request parameters.
  *
  * @param sfRequest $request
  */
  public function bindAll($request)
  {
    $this->memberForm->bind($request->getParameter('member'));
    $this->profileForm->bind($request->getParameter('profile'));
    $this->configForm->bind($request->getParameter('member_config'));
    $this->bind($request->getParameter('auth', array(
      'mobile_uid'        => '',
      'mobile_cookie_uid' => '',
    )));
  }

  public function validateMobileUID($validator, $values, $arguments = array())
  {
    if (!opConfig::get('retrieve_uid'))
    {
      return $values;
    }

    if (sfConfig::get('app_is_mobile', false))
    {
      $request = sfContext::getInstance()->getRequest();
      $uid = $request->getMobileUID();
      if (!$uid && opConfig::get('retrieve_uid') >= 2)
      {
        throw new sfValidatorError($validator, 'A mobile UID is required. Please check settings of your mobile phone and retry.');
      }
      elseif (Doctrine::getTable('Blacklist')->retrieveByUid($uid))
      {
        throw new sfValidatorError($validator, 'A mobile UID is invalid.');
      }

      $cookieUid = sfContext::getInstance()->getResponse()->generateMobileUidCookie();
      if ($cookieUid)
      {
        $values['mobile_cookie_uid'] = $cookieUid;
      }

      $values['mobile_uid'] = $uid;
    }

    return $values;
  }

  public function save()
  {
    $member = $this->memberForm->save();
    $this->setMember($member);

    $profile = $this->profileForm->save($this->getMember()->getId());
    $config = $this->configForm->save($this->getMember()->getId());
    $auth = $this->doSave();

    if ($member && $profile && $auth && $config)
    {
      if ($this->getValue('mobile_uid'))
      {
        $this->getMember()->setConfig('mobile_uid', $this->getValue('mobile_uid'));
      }

      if ($this->getValue('mobile_cookie_uid'))
      {
        $this->getMember()->setConfig('mobile_cookie_uid', $this->getValue('mobile_cookie_uid'));
      }

      $communities = Doctrine::getTable('Community')->getDefaultCommunities();
      if($communities)
      {
        foreach ($communities as $community)
        {
          Doctrine::getTable('CommunityMember')->join($this->getMember()->getId(), $community->getId());
        }
      }

      if (sfConfig::get('op_is_mail_address_contain_hash'))
      {
        $str = opToolkit::generatePasswordString(sfConfig::get('op_mail_address_hash_length', 12), false);
        $this->getMember()->setConfig('mail_address_hash', strtolower($str));
      }

      return $this->getMember()->getId();
    }

    return false;
  }

  public function doSave()
  {
    return true;
  }

 /**
  * Returns true if the form is valid.
  *
  * @return bool true if form is valid, false otherwise.
  */
  public function isValidAll()
  {
    return ($this->memberForm->isValid()
      && $this->profileForm->isValid()
      && $this->configForm->isValid()
      && $this->isValid()
    );
  }

  public function getIterator()
  {
    return $this->getFormFieldSchema();
  }

  public function getAllForms()
  {
    return array($this->memberForm, $this->profileForm, $this, $this->configForm);
  }
}
