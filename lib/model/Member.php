<?php

/**
 * Subclass for representing a row from the 'member' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Member extends BaseMember
{
  public function getProfile($profileName, $isGetProfileOptionId = false)
  {
    $c = new Criteria();
    $c->add(ProfilePeer::NAME, $profileName);
    $c->add(MemberProfilePeer::MEMBER_ID, $this->getId());
    $c->addJoin(MemberProfilePeer::PROFILE_ID, ProfilePeer::ID);
    $profile = MemberProfilePeer::doSelectOne($c);

    if ($isGetProfileOptionId) {
      return $profile->getValue();
    }

    $formType = $profile->getProfile()->getFormType();
    if ($formType == 'radio' || $formType == 'checkbox' || $formType == 'select') {
      $option = ProfileOptionPeer::retrieveByPk($profile->getProfileOptionId());
      return $option->getValue();
    }

    return $profile->getValue();
  }
}
