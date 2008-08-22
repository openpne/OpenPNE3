<?php
if (sfConfig::get('app_openpne_auth_mode') != 'PCAddress') {
  $disablePlugins = sfConfig::get('sf_openpne_disabled_plugins', array());
  $disablePlugins[] = 'opAuthPCAddressPlugin';
  sfConfig::set('sf_openpne_disabled_plugins', $disablePlugins);
}
