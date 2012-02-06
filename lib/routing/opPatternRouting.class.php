<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPatternRouting class controls the generation and parsing of URLs in OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage routing
 * @author     Yuya Watanabe <watanabe@openpne.jp>
 */
class opPatternRouting extends sfPatternRouting
{

  public function generate($name, $params = array(), $absolute = false)
  {
    $app = sfConfig::get('sf_app');
    $sslAppRequiredList = sfConfig::get('op_ssl_required_applications', array());
    $sslActionRequiredList = sfConfig::get('op_ssl_required_actions', array($app => array()));

    if (sfConfig::get('op_use_ssl', false)
      && in_array($app, $sslAppRequiredList)
      && isset($params['module']) && isset($params['action'])
      && in_array($params['module'].'/'.$params['action'], $sslActionRequiredList[$app])
    )
    {
      $this->options['context']['is_secure'] = true;
      $sslBaseUrls = sfConfig::get('op_ssl_base_url');
      $url = $sslBaseUrls[$app];
      $isDefault = 'https://example.com' === $url;
    }
    else
    {
      $this->options['context']['is_secure'] = false;
      $url = sfConfig::get('op_base_url');
      $isDefault = 'http://example.com' === $url;
    }

    $parts = parse_url($url);

    $configuration = sfContext::getInstance()->getConfiguration();
    $settings = sfDefineEnvironmentConfigHandler::getConfiguration($configuration->getConfigPaths('config/settings.yml'));
    $isNoScriptName = !empty($settings['.settings']['no_script_name']);

    if (!$isDefault)
    {
      $parts['path'] = isset($parts['path']) ? $parts['path'] : '';
      $this->options['context']['prefix'] =
        $this->getAppScriptName($app, sfConfig::get('sf_environment'), $parts['path'], $isNoScriptName);

      if (isset($parts['host']))
      {
        $this->options['context']['host'] = $parts['host'];
        if (isset($parts['port']))
        {
          $this->options['context']['host'] .= ':'.$parts['port'];
        }
      }
    }
    else
    {
      $path = preg_replace('#/[^/]+\.php$#', '', $this->options['context']['prefix']);
      $this->options['context']['prefix'] = $this->getAppScriptName($app, sfConfig::get('sf_environment'), $path, $isNoScriptName);
    }

    return parent::generate($name, $params, $absolute);
  }

  // equals opApplicationConfiguration#getAppScriptName
  private function getAppScriptName($application, $env, $prefix, $isNoScriptName = false)
  {
    if ($isNoScriptName)
    {
      return $prefix;
    }

    if ('/' === $prefix)
    {
      $prefix = '';
    }

    $name = $prefix.'/'.$application;
    if ($env !== 'prod')
    {
      $name .= '_'.$env;
    }
    $name .= '.php';

    return $name;
  }

}
