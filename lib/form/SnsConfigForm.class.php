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
class SnsConfigForm extends OpenPNEFormAutoGenerate
{
  public function configure()
  {
    foreach (sfConfig::get('openpne_sns_config') as $key => $value)
    {
      $this->setWidget($key, $this->generateWidget($value));
      $this->setValidator($key, $this->generateValidator($value));
      $this->widgetSchema->setLabel($key, $value['Caption']);
      $this->setDefault($key, opConfig::get($key));
    }

    $this->widgetSchema->setNameFormat('sns_config[%s]');
  }

  public function save()
  {
    $config = sfConfig::get('openpne_sns_config');
    foreach ($this->getValues() as $key => $value)
    {
      $snsConfig = SnsConfigPeer::retrieveByName($key);
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
