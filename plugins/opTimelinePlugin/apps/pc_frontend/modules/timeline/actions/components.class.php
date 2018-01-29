<?php

/**
 * opTimelinePlugin components.
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */

class timelineComponents extends sfComponents
{
  public function executeTimelineAll(sfWebRequest $request)
  {
    $this->getResponse()->addStyleSheet('/opTimelinePlugin/css/jquery.colorbox.css');
    $this->getResponse()->addJavascript('/opTimelinePlugin/js/jquery.colorbox.js', 'last');
    $this->getResponse()->addJavascript('/opTimelinePlugin/js/jquery.timeline.js', 'last');

    $this->publicFlags = Doctrine::getTable('ActivityData')->getPublicFlags();
    $this->viewPhoto = opTimeline::getViewPhoto();

    $this->setFileMaxSize();

    return sfView::SUCCESS;
  }

  private function setFileMaxSize()
  {
    $fileMaxSize = array();
    $fileMaxSize['format'] = opTimelinePluginUtil::getFileSizeMaxOfFormat();
    $fileMaxSize['size'] = opTimelinePluginUtil::getFileSizeMax();

    $this->fileMaxSize = $fileMaxSize;
  }

  public function executeTimelineProfile(sfWebRequest $request)
  {
    $this->getResponse()->addStyleSheet('/opTimelinePlugin/css/jquery.colorbox.css');
    $this->getResponse()->addJavascript('/opTimelinePlugin/js/jquery.colorbox.js', 'last');
    $this->getResponse()->addJavascript('/opTimelinePlugin/js/jquery.timeline.js', 'last');
    $this->memberId = $request->getParameter('id', $this->getUser()->getMember()->getId());

    $this->viewPhoto = opTimeline::getViewPhoto();

    $this->setFileMaxSize();

    return sfView::SUCCESS;
  }

  public function executeTimelineCommunity(sfWebRequest $request)
  {
    $this->getResponse()->addStyleSheet('/opTimelinePlugin/css/jquery.colorbox.css');
    $this->getResponse()->addJavascript('/opTimelinePlugin/js/jquery.colorbox.js', 'last');
    $this->getResponse()->addJavascript('/opTimelinePlugin/js/jquery.timeline.js', 'last');

    $this->publicFlags = Doctrine::getTable('ActivityData')->getPublicFlags();
    $this->viewPhoto = opTimeline::getViewPhoto();

    $this->setFileMaxSize();

    $this->memberId = $this->getUser()->getMember()->getId();
    $communityId = $request->getParameter('id');
    $this->community = Doctrine::getTable('Community')->find($communityId);
  }

  public function executeSmtTimeline(sfWebRequest $request)
  {
    $this->viewPhoto = opTimeline::getViewPhoto();

    $this->setFileMaxSize();

    return sfView::SUCCESS;
  }

  public function executeSmtMemberTimelineBy1(sfWebRequest $request)
  {
    $this->memberId = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->activityData =  Doctrine_Query::create()
      ->from('ActivityData ad')
      ->where('ad.in_reply_to_activity_id IS NULL')
      ->andWhere('ad.member_id = ?', $this->memberId)
      ->andWhere('ad.foreign_table IS NULL')
      ->andWhere('ad.foreign_id IS NULL')
      ->andWhere('ad.public_flag = ?', ActivityDataTable::PUBLIC_FLAG_SNS)
      ->orderBy('ad.id DESC')
      ->limit(1)
      ->execute();
    if ($this->activityData)
    {
      $this->createdAt = $this->activityData[0]->getCreatedAt();
      $this->body = $this->activityData[0]->getBody();
    }

    $this->setFileMaxSize();
  }

  public function executeSmtCommunityTimelineBy1(sfWebRequest $request)
  {
    $communityId = $request->getParameter('id');
    $this->activityData =  Doctrine_Query::create()
       ->from('ActivityData ad')
       ->where('ad.in_reply_to_activity_id IS NULL')
       ->andWhere('ad.foreign_table = ?', 'community')
       ->andWhere('ad.foreign_id = ?', $communityId)
       ->andWhere('ad.public_flag = ?', ActivityDataTable::PUBLIC_FLAG_SNS)
       ->orderBy('ad.id DESC')
       ->limit(1)
       ->execute();
    if ($this->activityData)
    {
      $this->createdAt = $this->activityData[0]->getCreatedAt();
      $this->body = $this->activityData[0]->getBody();
    }
    $this->memberId = $this->getUser()->getMemberId();
    $this->community = Doctrine::getTable('Community')->find($communityId);

    $this->setFileMaxSize();
  }

  public function executeSmtTimelineBy1(sfWebRequest $request)
  {
    $this->activityData =  Doctrine_Query::create()
       ->from('ActivityData ad')
       ->where('ad.in_reply_to_activity_id IS NULL')
       ->andWhere('ad.foreign_table IS NULL')
       ->andWhere('ad.public_flag = ?', ActivityDataTable::PUBLIC_FLAG_SNS)
       ->orderBy('ad.id DESC')
       ->limit(1)
       ->execute();
    if ($this->activityData)
    {
      $this->createdAt = $this->activityData[0]->getCreatedAt();
      $this->body = $this->activityData[0]->getBody();
    }

    $this->setFileMaxSize();
  }

  public function executeSmtTimelineMember(sfWebRequest $request)
  {
    $this->id = $request->getParameter('id');
    $this->member = Doctrine::getTable('Member')->find($this->id);
    $this->viewPhoto = opTimeline::getViewPhoto();

    $this->setFileMaxSize();
  }

  public function executeSmtTimelineCommunity(sfWebRequest $request)
  {
    $this->id = $request->getParameter('id');
    $this->viewPhoto = opTimeline::getViewPhoto();

    $this->setFileMaxSize();
    $communityId = $request->getParameter('id');
    $this->community = Doctrine::getTable('Community')->find($communityId);
  }
}

