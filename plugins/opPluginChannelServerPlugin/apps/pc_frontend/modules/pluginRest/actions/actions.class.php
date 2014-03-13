<?php

/**
* Copyright 2010 Kousuke Ebihara
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

/**
 * pluginRestActions
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class pluginRestActions extends sfActions
{
  public function preExecute()
  {
    error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

    foreach (array('channel_name', 'summary', 'suggestedalias') as $v)
    {
      $this->$v = Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.$v, str_replace(':80', '', $this->getRequest()->getHost()));
    }

    require_once 'PEAR.php';
    require_once 'PEAR/Common.php';
    require_once 'PEAR/ChannelFile.php';

    $baseUrl = 'http://'.$this->channel_name.'pluginRest/';

    $channel = new PEAR_ChannelFile();
    $channel->setName($this->channel_name);
    $channel->setSummary($this->summary);
    $channel->setAlias($this->suggestedalias);
    $channel->setBaseURL('REST1.0', $baseUrl);
    $channel->setBaseURL('REST1.1', $baseUrl);
    $channel->setBaseURL('REST1.2', $baseUrl);
    $channel->setBaseURL('REST1.3', $baseUrl);

    $registry = new PEAR_Registry(sfConfig::get('sf_cache_dir'), $channel);

    $this->pear = new PEAR_Common();

    $this->pear->config->setRegistry($registry);

    if (!$registry->channelExists($channel->getName()))
    {
      $registry->addChannel($channel);
    }
    else
    {
      $registry->updateChannel($channel);
    }
  }

  public function executeChannel(sfWebRequest $request)
  {
  }

  public function executeRoot(sfWebRequest $request)
  {
    $this->forward404();
  }

  public function executeCategoryAll(sfWebRequest $request)
  {
    $this->categories = Doctrine::getTable('PluginCategory')->findAll();
  }

  public function executeCategoryInfo(sfWebRequest $request)
  {
    $this->category = $this->getRoute()->getObject();
  }

  public function executeCategoryPackages(sfWebRequest $request)
  {
    $this->category = $this->getRoute()->getObject();
  }

  public function executeMaintainerAll(sfWebRequest $request)
  {
    $this->handles = Doctrine::getTable('MemberConfig')->findByName('pear_handle', Doctrine_Core::HYDRATE_ON_DEMAND);
  }

  public function executeMaintainerInfo(sfWebRequest $request)
  {
    $this->config = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue('pear_handle', $request['name']);
    $this->forward404Unless($this->config);
  }

  public function executePackageAll(sfWebRequest $request)
  {
    $this->packages = Doctrine::getTable('PluginPackage')->findAll(Doctrine_Core::HYDRATE_ON_DEMAND);
  }

  public function executePackageInfo(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
  }

  public function executePackageMaintainers(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
  }

  public function executePackageMaintainers2(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
  }

  public function executeReleaseAll(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
  }

  public function executeReleaseAll2(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
  }

  public function executeLatestRelease(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
    $this->release = $this->package->getLatestRelease();
    $this->forward404Unless($this->release);
  }

  public function executeStableRelease(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
    $this->release = $this->package->getStableRelease();
    $this->forward404Unless($this->release);
  }

  public function executeBetaRelease(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
    $this->release = $this->package->getBetaRelease();
    $this->forward404Unless($this->release);
  }

  public function executeAlphaRelease(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
    $this->release = $this->package->getAlphaRelease();
    $this->forward404Unless($this->release);
  }

  public function executeDevelRelease(sfWebRequest $request)
  {
    $this->package = $this->getRoute()->getObject();
    $this->release = $this->package->getDevelRelease();
    $this->forward404Unless($this->release);
  }

  public function executeReleaseVersion(sfWebRequest $request)
  {
    $version = $request['version'];
    if (0 === strpos($version, 'v2.'))
    {
      $version = substr($version, 3);
      $this->setTemplate('releaseVersion2');
    }
    elseif (0 === strpos($version, 'package.'))
    {
      $version = substr($version, 8);
      $this->setTemplate('releasePackageDefinition');
    }

    $this->package = $this->getRoute()->getObject();
    $this->release = Doctrine::getTable('PluginRelease')->findOneByPackageIdAndVersion($this->package->id, $version);
    $this->forward404Unless($this->release);

    $this->info = $this->pear->infoFromString($this->release->package_definition);
  }

  public function executeCategoryPackagesInfo(sfWebRequest $request)
  {
    $this->category = $this->getRoute()->getObject();
  }

  public function executeDownloadTgz(sfWebRequest $request)
  {
    $version = $request['version'];

    $this->package = $this->getRoute()->getObject();
    $this->release = Doctrine::getTable('PluginRelease')->findOneByPackageIdAndVersion($this->package->id, $version);
    $this->forward404Unless($this->release);

    header('Content-type: '.$this->release->File->type);
    echo $this->release->File->FileBin->bin;

    exit;
  }
}
