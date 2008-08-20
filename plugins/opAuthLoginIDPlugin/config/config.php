<?php
if (sfConfig::get('app_openpne_auth_mode') != 'LoginID') {
  $disablePlugins = sfConfig::get('sf_openpne_disabled_plugins', array());
  $disablePlugins[] = 'opAuthLoginIDPlugin';
  sfConfig::set('sf_openpne_disabled_plugins', $disablePlugins);
}
