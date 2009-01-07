<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * admin actions.
 *
 * @package    OpenPNE
 * @subpackage admin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class adminActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('admin', 'manageUser');
  }

 /**
  * Executes manageUser action
  *
  * @param sfRequest $request A request object
  */
  public function executeManageUser(sfWebRequest $request)
  {
    $this->users = AdminUserPeer::retrievesAll();
  }

 /**
  * Executes adminUser action
  *
  * @param sfRequest $request A request object
  */
  public function executeAddUser(sfWebRequest $request)
  {
    $this->form = new AdminUserForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('admin_user');
      $this->redirectIf($this->form->bindAndSave($params), 'admin/manageUser');
    }
  }

 /**
  * Executes deleteUser action
  *
  * @param sfRequest $request A request object
  */
  public function executeDeleteUser(sfWebRequest $request)
  {
    $this->user = AdminUserPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->user);
    $this->forward404If($this->user->getId() == $this->getUser()->getId());
    $this->forward404If($this->user->getId() == 1);

    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $field = $this->form->getCSRFFieldName();
      $this->form->bind(array($field => $request->getParameter($field)));
      if ($this->form->isValid())
      {
        $this->user->delete();
        $this->redirect('admin/manageUser');
      }
    }
  }

 /**
  * Executes editPassword action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditPassword(sfWebRequest $request)
  {
    $user = AdminUserPeer::retrieveByPk($this->getUser()->getId());
    $this->form = new AdminUserEditPasswordForm($user);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('admin_user');
      $this->redirectIf($this->form->bindAndSave($params), 'admin/manageUser');
    }
  }
}
