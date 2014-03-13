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
 * package actions.
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage package
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class packageActions extends sfActions
{
  public function preExecute()
  {
    error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

    if ($this->getRoute() instanceof sfDoctrineRoute)
    {
      $object = $this->getRoute()->getObject();
      if ($object instanceof PluginPackage)
      {
        $this->package = $object;
      }
      elseif ($object instanceof PluginRelease)
      {
        $this->release = $object;
        $this->package = $object->Package;
      }
      elseif ($object instanceof Member)
      {
        $this->member = $object;
      }
    }

    if ($this->getUser()->hasCredential('SNSMember'))
    {
      $this->security[strtolower($this->actionName)] = array('is_secure' => true);
    }
  }

  public function executeHome(sfWebRequest $request)
  {
  }

  public function executeHomeRedirector(sfWebRequest $request)
  {
    $this->redirect('package_home', $this->package);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PluginPackageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new PluginPackageForm();
    $this->redirectIf($this->form->bindAndSave($request['plugin_package'], $request->getFiles('plugin_package')),
      'package_home', $this->form->getObject());

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->form = new PluginPackageForm($this->package);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new PluginPackageForm($this->package);
    $this->redirectIf($this->form->bindAndSave($request['plugin_package'], $request->getFiles('plugin_package')),
      'package_home', $this->form->getObject());

    $this->setTemplate('edit');
  }

  public function executeAddRelease(sfWebRequest $request)
  {
    $this->form = new opPluginPackageReleaseForm();
    $this->form->setPluginPackage($this->package);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request['plugin_release'], $request->getFiles('plugin_release'));
      if ($this->form->isValid())
      {
        $this->form->uploadPackage();

        $this->getUser()->setFlash('notice', 'Released plugin package');
        $this->redirect('package_home', $this->package);
      }
    }
  }

  public function executeJoin(sfWebRequest $request)
  {
    $this->form = new opPluginPackageJoinForm();
    $this->form->setPluginPackage($this->package);

    if (opPlugin::getInstance('opMessagePlugin')->getIsActive())
    {
      $this->form->injectMessageField();
    }

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request['plugin_join']);
      if ($this->form->isValid())
      {
        $this->form->send();

        $this->getUser()->setFlash('notice', 'Sent join request');
        $this->redirect('package_home', $this->package);
      }
    }
  }

  public function executeToggleUsing(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
    $this->getResponse()->setContentType('application/json');

    try
    {
      $request->checkCSRFProtection();
    }
    catch (sfValidatorErrorSchema $e)
    {
      $this->forward404();
    }

    $memberId = $this->getUser()->getMemberId();
    $isUse = $this->package->isUser($memberId);
    $this->package->toggleUsing($memberId);

    return $this->renderText(json_encode(array($this->package->countUsers(), !$isUse)));
  }

  public function executeManageMember(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('PluginMember')->getPager($this->package->id, $request->getParameter('page', 1));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $form = new opPluginMemberManageForm();
      $form->bind($request['plugin_manage']);
      if ($form->isValid())
      {
        $form->save();
      }

      $this->redirect('package_manageMember', $this->package);
    }
  }

  public function executeRelease(sfWebRequest $request)
  {
    foreach (array('channel_name', 'summary', 'suggestedalias') as $v)
    {
      $this->$v = opPluginChannelServerToolkit::getConfig($v, str_replace(':80', '', $this->getRequest()->getHost()));
    }

    $baseUrl = 'http://'.$this->channel_name.'pluginRest/';
    $channel = opPluginChannelServerToolkit::generatePearChannelFile($this->channel_name, $this->summary, $this->suggestedalias, $baseUrl);
    $this->pear = opPluginChannelServerToolkit::registerPearChannel($channel);

    $this->info = $this->pear->infoFromString($this->release->package_definition);

    $this->form = new BaseForm();
  }

  public function executeReleaseList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('PluginRelease')
      ->getPager($this->package->id, $request['page'], 20);
  }

  public function executeMemberList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('PluginMember')
      ->getPager($this->package->id, $request['page'], 20);
  }

  public function executeSearch(sfWebRequest $request)
  {
    $params = $request->getParameter('package', array());
    if (isset($request['search_query']))
    {
      $params = array_merge($params, array('name' => $request->getParameter('search_query', '')));
    }

    $this->filters = new PluginPackageFormFilter();
    $this->filters->bind($request->getParameter('plugin_package_filters', array()));

    if (!isset($this->size))
    {
      $this->size = 20;
    }

    $this->pager = new sfDoctrinePager('PluginPackage', $this->size);
    $this->pager->setQuery($this->filters->getQuery()->orderBy('created_at DESC'));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }

  public function executeReleaseDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->release->delete();

    $this->getUser()->setFlash('notice', 'The release is removed successfully.');

    $this->redirect('package_home', $this->release->Package);
  }

  public function executeListMember(sfWebRequest $request)
  {
    if (!$this->member)
    {
      if (isset($request['id']))
      {
        $this->member = Doctrine::getTable('Member')->find($request['id']);
      }
      else
      {
        $this->member = $this->getUser()->getMember();
      }
    }

    $this->forward404Unless($this->member->id);

    if ($this->member->id !== $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
      sfConfig::set('sf_nav_id', $this->member->id);
    }

    $this->crownIds = array();
    foreach (Doctrine::getTable('PluginMember')->getLeadPlugins($this->member->id) as $v)
    {
      $this->crownIds[] = $v->id;
    }

    $this->pager = Doctrine::getTable('PluginPackage')->getMemberPluginPager($this->member->id, $request->getParameter('page', 1), 20);
  }
}
