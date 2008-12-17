<?php

class HomeWidgetConfigPeer extends BaseHomeWidgetConfigPeer
{
  static public function retrieveByWidgetIdAndName($widgetId, $name)
  {
    $c = new Criteria();
    $c->add(HomeWidgetConfigPeer::HOME_WIDGET_ID, $widgetId);
    $c->add(HomeWidgetConfigPeer::NAME, $name);
    return self::doSelectOne($c);
  }
}
