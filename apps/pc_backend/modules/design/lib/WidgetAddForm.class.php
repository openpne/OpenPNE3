<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Widget Add Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class WidgetAddForm extends sfForm
{
  public function configure()
  {
    $widgets = $this->getOption('current_widgets', array());

    foreach ($widgets as $key => $value)
    {
      $this->setValidator($key, new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    }

    $this->getWidgetSchema()->setNameFormat('new[%s]');
  }

  public function save()
  {
    foreach ($this->values as $type => $widgets)
    {
      if (!$widgets)
      {
        continue;
      }

      foreach ($widgets as $value)
      {
        $widget = new HomeWidget();
        $widget->setType($type);
        $widget->setName($value);
        $widget->save();
      }
    }
  }

  public function validate($validator, $value)
  {
    $result = array();

    foreach ($value as $key => $item)
    {
      if (array_key_exists($item, sfConfig::get('op_widget_list'))
        || array_key_exists($item, sfConfig::get('op_mobile_widget_list'))
        || array_key_exists($item, sfConfig::get('op_side_banner_widget_list')))
      {
        $result[] = $item;
      }
    }

    return $result;
  }
}
