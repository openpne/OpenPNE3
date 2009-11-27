<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opSkinClassicPresetForm extends sfForm
{
  public function configure()
  {
    $defaults = opSkinClassicConfig::getDefaults();
    $list = array();

    foreach ($defaults as $k => $v)
    {
      $list[$k] = $v['caption'];
    }

    $this->setWidget('theme', new sfWidgetFormSelect(array('choices' => $list)));
    $this->setValidator('theme', new sfValidatorChoice(array('choices' => array_keys($list))));
    $this->setDefault('theme', opSkinClassicConfig::getCurrentTheme());

    $this->widgetSchema->setNameFormat('preset[%s]');
  }

  public function save()
  {
    opSkinClassicConfig::set('theme', $this->getValue('theme'));

    foreach (opSkinClassicConfig::getAllowdColors() as $k)
    {
      opSkinClassicConfig::delete($k);
    }

    $configs = array_merge(opSkinClassicConfig::getImages(), opSkinClassicConfig::getThemeImages());
    foreach ($configs as $k)
    {
      $key = $k.'_image';
      $rawConfig = Doctrine::getTable('SkinConfig')->retrieveByPluginAndName('opSkinClassicPlugin', $key);
      if ($rawConfig)
      {
        $file = Doctrine::getTable('File')->findOneByName($rawConfig->value);
        if ($file)
        {
          $file->delete();
        }
      }

      opSkinClassicConfig::delete($key);
    }

    opToolkit::clearCache();
  }
}
