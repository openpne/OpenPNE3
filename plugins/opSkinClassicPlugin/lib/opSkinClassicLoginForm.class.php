<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opSkinClassicLoginForm extends sfForm
{
  public function configure()
  {
    $list = array(
      'default' => '標準のログイン画面を使用する',
      'classic' => '旧式のログイン画面を使用する',
    );
    $default = 'default';
    if ('@opSkinClassicPlugin_login' === opConfig::get('external_pc_login_url'))
    {
      $default = 'classic';
    }

    $this->setWidget('login', new sfWidgetFormSelect(array('choices' => $list)));
    $this->setValidator('login', new sfValidatorChoice(array('choices' => array_keys($list))));
    $this->setDefault('login', $default);

    $this->widgetSchema->setNameFormat('login[%s]');
  }

  public function save()
  {
    if ('classic' === $this->getValue('login'))
    {
      Doctrine::getTable('SnsConfig')->set('external_pc_login_url', '@opSkinClassicPlugin_login');
    }
    else
    {
      if ('@opSkinClassicPlugin_login' === opConfig::get('external_pc_login_url'))
      {
        Doctrine::getTable('SnsConfig')->set('external_pc_login_url', '');
      }
    }
  }
}
