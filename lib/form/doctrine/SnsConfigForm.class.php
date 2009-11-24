<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * SnsConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SnsConfigForm extends BaseForm
{
  public function configure()
  {
    $snsConfig = sfConfig::get('openpne_sns_config');
    $category = sfConfig::get('openpne_sns_category');
    if (empty($category[$this->getOption('category')]))
    {
      return false;
    }

    foreach ($category[$this->getOption('category')] as $configName)
    {
      if (empty($snsConfig[$configName]))
      {
        continue;
      }

      $this->setWidget($configName, opFormItemGenerator::generateWidget($snsConfig[$configName]));
      $this->setValidator($configName, opFormItemGenerator::generateValidator($snsConfig[$configName]));
      $this->widgetSchema->setLabel($configName, $snsConfig[$configName]['Caption']);
      if (isset($snsConfig[$configName]['Help']))
      {
        $this->widgetSchema->setHelp($configName, $snsConfig[$configName]['Help']);
      }

      $value = opConfig::get($configName);
      if ($value instanceof sfOutputEscaperArrayDecorator)
      {
        $value = $value->getRawValue();
      }

      $this->setDefault($configName, $value);
    }

    $this->widgetSchema->setNameFormat('sns_config[%s]');
  }

  public function save()
  {
    $config = sfConfig::get('openpne_sns_config');
    foreach ($this->getValues() as $key => $value)
    {
      $snsConfig = Doctrine::getTable('SnsConfig')->retrieveByName($key);
      if (!$snsConfig)
      {
        $snsConfig = new SnsConfig();
        $snsConfig->setName($key);
      }
      $snsConfig->setValue($value);
      $snsConfig->save();
    }
  }
}
