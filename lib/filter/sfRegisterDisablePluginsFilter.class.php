<?php

/**
 * sfRegisterDisablePluginsFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfRegisterDisablePluginsFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    $configName = 'sf_'.sfConfig::get('sf_app').'_openpne_disabled_plugins';
    $disablePlugins = sfConfig::get($configName, array());

    // auth
    $authPlugins = sfFinder::type('directory')->name('opAuth*Plugin')->in(sfConfig::get('sf_plugins_dir'));
    $authModes = OpenPNEConfig::get(sfConfig::get('sf_app').'_auth_mode');
    foreach ($authPlugins as $authPlugin) {
      $disablePlugins[] = basename($authPlugin);
    }
    foreach ($authModes as $authMode) {
      $pluginName = 'opAuth'.$authMode.'Plugin';
      if ($pluginName === basename($authPlugin)) {
        $key = array_search($pluginName, $disablePlugins);
        unset($disablePlugins[$key]);
      }
    }

    sfConfig::set($configName, $disablePlugins);

    $filterChain->execute();
  }
}
