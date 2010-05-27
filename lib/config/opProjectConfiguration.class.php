<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

if (!defined('E_DEPRECATED'))
{
  define('E_DEPRECATED', 8192);
}

/**
 * opProjectConfiguration
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opProjectConfiguration extends sfProjectConfiguration
{
  static public function listenToPreCommandEvent(sfEvent $event)
  {
    $subject = $event->getSubject();
    if ($subject instanceof sfTask)
    {
      sfConfig::set('sf_task_name', get_class($subject));
    }
    require_once dirname(__FILE__).'/../behavior/opActivateBehavior.class.php';
    opActivateBehavior::disable();
  }

  public function setup()
  {
    $this->enableAllPluginsExcept(array('sfPropelPlugin'));
    $this->setIncludePath();

    $this->setOpenPNEConfiguration();

    sfConfig::set('doctrine_model_builder_options', array(
      'baseClassName' => 'opDoctrineRecord',
    ));

    $this->dispatcher->connect('command.pre_command', array(__CLASS__, 'listenToPreCommandEvent'));
    $this->dispatcher->connect('doctrine.filter_cli_config', array(__CLASS__, 'filterDoctrineCliConfig'));

    $this->setupProjectOpenPNE();
  }

  protected function configureSessionStorage($name, $options = array())
  {
    $sessionName = 'OpenPNE_'.sfConfig::get('sf_app', 'default');
    $params = array('session_name' => $sessionName);

    if ('memcache' === $name)
    {
      sfConfig::set('sf_factory_storage', 'opMemcacheSessionStorage');
      sfConfig::set('sf_factory_storage_parameters', array_merge((array)$options, $params));
    }
    elseif ('database' === $name)
    {
      sfConfig::set('sf_factory_storage', 'opPDODatabaseSessionStorage');
      sfConfig::set('sf_factory_storage_parameters', array_merge(array(
        'db_table'    => 'session',
        'database'    => 'doctrine',
        'db_id_col'   => 'id',
        'db_data_col' => 'session_data',
        'db_time_col' => 'time',
      ), (array)$options, $params));
    }
    elseif ('file' !== $name)
    {
      sfConfig::set('sf_factory_storage', $name);
      sfConfig::set('sf_factory_storage_parameters', array_merge((array)$options, $params));
    }
  }

  public function setIncludePath()
  {
    sfToolkit::addIncludePath(array(
      dirname(__FILE__).'/../vendor/PEAR/',
      dirname(__FILE__).'/../vendor/OAuth/',
      dirname(__FILE__).'/../vendor/simplepie/',
    ));
  }

  public function configureDoctrine($manager)
  {
    $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);
    $manager->setAttribute(Doctrine::ATTR_RECURSIVE_MERGE_FIXTURES, true);
    $manager->setAttribute(Doctrine::ATTR_QUERY_CLASS, 'opDoctrineQuery');

    if (extension_loaded('apc'))
    {
      $options = array();
      if ($prefix = sfConfig::get('op_doctrine_cache_key_prefix'))
      {
        $options['prefix'] = $prefix;
      }
      else
      {
        $options['prefix'] = md5(dirname(__FILE__));
      }
      $cacheDriver = new Doctrine_Cache_Apc($options);
      $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
    }

    $manager->registerConnectionDriver('mysql', 'opDoctrineConnectionMysql');

    $this->setupProjectOpenPNEDoctrine($manager);
  }

  protected function setOpenPNEConfiguration()
  {
    $path = OPENPNE3_CONFIG_DIR.'/OpenPNE.yml';
    $config = sfYaml::load($path.'.sample');
    if (is_readable($path))
    {
      $config = array_merge($config, sfYaml::load($path));
    }

    $this->configureSessionStorage($config['session_storage']['name'], (array)$config['session_storage']['options']);
    unset($config['session_storage']);

    foreach ($config as $key => $value)
    {
      sfConfig::set('op_'.$key, $value);
    }
  }

  static public function filterDoctrineCliConfig($event, $config)
  {
    $config['migrations_path'] = sfConfig::get('sf_data_dir').'/migrations/generated';

    return $config;
  }
}
