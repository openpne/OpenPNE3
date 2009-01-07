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
 * default actions.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class secureAction extends sfAction
{
 /**
  * Executes secure action
  *
  * @param sfRequest $request A request object
  */
  public function execute($request)
  {
    $isForwardToLoginPage = false;

    $actionStack = sfContext::getInstance()->getController()->getActionStack();
    $stackSize = $actionStack->getSize();
    $preActionCredential = $actionStack->getEntry($stackSize - 2)->getActionInstance()->getCredential();

    if (is_array($preActionCredential)) {
      $isForwardToLoginPage = in_array('SNSMember', $preActionCredential);
    } elseif ($preActionCredential == 'SNSMember') {
      $isForwardToLoginPage = true;
    }

    $this->forwardIf($isForwardToLoginPage, sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
  }
}
