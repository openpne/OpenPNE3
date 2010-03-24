<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opPluginDefineTask extends sfBaseTask
{
  protected $pluginManager = null;

  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
      new sfCommandArgument('version', sfCommandArgument::REQUIRED, 'The plugin version'),
      new sfCommandArgument('stability', sfCommandArgument::REQUIRED, 'The plugin stability'),
      new sfCommandArgument('note', sfCommandArgument::REQUIRED, 'The plugin release note'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('channel', 'c', sfCommandOption::PARAMETER_REQUIRED, 'The PEAR channel name', null),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'define';
    $this->briefDescription = 'Creates the plugin definition file "package.xml"';
    $this->detailedDescription = <<<EOF
The [opPlugin:define|INFO] task creates the plugin definition file "package.xml".
Call it with:

  [./symfony opPlugin:define opSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // Remove E_STRICT and E_DEPRECATED from error_reporting
    error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

    if (empty($options['channel']))
    {
      $options['channel'] = opPluginManager::getDefaultPluginChannelServerName();
    }

    require_once 'PEAR/PackageFileManager2.php';

    $pluginName = $arguments['name'];
    $pluginDirectory = sfConfig::get('sf_plugins_dir').'/'.$pluginName.'/';

    $info = $this->getPluginManager($options['channel'])->getPluginInfo($pluginName);
    if (!$info)
    {
      $info = array(
        'n' => $pluginName,
        'c' => $options['channel'],
        'l' => 'Apache',
        's' => $pluginName,
        'd' => $pluginName,
      );
    }

    $packageXml = new PEAR_PackageFileManager2();
    $packageOptions = array(
      'packagedirectory'  => $pluginDirectory,
      'filelistgenerator' => 'file',
      'baseinstalldir'    => '/',
    );

    $e = $packageXml->setOptions($packageOptions);
    if (PEAR::isError($e))
    {
      echo $e->getMessage();
      exit;
    }

    $packageXml->_options['roles'] = array('*' => 'data');

    $maintainers = $this->getPluginManager()->getPluginMaintainer($pluginName);
    foreach ($maintainers as $maintainer)
    {
      $packageXml->addMaintainer($maintainer['r'], $maintainer['h'], $maintainer['n'], '');
    }

    $packageXml->setPackage($pluginName);
    $packageXml->setChannel($options['channel']);
    $packageXml->setReleaseVersion($arguments['version']);
    $packageXml->setReleaseStability($arguments['stability']);
    $packageXml->setApiVersion($arguments['version']);
    $packageXml->setApiStability($arguments['stability']);
    $packageXml->setNotes($arguments['note']);
    $packageXml->generateContents();
    $packageXml->setPackageType('php');

    $packageXml = $this->setDpendencies($packageXml, $pluginDirectory);

    if (isset($info['l']))
    {
      $packageXml->setLicense($info['l']);
    }
    if (isset($info['s']))
    {
      $packageXml->setSummary($info['s']);
    }
    if (isset($info['d']))
    {
      $packageXml->setDescription($info['d']);
    }

    $e = $packageXml->writePackageFile();
    if (PEAR::isError($e))
    {
      echo $e->getMessage();
      exit;
    }
  }

  public function setDpendencies($package, $dir)
  {
    $file = $dir.'dependencies.yml';
    if (!is_file($file))
    {
      $package->setPhpDep('5.2.3');
      $package->setPearinstallerDep('1.4.0');

      return $package;
    }

    $list = sfYaml::load($file);
    foreach ($list as $type => $v)
    {
      if (empty($v))
      {
        continue;
      }

      if ('php' === $type)
      {
        $v = array_merge(array('min' => false, 'max' => false, 'exclude' => false), $v);
        $package->setPhpDep($v['min'], $v['max'], $v['exclude']);
      }
      if ('pearinstaller' === $type)
      {
        $v = array_merge(array('min' => false, 'max' => false, 'recommended' => false, 'exclude' => false), $v);
        $package->setPearinstallerDep($v['min'], $v['max'], $v['recommended'], $v['exclude']);
      }
      elseif ('package' === $type || 'extension' === $type)
      {
        foreach ($v as $name => $dep)
        {
          $dep = array_merge(array('min' => false, 'max' => false, 'recommended' => false, 'exclude' => false, 'conflicts' => false, 'channel' => false), $dep);
          if ('extension' === $type)
          {
            $package->addExtensionDep('required', $name, $dep['min'], $dep['max'], $dep['recommended'], $dep['exclude']);
          }
          elseif ($dep['conflicts'])
          {
            $package->addConflictingPackageDepWithChannel($name, $dep['channel'], false, $dep['min'], $dep['max'], $dep['exclude']);
          }
          else
          {
            $package->addPackageDepWithChannel('required', $name, $dep['channel'], $dep['min'], $dep['max'], $dep['recommended'], $dep['exclude']);
          }
        }
      }
    }

    return $package;
  }

  public function getPluginManager($channel = null)
  {
    if (is_null($this->pluginManager))
    {
      $this->pluginManager = new opPluginManager($this->dispatcher, null, $channel);
    }

    return $this->pluginManager;
  }
}
