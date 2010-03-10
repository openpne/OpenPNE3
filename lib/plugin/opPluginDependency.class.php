<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The opPluginDependency class
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opPluginDependency extends PEAR_Dependency2
{
  public $failedDependency = array(
    'php' => array(),
    'pearinstaller' => array(),
    'extension' => array(),
    'package' => array(),
  );

  public function validatePackageDependency($dep, $required, $params, $depv1 = false)
  {
    if ('symfony' === strtolower($dep['name']))
    {
      return true;
    }

    if ('openpne' === strtolower($dep['name']))
    {
      $extra = $this->_getExtraString($dep);

      if (isset($dep['min']) && !version_compare(OPENPNE_VERSION, $dep['min'], '>='))
      {
        return $this->handleOpenPNEDependencyError('%s requires OpenPNE'.$extra.', installed version is '.OPENPNE_VERSION);
      }

      if (isset($dep['max']) && !version_compare(OPENPNE_VERSION, $dep['max'], '<='))
      {
        return $this->handleOpenPNEDependencyError('%s requires OpenPNE'.$extra.', installed version is '.OPENPNE_VERSION);
      }

      if (isset($dep['exclude']))
      {
        foreach ((array)$dep['exclude'] as $exclude)
        {
          if (version_compare(OPENPNE_VERSION, $exclude, '=='))
          {
            return $this->handleOpenPNEDependencyError('%s is not compatible with OpenPNE version '.$exclude);
          }
        }
      }

      return true;
    }

    $result = parent::validatePackageDependency($dep, $required, $params, $depv1);
    if (PEAR::isError($result))
    {
      $dep['package'] = $dep['name'];
      $this->failedDependency['package'][] = $dep;
    }

    return $result;
  }

  public function validateExtensionDependency($dep, $required = true)
  {
    $result = parent::validateExtensionDependency($dep, $required);
    if (PEAR::isError($result))
    {
      $this->failedDependency['extension'][] = $dep;
    }

    return $result;
  }

  public function validatePhpDependency($dep)
  {
    $result = parent::validatePhpDependency($dep);
    if (PEAR::isError($result))
    {
      $this->failedDependency['php'] = $dep;
    }

    return $result;
  }

  public function validatePearinstallerDependency($dep)
  {
    $result = parent::validatePearinstallerDependency($dep);
    if (PEAR::isError($result))
    {
      $this->failedDependency['pearinstaller'] = $dep;
    }

    return $result;
  }

  protected function handleOpenPNEDependencyError($message)
  {
    $_message = ' '.ucfirst(sprintf($message, 'this plugin'));

    $formatter = $this->getFormatter();
    if ($formatter)
    {
      $len = strlen($_message) + 4;
      $_message = str_repeat(' ', $len).PHP_EOL
                . $_message.str_repeat(' ', 4).PHP_EOL
                . str_repeat(' ', $len).PHP_EOL;

      echo $formatter->format($_message, 'ERROR', STDERR);
    }

    return $this->raiseError($message);
  }

  protected function getFormatter()
  {
    if ('cli' !== PHP_SAPI)
    {
      return null;
    }

    // It is not Windows, and the stdout is an interactive terminal
    if (DIRECTORY_SEPARATOR !== '\\' && function_exists('posix_isatty') && @posix_isatty(STDOUT))
    {
      return new sfAnsiColorFormatter();
    }

    return new sfFormatter();
  }

  public function hasFailedDependency()
  {
    return (
      $this->failedDependency['php'] ||
      $this->failedDependency['pearinstaller'] ||
      $this->failedDependency['extension'] ||
      $this->failedDependency['arch'] ||
      $this->failedDependency['os'] ||
      $this->failedDependency['subpackage'] ||
      $this->failedDependency['package']
    );
  }
}
