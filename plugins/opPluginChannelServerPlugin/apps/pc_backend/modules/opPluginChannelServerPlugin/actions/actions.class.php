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
 * opPluginChannelServerPluginActions actions.
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginChannelServerPluginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new opPluginChannelServerPluginConfigForm(array(
      'channel_name' => Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.'channel_name'),
      'summary' => Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.'summary'),
      'suggestedalias' => Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.'suggestedalias'),
      'related_redmine_base_url' => Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.'related_redmine_base_url'),
      'parent_project_id' => Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.'parent_project_id'),
      'user_role_id' => Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.'user_role_id'),
    ));
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request['plugin_config']);
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('opPluginChannelServerPlugin/index');
      }
    }
  }

  public function executeCategory(sfWebRequest $request)
  {
    $this->categories = Doctrine::getTable('PluginCategory')->findAll();
    $this->rootForm = new PluginCategoryForm();
    $this->deleteForm = new sfForm();
    $this->categoryForms = array();

    $params = $request->getParameter('plugin_category');
    if ($request->isMethod(sfRequest::POST))
    {
      $targetForm = $this->rootForm;
      if ($targetForm->bindAndSave($params))
      {
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('opPluginChannelServerPlugin/category');
      }
    }
  }

  /**
   * Executes categoryList action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCategoryEdit(sfWebRequest $request)
  {
    $form = new PluginCategoryForm(Doctrine::getTable('PluginCategory')->find($request['id']));
    if ($request->isMethod(sfRequest::POST))
    {
      if ($form->bindAndSave($request['plugin_category']))
      {
        $this->getUser()->setFlash('notice', 'Saved.');
      }
      else
      {
        $this->getUser()->setFlash('error', $form->getErrorSchema()->getMessage());
      }
    }
    $this->redirect('opPluginChannelServerPlugin/category');
  }

  /**
   * Executes categoryDelete action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCategoryDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $category = Doctrine::getTable('PluginCategory')->find($request['id']);
    $this->forward404Unless($category);

    $category->delete();

    $this->getUser()->setFlash('notice', 'Deleted.');
    $this->redirect('opPluginChannelServerPlugin/category');
  }
}
