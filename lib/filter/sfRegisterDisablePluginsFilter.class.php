<?php

/**
 * sfRegisterDisablePluginsFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
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
    $disablePlugins = sfConfig::get('sf_openpne_disabled_plugins', array());

    // auth
    $authPlugins = sfFinder::type('directory')->name('opAuth*Plugin')->in(sfConfig::get('sf_plugins_dir'));
    $authMode = OpenPNEConfig::get(sfConfig::get('sf_app') . '_auth_mode');
    foreach ($authPlugins as $authPlugin) {
      if ('opAuth' . $authMode . 'Plugin' !== basename($authPlugin)) {
        $disablePlugins[] = basename($authPlugin);
      }
    }

    sfConfig::set('sf_openpne_disabled_plugins', $disablePlugins);

    $filterChain->execute();
  }
}
