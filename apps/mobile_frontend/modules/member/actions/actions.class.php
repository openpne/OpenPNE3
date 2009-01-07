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
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class memberActions extends sfOpenPNEMemberAction
{
 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->information = SnsConfigPeer::retrieveByName('mobile_home_information');
    return parent::executeHome($request);
  }

 /**
  * Executes configUID action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigUID($request)
  {
    $option = array('member' => $this->getUser()->getMember());
    $this->passwordForm = new sfOpenPNEPasswordForm(array(), $option);

    if ($request->isMethod('post')) {
      $this->passwordForm->bind($request->getParameter('password'));
      if ($this->passwordForm->isValid()) {
        $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('mobile_uid', $this->getUser()->getMemberId());
        if (!$memberConfig) {
          $memberConfig = new MemberConfig();
          $memberConfig->setMember($this->getUser()->getMember());
          $memberConfig->setName('mobile_uid');
        }
        $memberConfig->setValue($request->getMobileUID());
        $this->redirectIf($memberConfig->save(), 'member/configUID');
      }
    }

    return sfView::SUCCESS;
  }
}
