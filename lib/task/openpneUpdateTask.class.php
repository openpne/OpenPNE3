<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneUpdateTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'update';

    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::OPTIONAL, 'The plugin name or "OpenPNE"', 'OpenPNE'),
      new sfCommandArgument('before-version', sfCommandArgument::OPTIONAL, '', '3.0.0'),
      new sfCommandArgument('after-version', sfCommandArgument::OPTIONAL, '', OPENPNE_VERSION),
    ));

    $this->addOptions(array(
      new sfCommandOption('no-build-model', null, sfCommandOption::PARAMETER_NONE, 'Do not build model classes'),
    ));

    $this->briefDescription = 'update OpenPNE';
    $this->detailedDescription = <<<EOF
The [openpne:update|INFO] task updates OpenPNE and/or plugin.
Call it with:

  [./symfony openpne:update|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $dir = sfConfig::get('sf_lib_dir').'/update';
    $prefix = 'op';
    if ('OpenPNE' !== $arguments['name'])
    {
      $dir = sfConfig::get('sf_plugins_dir').'/'.$arguments['name'].'/lib/update';
      $prefix = $arguments['name'];
    }

    $beforeVersion = self::formatVersion($arguments['before-version']);
    $afterVersion = self::formatVersion($arguments['after-version']);

    $files = sfFinder::type('file')->not_name('opUpdate.class.php')->not_name('opUpdateDoctrineMigrationProcess.class.php')->in($dir);
    $files = array_map(create_function('$path', 'return basename($path);'), $files);
    usort($files, array(get_class($this), 'sortBeforeVersions'));
    foreach ($files as $file)
    {
      $versions = explode('_to_', str_replace('.php', '', $file), 2);
      $_before = self::formatVersion($versions[0]);
      $_after = self::formatVersion($versions[1]);

      if (version_compare($beforeVersion, $_after, '<=') && version_compare($afterVersion, $_after, '>='))
      {
        $className = $prefix.'Update_'.str_replace('.php', '', $file);
        $this->logSection('execute', $className);
        require_once $dir.'/'.$file;
        $obj = new $className($this->dispatcher, $databaseManager);
        $obj->doUpdate();
      }
    }

    if (!$options['no-build-model'])
    {
      $this->buildModel();
    }
  }

  static public function sortBeforeVersions($name1, $name2)
  {
    $_versions = explode('_to_', str_replace('.php', '', $name1), 2);
    $version1 = array_shift($_versions);
    $_versions = explode('_to_', str_replace('.php', '', $name2), 2);
    $version2 = array_shift($_versions);

    return version_compare(self::formatVersion($version1), self::formatVersion($version2));
  }

  static public function formatVersion($version)
  {
    $_searches = array('-', '_', '+');
    $version = str_replace($_searches, '.', $version);

    $pos = strpos($version, '.dev.');
    if (false !== $pos)
    {
      $time = substr($version, $pos + strlen('.dev.'));
      $datetime = new DateTime($time);
      $version = substr($version, 0, $pos + strlen('.dev.')).$datetime->format('U');
    }

    return $version;
  }

  protected function buildModel()
  {
    $task = new sfPropelBuildModelTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfPropelBuildFormsTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfPropelBuildFiltersTask($this->dispatcher, $this->formatter);
    $task->run();
  }
}
