<?php

/**
 * Community form.
 *
 * @package    form
 * @subpackage community
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class CommunityForm extends BaseCommunityForm
{
  public function configure()
  {
  }

  public function save($con = null)
  {
    $community = parent::save($con);

    if ($this->isNew()) {
      $communityMember = new CommunityMember();
      $communityMember->setPosition('admin');
      $communityMember->setMemberId(sfContext::getInstance()->getUser()->getMemberId());
      $communityMember->setCommunityId($community->getId());
      $communityMember->save();
    }

    return $community;
  }
}
