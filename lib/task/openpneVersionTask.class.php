<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpneVersionTask
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class openpneVersionTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace        = 'openpne';
    $this->name             = 'version';
    $this->briefDescription = 'Show version information of OpenPNE and all installed plugins';
    $this->detailedDescription = <<<EOF
The [openpne:version|INFO] task shows version information of OpenPNE and all installed plugins.
Call it with:

  [./symfony openpne:version|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->log($this->formatter->format('Core versions:', 'COMMENT'));

    $this->displayLine('OpenPNE', OPENPNE_VERSION);
    $this->displayLine('symfony', SYMFONY_VERSION);

    $this->log($this->formatter->format('OpenPNE plugin versions:', 'COMMENT'));

    foreach ($this->configuration->getAllOpenPNEPlugins() as $name)
    {
      $version = opPlugin::getInstance($name, $this->dispatcher)->getVersion();
      if (!$version)
      {
        $version = 'unknown';
      }
      $this->displayLine($name, $version);
    }
  }

  protected function displayLine($name, $version)
  {
    $this->log(sprintf(' %-40s %s', $this->formatter->format($name, 'INFO'), $version));
  }
}
