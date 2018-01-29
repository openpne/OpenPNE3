<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTimelinePluginConfigurationForm
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 * @author     tatsuya ichikawa <ichikawa@tejimaya.com>
 */
class opTimelinePluginConfigurationForm extends BaseForm
{
  public function configure()
  {
    $choices = array('1' => '表示する', '0' => '表示しない');

    $this->setWidget('view_photo', new sfWidgetFormSelectRadio(array('choices' => $choices)));
    $this->setValidator('view_photo', new sfValidatorChoice(array('choices' => array_keys($choices))));
    $this->setDefault('view_photo', Doctrine::getTable('SnsConfig')->get('op_timeline_plugin_view_photo', '1'));
    $this->widgetSchema->setLabel('view_photo', '画像表示');
    $this->widgetSchema->setHelp('view_photo', '画像URLに自動でimgタグを付けない場合はOFFに設定して下さい。デフォルトはON');

    if (version_compare(OPENPNE_VERSION, '3.6beta1-dev', '<'))
    {
      unset($this['view_photo']);
    }

    $this->widgetSchema->setNameFormat('op_timeline_plugin[%s]');
  }

  public function save()
  {
    $names = array('view_photo');

    foreach ($names as $name)
    {
      if (!is_null($this->getValue($name)))
      {
        Doctrine::getTable('SnsConfig')->set('op_timeline_plugin_'.$name, $this->getValue($name));
      }
    }
  }
}
