<?php

/**
 * CommunityMember form.
 *
 * @package    form
 * @subpackage CommunityMember
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 * @auther     Shinichi Urabe <urabe@tejimaya.net>
 */
class CommunityMemberForm extends BaseCommunityMemberForm
{
  protected
    $configSettings = array(),
    $communityMember,
    $isNew = false,
    $isAutoGenerate = true;

  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    unset(
      $this['community_id'],
      $this['member_id'],
      $this['position'],
      $this['created_at'],
      $this['updated_at']
    );

    $this->setConfigSettings();

    if ($this->isAutoGenerate)
    {
      $this->generateConfigWidgets();
    }

    $this->widgetSchema->setNameFormat('community_member[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

  }

  public function generateConfigWidgets()
  {
    foreach ($this->configSettings as $key => $value)
    {
      $this->setConfigWidget($key);
    }
  }

  public function setConfigWidget($name)
  {
    $config = $this->configSettings[$name];
    $this->widgetSchema[$name] = opFormItemGenerator::generateWidget($config);
    $this->widgetSchema->setLabel($name, $config['Caption']);
    $this->validatorSchema[$name] = opFormItemGenerator::generateValidator($config);
  }

  public function setConfigSettings($category = '')
  {
    $configs = sfConfig::get('openpne_community_notification_config');
    $categorys = sfConfig::get('openpne_community_notification_category');
    if (!$category)
    {
      $this->configSettings = $configs;
      return true;
    }

    foreach ($categories[$category] as $value)
    {
      $this->configSettings[$value] = $configs[$value];
    }
  }
}
