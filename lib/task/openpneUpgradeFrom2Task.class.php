<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpneUpgradeFrom2Task
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class openpneUpgradeFrom2Task extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'upgrade-from-2';

    $this->addOptions(array(
      new sfCommandOption('origin', null, sfCommandOption::PARAMETER_REQUIRED, 'The base 2.x version (2.12 or 2.14)'),
      new sfCommandOption('rules', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'The rules that you want to do'),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->briefDescription = 'Upgrading from 2.12.x or 2.14.x to current version';
    $this->detailedDescription = <<<EOF
The [openpne:upgrade-from-30x|INFO] task upgrades from 2.12.x or 2.14.x to current version.
Call it with:

  [./symfony openpne:upgrade-from-2|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (!isset($options['origin']))
    {
      $options['origin'] = '2.12';
    }

    if (!in_array($options['origin'], array('2.12', '2.14', '3.4')))
    {
      throw new RuntimeException('You must specify "2.12", "2.14" or "3.4" to the --origin option. (--origin オプションには 2.12、 2.14 または 3.4 を指定してください。)');
    }
    sfConfig::set('op_upgrade2_version', $options['origin']);

    $op2config = sfConfig::get('sf_config_dir').DIRECTORY_SEPARATOR.'config.OpenPNE2.php';
    if (!is_readable($op2config))
    {
      throw new RuntimeException('You must copy the config.php in your OpenPNE2 as config/config.OpenPNE2.php. (お使いの OpenPNE2 の config.php を config/config.OpenPNE2.php としてコピーしてください。)');
    }
    if (!defined('OPENPNE_DIR'))
    {
      define('OPENPNE_DIR', sfConfig::get('sf_root_dir'));
    }
    require_once $op2config;

    $this->runTask('configure:database', array(
      opToolkit::createStringDsnFromArray($GLOBALS['_OPENPNE_DSN_LIST']['main']['dsn']),
      $GLOBALS['_OPENPNE_DSN_LIST']['main']['dsn']['username'],
      empty($GLOBALS['_OPENPNE_DSN_LIST']['main']['dsn']['password']) ? null: $GLOBALS['_OPENPNE_DSN_LIST']['main']['dsn']['password']
    ));

    $path = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'upgrade'.DIRECTORY_SEPARATOR.'2';
    $upgrader = new opUpgrader($this->dispatcher, $this->formatter, $path, $this->configuration);
    if ($options['rules'])
    {
      $upgrader->setOption('targets', $options['rules']);
    }
    if ('3.4' === $options['origin'])
    {
      $upgrader->setDefinitionName('definition-34to36.yml');
    }

    $this->logSection('upgrade', 'Begin upgrading from 2.x');
    $upgrader->execute();

    $task = new sfPluginPublishAssetsTask($this->dispatcher, $this->formatter);
    $task->run(array(), array());
  }
}
