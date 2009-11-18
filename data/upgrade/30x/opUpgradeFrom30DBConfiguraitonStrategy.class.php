<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy changes propel configuration to be compatible with doctrine
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30DBConfiguraitonStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $file = sfConfig::get('sf_config_dir').'/databases.yml';
    if (!file_exists($file))
    {
      throw new RuntimeException('It needs databases.yml');
    }

    $config = sfYaml::load($file);

    if (isset($config['all']['propel']))
    {
      $propel = $config['all']['propel'];
      unset($config['all']['propel']);

      $config['all']['doctrine'] = array(
        'class' => 'sfDoctrineDatabase',
        'param' => array(
          'dsn'        => $propel['param']['dsn'],
          'username'   => $propel['param']['username'],
          'encoding'   => 'utf8',
          'attributes' => array(
             Doctrine::ATTR_USE_DQL_CALLBACKS => true,
          ),
        ),
      );

      if (!empty($propel['password']))
      {
        $config['all']['doctrine']['param']['password'] = $propel['param']['password'];
      }
    }

    if (isset($config['dev']['propel']))
    {
      $propel = $config['dev']['propel'];
      unset($config['dev']['propel']);
      $config['dev']['doctrine'] = $propel;
    }

    file_put_contents($file, sfYaml::dump($config, 4));
  }
}

