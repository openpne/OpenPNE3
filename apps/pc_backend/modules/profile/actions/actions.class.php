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
 * profile actions.
 *
 * @package    OpenPNE
 * @subpackage profile
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class profileActions extends sfActions
{
 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $criteria = new Criteria();
    $criteria->addAscendingOrderByColumn(ProfilePeer::SORT_ORDER);
    $this->profiles = ProfilePeer::doSelect($criteria);
    $this->option_form = array();

    $criteria = new Criteria();
    $criteria->addAscendingOrderByColumn(ProfileOptionPeer::SORT_ORDER);
    foreach ($this->profiles as $value) {
      $this->option_form[$value->getId()] = array();
      foreach ($value->getProfileOptions($criteria) as $option) {
        $this->option_form[$value->getId()][$option->getId()] = new ProfileOptionForm(ProfileOptionPeer::retrieveByPk($option->getId()));
      }
      $newProfileOption = new ProfileOption();
      $newProfileOption->setProfileId($value->getId());
      $this->option_form[$value->getId()][] = new ProfileOptionForm($newProfileOption);
    }
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    $this->profile = ProfilePeer::retrieveByPk($request->getParameter('id'));
    $this->form = new ProfileForm($this->profile);

    if ($request->isMethod('post')) {
      $parameter = $request->getParameter('profile');
      if ($this->form->getObject()->isNew())
      {
        $parameter['sort_order'] = ProfilePeer::getMaxSortOrder();
      }
      $this->form->bind($parameter);
      if ($this->form->isValid()) {
        $this->form->save();
        $this->redirect('profile/list');
      }
    }
  }

 /**
  * Executes editOption action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditOption($request)
  {
    $this->profileOption = ProfileOptionPeer::retrieveByPk($request->getParameter('id'));
    $this->form = new ProfileOptionForm($this->profileOption);

    if ($request->isMethod('post')) {
      $parameter = $request->getParameter('profile_option');
      if ($this->form->getObject()->isNew())
      {
        $parameter['sort_order'] = ProfileOptionPeer::getMaxSortOrder();
      }
      $this->form->bind($parameter);
      if ($this->form->isValid()) {
        $this->form->save();
      }
    }
    $this->redirect('profile/list');
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    $this->profile = ProfilePeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->profile);

    if ($request->isMethod('post')) {
      $this->profile->delete();
      $this->redirect('profile/list');
    }
  }

 /**
  * Executes deleteOption action
  *
  * @param sfRequest $request A request object
  */
  public function executeDeleteOption($request)
  {
    $this->profileOption = ProfileOptionPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->profileOption);

    if ($request->isMethod('post')) {
      $this->profileOption->delete();
    }
    $this->redirect('profile/list');
  }

  /**
   * Executes sortProfile action
   *
   * @param sfRequest $request A request object
   */
  public function executeSortProfile($request)
  {
    if ($request->isXmlHttpRequest())
    {
      $order = $request->getParameter('profiles');
      for ($i = 0; $i < count($order); $i++)
      {
        $profile = ProfilePeer::retrieveByPk($order[$i]);
        if ($profile)
        {
          $profile->setSortOrder($i * 10);
          $profile->save();
        }
      }
    }
    return sfView::NONE;
  }

  /**
   * Executes sortProfileOption action
   *
   * @param sfRequest $request A request object
   */
  public function executeSortProfileOption($request)
  {
    if ($request->isXmlHttpRequest())
    {
      $parameters = $request->getParameterHolder();
      $keys       = $parameters->getNames();
      foreach ($keys as $key)
      {
        if (preg_match('/^profile_options_\d+$/', $key, $match))
        {
          $order = $parameters->get($match[0]);
          for ($i = 0; $i < count($order); $i++)
          {
            $profileOption = ProfileOptionPeer::retrieveByPk($order[$i]);
            if ($profileOption)
            {
              $profileOption->setSortOrder($i * 10);
              $profileOption->save();
            }
          }
          break;
        }
      }
    }
    return sfView::NONE;
  }
}
