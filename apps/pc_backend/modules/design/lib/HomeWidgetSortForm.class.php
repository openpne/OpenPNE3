<?php

/**
 * Home Widget Sort Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class HomeWidgetSortForm extends sfForm
{
  public function configure()
  {
    $this->setValidator('top', new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    $this->setValidator('sideMenu', new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    $this->setValidator('contents', new sfValidatorCallback(array('callback' => array($this, 'validate'))));
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
          var_dump($widget, $widgets);
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
      if ($widget && array_key_exists($widget->getName(), sfConfig::get('op_widget_list')))
      {
        $result[] = $id;
      }
    }

    return $result;
  }
}
