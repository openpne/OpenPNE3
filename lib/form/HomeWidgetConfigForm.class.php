<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * HomeWidgetConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class HomeWidgetConfigForm extends sfForm
{
  protected $homeWidget;

  public function __construct(HomeWidget $homeWidget, $options = array(), $CSRFSecret = null)
  {
    $this->homeWidget = $homeWidget;

    parent::__construct(array(), $options, $CSRFSecret);

    $config = sfConfig::get('op_widget_list', array());
    if (empty($config[$homeWidget->getName()]['config']))
    {
      throw new RuntimeException('The widget has not registered or it doesn\'t have any configuration items.');
    }

    $widgetConfig = $config[$homeWidget->getName()]['config'];
    foreach ($widgetConfig as $key => $value)
    {
      $this->setWidget($key, opFormItemGenerator::generateWidget($value));
      $this->setValidator($key, opFormItemGenerator::generateValidator($value));

      $config = HomeWidgetConfigPeer::retrieveByWidgetIdAndName($homeWidget->getId(), $key);
      if ($config)
      {
        $this->setDefault($key, $config->getValue());
      }
    }

    $this->widgetSchema->setNameFormat('home_widget_config[%s]');
  }

  public function save()
  {
    foreach ($this->values as $key => $value)
    {
      $homeWidgetConfig = HomeWidgetConfigPeer::retrieveByWidgetIdAndName($this->homeWidget->getId(), $key);
      if (!$homeWidgetConfig)
      {
        $homeWidgetConfig = new HomeWidgetConfig();
        $homeWidgetConfig->setHomeWidget($this->homeWidget);
        $homeWidgetConfig->setName($key);
      }
      $homeWidgetConfig->setValue($value);
      $homeWidgetConfig->save();
    }
  }
}
