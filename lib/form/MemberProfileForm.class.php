<?php

/**
 * MemberProfile form.
 *
 * @package    form
 * @subpackage member_profile
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class MemberProfileForm extends sfForm
{
  public function __construct($profileMember = array(), $options = array(), $CSRFSecret = null)
  {
    parent::__construct(array(), $options, $CSRFSecret);

    foreach ($profileMember as $profile) {
      $this->setDefault($profile->getName(), $profile->getValue());
    }
  }

  public function configure()
  {
    $this->widgetSchema->setNameFormat('profile[%s]');
  }

  public function save($memberId)
  {
    $values = $this->getValues();

    foreach ($values as $key => $value) {
      $profile = ProfilePeer::retrieveByName($key);
      if (!$profile) {
        continue;
      }

      $formType = $profile->getFormType();

      $memberProfile = MemberProfilePeer::retrieveByMemberIdAndProfileId($memberId, $profile->getId());
      if (!$memberProfile) {
        $memberProfile = new MemberProfile();
      }

      $memberProfile->setMemberId($memberId);
      $memberProfile->setProfileId($profile->getId());
      if ($formType == 'checkbox' || $formType == 'select' || $formType == 'radio') {
        $memberProfile->setProfileOptionId($value);
      } else {
        $memberProfile->setValue($value);
      }

      $memberProfile->save();
    }

    return true;
  }

  public function setRegisterWidgets()
  {
    $profiles = ProfilePeer::retrieveByIsDispRegist();
    $this->setProfileWidgets($profiles);
  }

  public function setConfigWidgets()
  {
    $profiles = ProfilePeer::retrieveByIsDispConfig();
    $this->setProfileWidgets($profiles);
  }

  public function setSearchWidgets()
  {
    $profiles = ProfilePeer::retrieveByIsDispSearch();
    $this->setProfileWidgets($profiles);
  }

  private function setProfileWidgets($profiles)
  {
    foreach ($profiles as $profile) {
      $this->widgetSchema[$profile->getName()] = $this->generateWidget($profile);
      $this->validatorSchema[$profile->getName()] = $this->generateValidator($profile);
    }
  }

  private function generateWidget($profile)
  {
    $option = array();
    if ($profile->getCaption()) {
      $this->widgetSchema->setLabel($profile->getName(), $profile->getCaption());
    }

    switch ($profile->getFormType()) {
      case 'checkbox':
        $choices = $this->getFormOptionsValue($profile->getId());
        $obj = new sfWidgetFormInputCheckbox(array('choices' => $choices));
        break;
      case 'select':
        $choices = $this->getFormOptionsValue($profile->getId());
        $obj = new sfWidgetFormSelect(array('choices' => $choices));
        break;
      case 'radio':
        $choices = $this->getFormOptionsValue($profile->getId());
        $obj = new sfWidgetFormSelectRadio(array('choices' => $choices));
        break;
      case 'textarea':
        $obj = new sfWidgetFormTextarea();
        break;
      case 'password':
        $obj = new sfWidgetFormInputPassword();
        break;
      default:
        $obj = new sfWidgetFormInput();
    }

    return $obj;
  }

  private function generateValidator($profile)
  {
    $formType = $profile->getFormType();
    $valueType = $profile->getValueType();

    if ($formType == 'checkbox' || $formType == 'select' || $formType == 'radio') {
      $choices = $this->getFormOptions($profile->getId());
      $obj = new sfValidatorChoice(array('choices' => $choices));
      return $obj;
    }

    $option = array('required' => $profile->getIsRequired());
    switch ($valueType) {
      case 'datetime':
        $option['min'] = $profile->getValueMin();
        $option['max'] = $profile->getValueMax();
        $obj = new sfValidatorDatetime($option);
        break;
      case 'email':
        $obj = new sfValidatorEmail($option);
        break;
      case 'integer':
        $option['min'] = $profile->getValueMin();
        $option['max'] = $profile->getValueMax();
        $obj = new sfValidatorInteger($option);
        break;
      case 'regexp':
        $option['pattern'] = $profile->geValueRegexp();
        $obj = new sfValidatorInteger($option);
        break;
      case 'url':
        $obj = new sfValidatorUrl($option);
        break;
      default:
        $option['min_length'] = $profile->getValueMin();
        $option['max_length'] = $profile->getValueMax();
        $obj = new sfValidatorString($option);
    }

    return $obj;
  }

  private function getFormOptions($profileId)
  {
    $result = array();
    $options = ProfileOptionPeer::retrieveByIsProfileId($profileId);

    foreach ($options as $option) {
      $result[] = $option->getId();
    }

    return $result;
  }

  private function getFormOptionsValue($profileId)
  {
    $result = array();
    $options = ProfileOptionPeer::retrieveByIsProfileId($profileId);

    foreach ($options as $option) {
      $result[$option->getId()] = $option->getValue();
    }

    return $result;
  }

  private function updateDefaultsFromObject($obj)
  {
    $this->setDefaults(array_merge($this->getDefaults(), $obj->toArray(BasePeer::TYPE_FIELDNAME)));
    var_dump($obj->toArray(BasePeer::TYPE_FIELDNAME));
  }
}
