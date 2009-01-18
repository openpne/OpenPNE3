<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Widget Sort Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class WidgetSortForm extends sfForm
{
  public function configure()
  {
    $widgets = $this->getOption('current_widgets', array());

    foreach ($widgets as $key => $value)
    {
      $this->setValidator($key, new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    }

    $this->getWidgetSchema()->setNameFormat('widget[%s]');
  }

  public function save()
  {
    foreach ($this->values as $type => $widgets)
    {
      $ids = HomeWidgetPeer::getWidgetsIds($type);
      if (!$widgets)
      {
        $widgets = array();
      }

      foreach ($ids as $id)
      {
        $widget = HomeWidgetPeer::retrieveByPk($id);
        $key = array_search($id, $widgets);

        if ($key === false)
        {
          $widget->delete();
          continue;
        }

        if ($widget)
        {
          $sortOrder = ((int)$key + 1) * 10;
          $widget->setSortOrder($sortOrder);
          $widget->save();
        }
      }
    }
  }

  public function validate($validator, $value)
  {
    $result = array();

    foreach ($value as $id)
    {
      $widget = HomeWidgetPeer::retrieveByPk($id);
      if ($widget) 
      {
        if (array_key_exists($widget->getName(), sfConfig::get('op_widget_list'))
          || array_key_exists($widget->getName(), sfConfig::get('op_mobile_widget_list'))
          || array_key_exists($widget->getName(), sfConfig::get('op_side_banner_widget_list')))
        {
          $result[] = $id;
        }
      }
    }

    return $result;
  }
}
