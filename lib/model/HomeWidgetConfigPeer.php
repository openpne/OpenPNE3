<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
