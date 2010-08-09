<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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
    $this->profiles = ProfilePeer::retrievesAll();
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
        $profileId = ProfileOptionPeer::getMaxSortOrder($parameter['profile_id']);
        $parameter['sort_order'] = $profileId === false ? 0 : $profileId + 10;
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

    if ($request->isMethod('post'))
    {
      $request->checkCSRFProtection();
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

    if ($request->isMethod('post'))
    {
      $request->checkCSRFProtection();
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
