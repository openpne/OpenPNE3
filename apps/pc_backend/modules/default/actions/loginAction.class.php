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
 * login action.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class loginAction extends sfAction
{
 /**
  * Executes this action
  *
  * @param sfRequest $request A request object
  */
  public function execute($request)
  {
    $this->getUser()->setAuthenticated(false);

    $this->form = new opAdminLoginForm();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('admin_user'));
      if ($this->form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
        $this->getUser()->setAttribute('adminUserId', $this->form->getValue('adminUser')->getId(), 'adminUser');
        $this->redirect('default/top');
      }
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }
}
