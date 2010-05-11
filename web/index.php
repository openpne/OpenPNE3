<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

// load opMobileUserAgent before initializing application
$old_error_level = error_reporting();

error_reporting($old_error_level & ~(E_STRICT | E_DEPRECATED));

set_include_path(dirname(__FILE__).'/../lib/vendor/PEAR/'.PATH_SEPARATOR.get_include_path());
require_once(dirname(__FILE__).'/../lib/util/opMobileUserAgent.class.php');

$is_mobile = !opMobileUserAgent::getInstance()->getMobile()->isNonMobile();

error_reporting($old_error_level);

if ($is_mobile)
{
  $configuration = ProjectConfiguration::getApplicationConfiguration('mobile_frontend', 'prod', false);
}
else
{
  $configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'prod', false);
}

sfContext::createInstance($configuration)->dispatch();
