<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * application actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class applicationActions extends sfActions
{
  public function preExecute()
  {
    if (is_callable(array($this->getRoute(), 'getObject')))
    {
      $object = $this->getRoute()->getObject();
      if ($object instanceof MemberApplication)
      {
        $this->memberApplication = $object;
        $this->application       = $this->memberApplication->getApplication();
        $this->member            = $this->memberApplication->getMember();
      }
      elseif ($object instanceof Application)
      {
        $this->application = $object;
      }
      elseif ($object instanceof Member)
      {
        $this->member = $object;
      }
    }

    if (empty($this->member))
    {
      $this->member = $this->getUser()->getMember();
    }
    elseif ($this->member->getId() != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
      sfConfig::set('sf_nav_id', $this->member->getId());
    }
  }

  /**
   * Executes canvas action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCanvas(sfWebRequest $request)
  {
    $this->forward404Unless($this->memberApplication->isViewable());
    $this->forward404Unless($this->application->isActive());
  }

  /**
   * Executes list action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeList(sfWebRequest $request)
  {
    $memberId    = $this->getUser()->getMemberId();
    $owner       = $this->member;
    $ownerId     = $owner->getId();

    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($ownerId, $memberId);
    $this->forward404If($relation && $relation->getIsAccessBlock());

    $this->isOwner = false;
    if ($memberId == $ownerId)
    {
      $this->isOwner = true;
      $this->isInstalledApp = (bool)Doctrine::getTable('Application')->findOneByMemberId($memberId);
      $this->isInstallApp = !(
        (int)Doctrine::getTable('SnsConfig')->get('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY) ===
        ApplicationTable::ADD_APPLICATION_DENY
      );
    }

    $this->memberApplications = Doctrine::getTable('MemberApplication')->getMemberApplications($ownerId);

    $this->form = new sfForm();
  }

  /**
   * Executes setting action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeSetting(sfWebRequest $request)
  {
    $this->forward404If($this->getUser()->getMemberId() != $this->memberApplication->getMember()->getId());
    $this->settingForm     = new ApplicationSettingForm(array(), array('member_application' => $this->memberApplication));
    $this->userSettingForm = new ApplicationUserSettingForm(array(), array('member_application' => $this->memberApplication));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->settingForm->bind($request->getParameter('setting'));
      $this->userSettingForm->bind($request->getParameter('user_setting'));
      if ($this->settingForm->isValid() && $this->userSettingForm->isValid())
      {
        $this->settingForm->save();
        $this->userSettingForm->save();
        $this->isValid = true;
      }
    }
  }

  /**
   * Executes gallery action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeGallery(sfWebRequest $request)
  {
    $this->searchForm = new ApplicationSearchForm();
    $this->searchForm->bind($request->getParameter('application'));
    if ($this->searchForm->isValid())
    {
      $this->pager = $this->searchForm->getPager($request->getParameter('page', 1), 20, true);
    }
  }

  /**
   * Executes add action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeAdd(sfWebRequest $request)
  {
    $memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($this->application, $this->member);
    if ($memberApplication)
    {
      $this->redirect('@application_canvas?id='.$memberApplication->getId());
    }

    $this->forward404Unless($this->application->isActive());

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      try 
      {
        $application = Doctrine::getTable('Application')->addApplication($this->application->getUrl());
        $this->application = $application;
      }
      catch (Exception $e)
      {
      }

      $memberApplication = $this->application->addToMember($this->member, array('is_view_home' => true, 'is_view_profile' => true));
      $this->redirect('@application_canvas?id='.$memberApplication->getId());
    }
  }

  /**
   * Executes remove action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeRemove(sfWebRequest $request)
  {
    $this->forward404If($this->getUser()->getMemberId() != $this->memberApplication->getMember()->getId());

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->memberApplication->delete();
      $this->getUser()->setFlash('notice', 'The App was removed successfully.');
      $this->redirect('@my_application_list');
    }
  }

 /**
  * Executes view Application User List action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeMember(sfWebRequest $request)
  {
    $this->pager = $this->application->getMemberListPager($request->getParameter('page', 1));
  }

  /**
   * Executes info action
   * 
   * @param sfWebRequest $request A request object
   */ 
  public function executeInfo(sfWebRequest $request)
  {
    $this->memberListPager = $this->application->getMemberListPager(1, 9, true); 
  }

 /**
  * Executes install action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeInstall(sfWebRequest $request)
  {
    $this->rule = (int)Doctrine::getTable('SnsConfig')->get('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY);
    $this->forward404If($this->rule == ApplicationTable::ADD_APPLICATION_DENY);

    $this->form = new AddApplicationForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        try
        {
          $application = Doctrine::getTable('Application')->findOneByUrl($this->form->getValue('application_url'));
          if ($application)
          {
            $this->getUser()->setFlash('notice', 'This app is already installed.');
          }
          else
          {
            $application = Doctrine::getTable('Application')->addApplication($this->form->getValue('application_url'));
            $application->setMemberId($this->getUser()->getMemberId());
            if (ApplicationTable::ADD_APPLICATION_NECESSARY_TO_PERMIT)
            if ((int)Doctrine::getTable('SnsConfig')->get('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY) ===
              ApplicationTable::ADD_APPLICATION_NECESSARY_TO_PERMIT)
            {
              $application->setIsActive(false);
            }
            $application->save();
          }
          $this->redirect('@application_info?id='.$application->getId());
        }
        catch (Exception $e)
        {
          if (!($e instanceof sfStopException))
          {
            $this->getUser()->setFlash('error', 'Failed in adding the App.');
          }
        }
        $this->redirect('@application_install');
      }
    }
  }

 /**
  * Executes installed list action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeInstalledList(sfWebRequest $request)
  {
    $page = $request->getParameter('page', 1);
    $this->pager = Doctrine::getTable('Application')->getApplicationListPager($page, 20, $this->member->getId());
    $this->forward404Unless($this->pager->getNbResults());
  }

  /**
   * Executes update application action
   *
   * @param sfWebRequest $request A request object
   */

  public function executeUpdate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($this->member->getId() === $this->application->getMemberId());
    $this->application->updateApplication($this->getUser()->getCulture());
    $this->redirect('@application_info?id='.$this->application->getId());
  }

  /**
   * Executes sort application
   * 
   * @param sfWebRequest $request A request object
   */
  public function executeSort(sfWebRequest $request)
  {
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $request->checkCSRFProtection();
      $memberId = $this->getUser()->getMember()->getId();
      $order = $request->getParameter('order');
      foreach ($order as $key => $value)
      {
        $memberApplication = Doctrine::getTable('MemberApplication')->find($value);
        if ($memberApplication && $memberApplication->getMemberId() == $memberId)
        {
          $memberApplication->setSortOrder($key);
          $memberApplication->save();
        }
      }
    }
    return sfView::NONE;
  }
}
