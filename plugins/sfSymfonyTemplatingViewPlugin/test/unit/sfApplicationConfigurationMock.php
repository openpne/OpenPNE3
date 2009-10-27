<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class ApplicationConfigurationMock extends ProjectConfiguration
{
  static protected $loadedHelpers = array();

  public function getTemplateDir($moduleName, $templateFile)
  {
    return dirname(__FILE__).'/fixtures/'.$moduleName;
  }

  public function getTemplateDirs($moduleName)
  {
    return array(dirname(__FILE__).'/fixtures/'.$moduleName);
  }

  public function getDecoratorDirs()
  {
    return array(dirname(__FILE__).'/fixtures/template');
  }

  public function getDecoratorDir($template)
  {
    foreach ($this->getDecoratorDirs() as $dir)
    {
      if (is_readable($dir.'/'.$template))
      {
        return $dir;
      }
    }
  }

  public function loadHelpers($helpers, $moduleName = '')
  {
    $dir = sfConfig::get('sf_symfony_lib_dir').'/helper';
    foreach ((array) $helpers as $helperName)
    {
      $fileName = $helperName.'Helper.php';

      if (isset(self::$loadedHelpers[$helperName]))
      {
        continue;
      }

      if (is_readable($dir.'/'.$fileName))
      {
        include_once($dir.'/'.$fileName);
      }
      else
      {
        throw new InvalidArgumentException(sprintf('Unable to load "%sHelper.php" helper in: %s.', $helperName, implode(', ', $dirs)));
      }

      self::$loadedHelpers[$helperName] = true;
    }
  }
}

