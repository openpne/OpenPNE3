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
 * sns actions.
 *
 * @package    OpenPNE
 * @subpackage sns
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class snsActions extends sfActions
{
 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig($request)
  {
    $this->form = new SnsConfigForm();

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('sns_config'));
      if ($this->form->isValid()) {
        $this->form->save();
      }
    }
  }

 /**
  * Executes configInformation action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformationConfig($request)
  {
    $this->target = $request->getParameter('target', 'mobile_home');
    $this->form = new InformationConfigForm(array(), array('target' => $this->target));

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('information'));
      if ($this->form->isValid())
      {
        $this->form->save();
      }
    }
  }
}
