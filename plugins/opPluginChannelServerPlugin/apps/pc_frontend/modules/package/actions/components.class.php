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
 * packageComponents
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class packageComponents extends sfComponents
{
  public function executeCautionAboutPluginJoinRequest()
  {
    $this->count = Doctrine::getTable('PluginMember')->countJoinRequests($this->getUser()->getMemberId());
  }

  public function executeUsage()
  {
  }

  public function executeListRecentPlugin()
  {
    $this->plugins = Doctrine::getTable('PluginPackage')->createQuery()
      ->orderBy('created_at DESC')
      ->limit(5)
      ->execute();
  }

  public function executeListPopularPlugin()
  {
    $this->plugins = Doctrine::getTable('PluginPackage')->createQuery()
      ->orderBy('user_count DESC')
      ->limit(5)
      ->execute();
  }

  public function executeListRecentRelease()
  {
    $this->releases = Doctrine::getTable('PluginRelease')->createQuery()
      ->orderBy('created_at DESC')
      ->limit(5)
      ->execute();
  }

  public function executeMemberPlugins($request)
  {
    if ($request->hasParameter('id') && $request->getParameter('module') == 'member' && $request->getParameter('action') == 'profile')
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
    $this->row = $this->gadget->getConfig('row');
    $this->col = $this->gadget->getConfig('col');
    $this->crownIds = array();
    foreach (Doctrine::getTable('PluginMember')->getLeadPlugins($this->member->id) as $v)
    {
      $this->crownIds[] = $v->id;
    }
    $this->plugins = Doctrine::getTable('PluginPackage')->getMemberPlugin($this->member->id, $this->row * $this->col);
  }

  public function executeListMemberPlugin($request)
  {
    if ($request->hasParameter('id') && $request->getParameter('module') == 'member' && $request->getParameter('action') == 'profile')
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
    $this->plugins = Doctrine::getTable('PluginPackage')->getMemberPlugin($this->member->id, 5);
  }
}
