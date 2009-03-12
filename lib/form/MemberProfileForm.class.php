<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberProfile form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberProfileForm extends sfForm
{
  public function __construct($profileMember = array(), $options = array(), $CSRFSecret = null)
  {
    parent::__construct(array(), $options, $CSRFSecret);

    $profiles = ProfilePeer::doSelect(new Criteria());

    foreach ($profileMember as $profile)
    {
      $this->setDefault($profile->getName(), array(
        'value' => $profile->getValue(),
        'public_flag' => $profile->getPublicFlag()
      ));
    }
  }

  public function configure()
  {
    $this->widgetSchema->setNameFormat('profile[%s]');
  }

  public function save($memberId)
  {
    $values = $this->getValues();

    foreach ($values as $key => $value)
    {
      $profile = ProfilePeer::retrieveByName($key);
      if (!$profile)
      {
        continue;
      }

      $formType = $profile->getFormType();

      $memberProfile = MemberProfilePeer::retrieveByMemberIdAndProfileId($memberId, $profile->getId());
      if ($memberProfile)
      {
        $memberProfile->clearChildren();
      }
      $memberProfile = MemberProfilePeer::makeRoot($memberId, $profile->getId());

      if ($profile->isMultipleSelect())
      {
        $ids = array();
        $_values = array();
        if ('date' === $formType)
        {
          $_values = explode('-', $value['value']);
          $c = new Criteria();
          $c->addAscendingOrderByColumn(ProfileOptionPeer::SORT_ORDER);
          $options = $profile->getProfileOptions($c);
          foreach ($options as $option)
          {
            $ids[] = $option->getId();
          }
        }
        else
        {
          $ids = $value['value'];
        }
        MemberProfilePeer::createChild($memberProfile, $memberId, $profile->getId(), $ids, $_values);
      }
      else
      {
        $memberProfile->setValue($value['value']);
      }
      
      $memberProfile->setPublicFlag(1);
      if (isset($value['public_flag']))
      {
        $memberProfile->setPublicFlag($value['public_flag']);
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

  public function setAllWidgets()
  {
    $profiles = ProfilePeer::retrievesAll();
    $this->setProfileWidgets($profiles);
  }

  protected function setProfileWidgets($profiles)
  {
    foreach ($profiles as $profile)
    {
      $profile_i18n = $profile->getProfileI18ns();
      $profileWithI18n = $profile->toArray() + $profile_i18n[0]->toArray();
      
      $widgetOptions = array(
        'widget' => opFormItemGenerator::generateWidget($profileWithI18n, $this->getFormOptionsValue($profile->getId())),
        'is_edit_public_flag' => $profile->getIsEditPublicFlag(),
      );
      $validatorOptions = array(
        'validator' => opFormItemGenerator::generateValidator($profileWithI18n, $this->getFormOptions($profile->getId())),
        'is_edit_public_flag' => $profile->getIsEditPublicFlag(),
      );

      $this->widgetSchema[$profile->getName()] = new opWidgetFormProfile($widgetOptions);
      $this->validatorSchema[$profile->getName()] = new opValidatorProfile($validatorOptions);
    }
  }

  private function getFormOptions($profileId)
  {
    $result = array();
    $options = ProfileOptionPeer::retrieveByProfileId($profileId);

    foreach ($options as $option)
    {
      $result[] = $option->getId();
    }

    return $result;
  }

  private function getFormOptionsValue($profileId)
  {
    $result = array();
    $options = ProfileOptionPeer::retrieveByProfileId($profileId);

    foreach ($options as $option)
    {
      $result[$option->getId()] = $option->getValue();
    }

    return $result;
  }

  private function updateDefaultsFromObject($obj)
  {
    $this->setDefaults(array_merge($this->getDefaults(), $obj->toArray(BasePeer::TYPE_FIELDNAME)));
  }
}
