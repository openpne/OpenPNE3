<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialContainerConfig
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialContainerConfig
{

  protected $containerName = null;
  protected $isDevEnvironment = null;

  public function __construct($isDevEnvironment = null, $containerName = 'openpne')
  {
    if ($isDevEnvironment === null)
    {
      if (sfConfig::get('sf_environment') == 'dev')
      {
        $isDevEnvironment = true;
      }
      else
      {
        $isDevEnvironment = false;
      }
    }

    if ($isDevEnvironment)
    {
      $containerName .= '_dev';
    }

    $this->isDevEnvironment = $isDevEnvironment;
    $this->containerName = $containerName;
  }

  public function getContainerName()
  {
    return $this->containerName;
  }

  /**
   * generate and save a configuration
   *
   * @param boolean $force
   * @param string  $snsUrl
   * @param string  $shindigUrl
   * @param string  $apiUrl
   * @return boolean
   */
  public function generateAndSave($force = false, $snsUrl = null, $shindigUrl = null, $apiUrl = null)
  {
    if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
    {
      return false;
    }

    $dirname  = Shindig_Config::get('container_path');
    $filename = $dirname.'/'.$this->containerName.'.js';

    if (file_exists($filename) && !$force)
    {
      return true;
    }
    
    $json = self::generate($snsUrl, $shindigUrl);

    if (!is_dir($dirname))
    {
      $old_umask = umask(0);
      @mkdir($dirname, 0777, true);
      umask($old_umask);
    }

    if (file_put_contents($filename, $json))
    {
      return true;
    }

    return false;
  }

  /**
   * generate a configutaion
   *
   * @param string $containerName
   * @param string $snsUrl
   * @param string $shindigUrl
   * @param string $apiUrl
   * @return string 
   */
  public function generate($snsUrl = null, $shindigUrl = null, $apiUrl = null)
  {
    //Template
    $containerTemplate = array(
      'gadgets.container' => array(),
      'gadgets.parent' => null,
      'gadgets.lockedDomainRequired' => false,
      'gadgets.lockedDomainSuffix' => '-a.example.com:8080',
      'gadgets.iframeBaseUri' => '/gadgets/ifr',
      'gadgets.jsUriTemplate' => '#shindig_url#gadgets/js/%js%',
      'gadgets.oauthGadgetCallbackTemplate' => '#shindig_url#/gadgets/oauthcallback',
      'gadgets.securityTokenType' => 'secure',
      'gadgets.osDataUri' => '#api_url#social/rpc',
      'gadgets.features' => array(
        'core.io' => array(
          'proxyUrl' => '#shindig_url#gadgets/proxy?refresh=%refresh%&url=%url%',
          'jsonProxyUrl' => '#shindig_url#gadgets/makeRequest',
        ),
        'views' => array(
          'profile' => array(
            'isOnlyVisible' => false,
            'urlTemplate' => '#sns_url#member/{var}',
            'aliases' => array('DASHBOARD', 'default')
          ),
          'canvas' => array(
            'isOnlyVisible' => true,
            'urlTemplate' => '#sns_url#application/canvas/id/{var}',
          )
        ),
        'rpc' => array(
          'parentRelayUrl' => '#sns_url#opOpenSocialPlugin/js/rpc_relay.html',
          'useLegacyProtocol' => false
        ),
        'skins' => array(
          'properties' => array(
            'BG_COLOR' => '',
            'BG_IMAGE' => '',
            'BG_POSITION' => '',
            'BG_REPEAT' => '',
            'FONT_COLOR' => '',
            'ANCHOR_COLOR' => ''
          )
        ),
        'opensocial-0.8' => array(
          'path'           => '#api_url#social/rpc',
          'invalidatePath' => '#shindig_url#gadgets/api/rpc',
          'domain' => 'shindig',
          'enableCaja' => false,
          'supportedFields' => array()
        ),
        'osapi.services' => array(
          'gadgets.rpc' => array('container.listMethods'),
          '#api_url#social/rpc' => array(
            'system.listMethods',
            'people.get',
            'appdata.get',
            'appdata.update',
            'appdata.delete',
          ),
          '#shindig_url#gadgets/api/rpc' => array('cache.invalidate')
        ),
        'osapi' => array(
          'endPoint' => array('#api_url#social/rpc', '#shindig_url#gadgets/api/rpc')
        ),
        'osml' => array(
          'library' => 'config/OSML_library.xml'
        )
      ),
    );

    $containerTemplate['gadgets.container'][] = $this->containerName;

    $request = sfContext::getInstance()->getRequest();
    if ($snsUrl === null)
    {
      $snsUrl = $request->getUriPrefix().$request->getRelativeUrlRoot().'/';
      if($this->isDevEnvironment)
      {
        $snsUrl .= 'pc_frontend_dev.php/';
      }
    }

    if ($apiUrl === null)
    {
      if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
      {
        $apiUrl = Doctrine::getTable('SnsConfig')->get('shindig_url');
        if (substr($apiUrl, -1) !== '/')
        {
          $apiUrl .= '/';
        }
      }
      else
      {
        $apiUrl = $request->getUriPrefix().$request->getRelativeUrlRoot().'/api';
        if ($this->isDevEnvironment)
        {
          $apiUrl .= '_dev';
        }
        $apiUrl .= '.php/';
      }
    }

    if ($shindigUrl === null)
    {
      if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
      {
        $shindigUrl = $apiUrl;
      }
      else
      {
        $shindigUrl = $snsUrl;
      }
    }

    $export = new opOpenSocialProfileExport();
    
    $containerTemplate['gadgets.features']['opensocial-0.8']['supportedFields'] = $export->getSupportedFields();
    $json = json_encode($containerTemplate);

    $replace = array(
      '/#sns_url#/'     => addcslashes($snsUrl, '/'),
      '/#shindig_url#/' => addcslashes($shindigUrl, '/'),
      '/#api_url#/'     => addcslashes($apiUrl, '/'), 
    );

    return preg_replace(array_keys($replace), $replace, $json);
  }
}
