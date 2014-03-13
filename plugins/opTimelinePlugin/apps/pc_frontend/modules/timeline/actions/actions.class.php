<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * timeline actions.
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */

class timelineActions extends sfActions
{
  public function executeMember(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'timeline', 'smtMember');

    return sfView::SUCCESS;
  }

  public function executeCommunity(opWebRequest $request)
  {
    $communityId = $request->getParameter('id');
    $community = Doctrine::getTable('Community')->find($communityId);
    $this->forward404Unless($community, 'Undefined community.');
    $this->forwardIf($request->isSmartphone(), 'timeline', 'smtCommunity');
    sfConfig::set('sf_nav_type', 'community');

    return sfView::SUCCESS;
  }

  public function executeShow(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'timeline', 'smtShow');

    $this->getResponse()->addStyleSheet('/opTimelinePlugin/css/jquery.colorbox.css');
    $this->getResponse()->addJavascript('/opTimelinePlugin/js/jquery.colorbox.js', 'last');

    $activityId = (int)$request['id'];
    $this->activity = Doctrine::getTable('ActivityData')->find($activityId);
    if (!$this->activity)
    {
      $this->redirect('default/error');
    }

    if ('community' === $this->activity->getForeignTable())
    {
      $this->isCommunity = true;
      $communityId = $this->activity->getForeignId();
      $this->community = Doctrine::getTable('Community')->find($communityId);
      $this->memberId = $this->getUser()->getMember()->getId();
    }
    $this->viewPhoto = opTimeline::getViewPhoto();

    return sfView::SUCCESS;
  }

  public function executeSns(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'timeline', 'smtSns');
    $this->forward('default', 'error');

    return sfView::SUCCESS;
  }

  public function executeSmtSns(opWebRequest $request)
  {
    $this->viewPhoto = opTimeline::getViewPhoto();

    $this->setTemplate('smtSns');

    return sfView::SUCCESS;
  }

  public function executeSmtShow(opWebRequest $request)
  {
    $activityId = (int)$request['id'];
    $this->activity = Doctrine::getTable('ActivityData')->find($activityId);
    if (!$this->activity)
    {
      $this->redirect('default/error');
    }
    $this->viewPhoto = opTimeline::getViewPhoto();

    return sfView::SUCCESS;
  }

  public function executeSmtMember(opWebRequest $request)
  {
    $this->setTemplate('smtMember');

    return sfView::SUCCESS;
  }

  public function executeSmtCommunity(opWebRequest $request)
  {
    $this->setTemplate('smtCommunity');

    return sfView::SUCCESS;
  }
}
