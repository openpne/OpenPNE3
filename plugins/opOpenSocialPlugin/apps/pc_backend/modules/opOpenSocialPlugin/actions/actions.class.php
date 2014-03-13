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
 * @author     Shogo Kawahara<kawahara@tejimaya.net>
 */
class opOpenSocialPluginActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    return $this->redirect('opOpenSocialPlugin/applicationConfig');
  }

  /**
   * Executes applicationConfig action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeApplicationConfig(sfWebRequest $request)
  {
    $this->applicationConfigForm = new ApplicationConfigForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->applicationConfigForm->bind($request->getParameter('application_config'));
      if ($this->applicationConfigForm->isValid())
      {
        $this->applicationConfigForm->save();
      }
    }
  }

  /**
   * Executes containerConfig action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeContainerConfig(sfWebRequest $request)
  {
    $this->containerConfigForm = new ContainerConfigForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->containerConfigForm->bind($request->getParameter('container_config'));
      if ($this->containerConfigForm->isValid())
      {
        $this->containerConfigForm->save();
      }
    }
  }

 /**
  * Executes add action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeAdd(sfWebRequest $request)
  {
    $this->form = new AddApplicationForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        try
        {
          $application = Doctrine::getTable('Application')->addApplication($this->form->getValue('application_url'));
          $this->redirect('opOpenSocialPlugin/info?id='.$application->id);
        }
        catch (Exception $e)
        {
          if (!($e instanceof sfStopException))
          {
            $this->getUser()->setFlash('error', 'Failed in adding the App.');
          }
        }
        $this->redirect('opOpenSocialPlugin/add');
      }
    }
  }

  /**
   * Executes list action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeList(sfWebRequest $request)
  {
    $this->searchForm = new ApplicationSearchForm();
    $this->searchForm->bind($request->getParameter('application'));
    if ($this->searchForm->isValid())
    {
      $this->pager = $this->searchForm->getPager($request->getParameter('page', 1), 20, true);
    }
  }

  /**
   * Executes info action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeInfo(sfWebRequest $request)
  {
    $this->application = Doctrine::getTable('Application')->find($request->getParameter('id'));
    $this->forward404Unless($this->application);
  }

  /**
   * Executes delete application action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->application = Doctrine::getTable('Application')->find($request->getParameter('id'));
    $this->forward404Unless($this->application);

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->application->delete();
      $this->redirect('opOpenSocialPlugin/list');
    }
  }

  /**
   * Executes update application action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $application = Doctrine::getTable('Application')->find($request->getParameter('id'));
    $this->forward404Unless($application);
    $application->updateApplication($this->getUser()->getCulture());
    $this->redirect('opOpenSocialPlugin/info?id='.$application->getId());
  }

  /**
   * Executes generate container config
   *
   * @param sfWebRequest $request A request object
   */
  public function executeGenerateContainerConfig(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug',false);
    $response = $this->getResponse();
    $response->setContentType('text/javascript');
    $response->setHttpHeader('Content-Disposition','attachment; filename="openpne.js');
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig(false);
    $this->json = $opOpenSocialContainerConfig->generate();
  }

  /**
   * Executes activate application
   *
   * @param sfWebRequest $request A request object
   */
  public function executeActivate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $application = Doctrine::getTable('Application')->find($request->getParameter('id'));
    $application->setIsActive(true);
    $application->save();
    $this->redirect('opOpenSocialPlugin/info?id='.$application->getId());
  }

  /**
   * Executes inactivate application
   *
   * @param sfWebRequest $request A request object
   */
  public function executeInactivate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $application = Doctrine::getTable('Application')->find($request->getParameter('id'));
    $application->setIsActive(false);
    $application->save();
    $this->redirect('opOpenSocialPlugin/info?id='.$application->getId());
  }

  /**
   * Executes inactive application list
   *
   * @param sfWebRequest $request A request object
   */
  public function executeInactiveList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Application')->getApplicationListPager($request->getParameter('page'), 20, null, false);
  }
}
