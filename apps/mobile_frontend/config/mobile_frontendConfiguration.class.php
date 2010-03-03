<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once dirname(__FILE__).'/../../../lib/config/opApplicationConfiguration.class.php';

class mobile_frontendConfiguration extends opApplicationConfiguration
{
  public function initialize()
  {
    parent::initialize();

    if (!opMobileUserAgent::getInstance()->isCookie())
    {
      ini_set('session.use_only_cookies', 0);
      ini_set('session.use_cookies', 0);
      ini_set('session.use_trans_sid', 1);
    }

    sfWidgetFormSchema::setDefaultFormFormatterName('mobile');
  }
}
