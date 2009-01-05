<?php

/**
 * sfOpenPNEFilterConfigHandler allows you to register filters with the system.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEFilterConfigHandler extends sfFilterConfigHandler
{
  /**
   * @see sfFilterConfigHandler::addFilter()
   */
  protected function addFilter($category, $class, $parameters)
  {
    $result = parent::addFilter($category, $class, $parameters);
    if ('execution' === $category) {
        $result = $this->addActionFilter($category, $class, $parameters).$result;
    }
    return $result;
  }

  /**
   * Adds an action filter statement to the data.
   *
   * @param string The category name
   * @param string The filter class name
   * @param array  Filter default parameters
   *
   * @return string The PHP statement
   */
  protected function addActionFilter($category, $class, $parameters)
  {
    $finder = sfFinder::type('file')->name('*.php');
    $files = array();
    $data = '';

    // module filter directories
    if ($matches = glob(sfConfig::get('sf_app_module_dir').'/*/filters')) {
      $files = array_merge($files, $finder->in($matches));
    }

    // plugin module filter directories
    if ($matches = glob(sfConfig::get('sf_plugins_dir').'/*/apps/'.sfConfig::get('sf_app').'/modules/*/filters')) {
      $files = array_merge($files, $finder->in($matches));
    }

    $regex = '~^\s*(?:abstract\s+|final\s+)?(?:class|interface)\s+(\w+)~mi';
    foreach ($files as $file)
    {
      $modulesDirPos = strrpos($file, 'modules/') + strlen('modules/');
      $moduleNameLength = strpos($file, '/', $modulesDirPos) - $modulesDirPos;
      $moduleName = substr($file, $modulesDirPos, $moduleNameLength);

      preg_match_all($regex, file_get_contents($file), $classes);
      foreach ($classes[1] as $class) {
        $data .= <<<EOF
if ('$moduleName' == \$actionInstance->getModuleName()) {
  require_once '$file';
  {$this->addFilter($class, $class, $parameters)}
}
EOF;
      }
    }

    return $data;
  }
}
