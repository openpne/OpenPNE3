<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once dirname(__FILE__).'/../../../lib/config/sfOpenPNEApplicationConfiguration.class.php';

class pc_frontendConfiguration extends sfOpenPNEApplicationConfiguration
{
  public function configure()
  {
    sfConfig::set('op_is_use_captcha', true);
  }

  public function initialize()
  {
    parent::initialize();

    sfWidgetFormSchema::setDefaultFormFormatterName('pc');
  }
}
