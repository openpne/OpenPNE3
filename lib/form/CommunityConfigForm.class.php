<?php

/**
 * CommunityConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityConfigForm extends OpenPNEFormAutoGenerate
{
  protected
    $configSettings = array(),
    $category = '',
    $community,
    $isNew = false,
    $isAutoGenerate = true,
    $fieldName = 'config[%s]';

  public function configure()
  {
    $this->setCommunity($this->getOption('community'));

    $this->setConfigSettings();

    if ($this->isAutoGenerate)
    {
      $this->generateConfigWidgets();
    }

    $this->widgetSchema->setNameFormat('community_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

  public function setCommunity(Community $community)
  {
    $this->community = $community;
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
    $this->widgetSchema[sprintf($this->fieldName, $name)] = $this->generateWidget($config);
    $this->widgetSchema->setLabel(sprintf($this->fieldName, $name), $config['Caption']);
    $communityConfig = CommunityConfigPeer::retrieveByNameAndCommunityId($name, $this->community->getId());
    if ($communityConfig)
    {
      $this->setDefault(sprintf($this->fieldName, $name), $communityConfig->getValue());
    }
    $this->validatorSchema[sprintf($this->fieldName, $name)] = $this->generateValidator($config);
  }

  public function setConfigSettings($category = '')
  {
    $categories = sfConfig::get('openpne_community_category');
    $configs = sfConfig::get('openpne_community_config');

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

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $key = $this->getUnformattedFieldName($key);
      $config = CommunityConfigPeer::retrieveByNameAndCommunityId($key, $this->community->getId());
      if (!$config)
      {
        $config = new CommunityConfig();
        $config->setCommunity($this->community);
        $config->setName($key);
      }
      $config->setValue($value);
      $config->save();
    }
  }

  public function getUnformattedFieldName($field)
  {
    $regexp = '/'.str_replace(array('%s'), array('(\w+)'), preg_quote($this->fieldName)).'/';
    $matches = array();
    preg_match($regexp, $field, $matches);
    array_shift($matches);

    return implode('', $matches);
  }
}
