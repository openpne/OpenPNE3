<?php

/**
 * sfOpenPNEApplicationConfiguration represents a configuration for OpenPNE application.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
abstract class sfOpenPNEApplicationConfiguration extends sfApplicationConfiguration
{
  /**
   * Configures the current configuration.
   */
  public function initialize()
  {
    sfConfig::set('sf_openpne_plugins_dir', sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'op_plugins');
  }

  /**
   * Gets directories where controller classes are stored for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of directories
   */
  public function getControllerDirs($moduleName)
  {
    $dirs = array();

    if ($opPluginDirs = glob(sfConfig::get('sf_openpne_plugins_dir').'/*/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/actions'))
    {
      $dirs = array_merge($dirs, array_combine($opPluginDirs, array_fill(0, count($opPluginDirs), false))); // OpenPNE plugins
    }

    $dirs = array_merge($dirs, parent::getControllerDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets directories where template files are stored for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of directories
   */
  public function getTemplateDirs($moduleName)
  {
    $dirs = array();

    if ($opPluginDirs = glob(sfConfig::get('sf_openpne_plugins_dir').'/*/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/templates'))
    {
      $dirs = array_merge($dirs, $opPluginDirs); // OpenPNE plugins
    }

    $dirs = array_merge($dirs, parent::getTemplateDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets the i18n directories to use globally.
   *
   * @return array An array of i18n directories
   */
  public function getI18NGlobalDirs()
  {
    $dirs = glob(sfConfig::get('sf_openpne_plugins_dir').'/*/i18n');

    $dirs = array_merge($dirs, parent::getI18NGlobalDirs());

    return $dirs;
  }

  /**
   * Gets the i18n directories to use for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of i18n directories
   */
  public function getI18NDirs($moduleName)
  {
    $dirs = array();

    if ($opPluginDirs = glob(sfConfig::get('sf_openpne_plugins_dir').'/*/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/i18n'))
    {
      $dirs = array_merge($dirs, $opPluginDirs); // OpenPNE plugins
    }

    $dirs = array_merge($dirs, parent::getI18NDirs($moduleName));

    return $dirs;
  }
}
