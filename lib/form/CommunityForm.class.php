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
    unset($this['created_at'], $this['updated_at']);

    $this->setValidator('config', new sfValidatorPass());
  }

  public function save($con = null)
  {
    $community = parent::save($con);
    $tainted_values = $this->getTaintedValues();
    $community_config_array = $tainted_values['config'];

    if ($this->isNew()) {
      $communityMember = new CommunityMember();
      $communityMember->setPosition('admin');
      $communityMember->setMemberId(sfContext::getInstance()->getUser()->getMemberId());
      $communityMember->setCommunityId($community->getId());
      $communityMember->save();

      foreach ($community_config_array as $community_config_key => $community_config_value)
      {
        $community_config = new CommunityConfig();
        $community_config->setCommunityId($community->getId());
        $community_config->setName($community_config_key);
        $community_config->setValue($community_config_value);
        $community_config->save();
      }
    }
    else
    {
      foreach ($community_config_array as $community_config_key => $community_config_value)
      {
        $community_config = CommunityConfigPeer::retrieveByNameAndCommunityId($community_config_key, $community->getId());
        $community_config->setValue($community_config_value);
        $community_config->save();
      }
    }

    return $community;
  }
}
