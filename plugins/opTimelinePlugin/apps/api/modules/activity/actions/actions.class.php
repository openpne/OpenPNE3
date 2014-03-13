<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */


/**
 * activity actions.
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 * @author     tatsuya ichikawa <ichikawa@tejimaya.com>
 */
class activityActions extends opJsonApiActions
{

  const TWEET_MAX_LENGTH = 140;
  const COMMENT_DEFAULT_LIMIT = 15;

  /**
   * active data model maked by POSTAPI
   */
  private $createdActivity;

  /**
   * @var opTimeline
   */
  private $timeline;

  const DEFAULT_IMAGE_SIZE = 'large';

  public function preExecute()
  {
    parent::preExecute();

    $user = new opTimelineUser();

    $params = array();
    $params['image_size'] = $this->getRequestParameter('image_size', self::DEFAULT_IMAGE_SIZE);

    $request = sfContext::getInstance()->getRequest();
    $params['base_url'] = $request->getUriPrefix().$request->getRelativeUrlRoot();

    $this->timeline = new opTimeline($user, $params);

    $this->loadHelperForUseOpJsonAPI();
    $this->memberId = $this->getUser()->getMemberId();
  }

  public function executeCommentSearch(sfWebRequest $request)
  {
    if (!isset($request['timeline_id']))
    {
      $this->forward400('timeline id is not specified');
    }

    if ('' === (string) $request['timeline_id'])
    {
      $this->forward400('timeline id is not specified');
    }

    $limit = isset($request['count']) ? $request['count'] : sfConfig::get('op_json_api_limit', self::COMMENT_DEFAULT_LIMIT);

    $timelineId = $request['timeline_id'];
    $activity = Doctrine::getTable('ActivityData')->find($timelineId);

    if (0 < count($activity))
    {
      $this->replies = $activity->getReplies(ActivityDataTable::PUBLIC_FLAG_SNS, $limit);
    }
  }

  public function executePost(sfWebRequest $request)
  {
    $errorResponse = $this->getErrorResponseIfBadRequestOfPost($request);

    if (!is_null($errorResponse))
    {
      return $this->renderJSONDirect($errorResponse);
    }

    $this->createActivityDataByRequest($request);

    $responseData = $this->createResponActivityDataOfPost();
    $responseData['body'] = htmlspecialchars($responseData['body'], ENT_QUOTES, 'UTF-8', false);
    $responseData['body_html'] = htmlspecialchars($responseData['body_html'], ENT_QUOTES, 'UTF-8', false);

    if ($this->isUploadImagePost())
    {
      return $this->renderJSONDirect(array('status' => 'success', 'message' => 'file up success', 'data' => $responseData));
    }

    return $this->renderJSONDirect(array('status' => 'success', 'message' => 'tweet success', 'data' => $responseData));
  }

  private function isUploadImagePost()
  {
    return (!empty($_FILES) && (int) $_FILES['timeline-submit-upload']['size'] !== 0);
  }

  private function getErrorResponseIfBadRequestOfPost(sfWebRequest $request)
  {
    $errorInfo = $this->getErrorResponseIfBadRequestOfTweetPost($request);

    if (!empty($errorInfo))
    {
      return $errorInfo;
    }

    if ($this->isUploadImagePost())
    {
      $fileInfo = $this->createFileInfo($request);

      if ($fileInfo['size'] >= opTimelinePluginUtil::getFileSizeMax())
      {
        return array('status' => 'error', 'message' => 'file size over', 'type' => 'file_size');
      }

      $stream = fopen($fileInfo['tmp_name'], 'r');

      if (false === $stream)
      {
        return array('status' => 'error', 'message' => 'file upload error', 'type' => 'upload');
      }

      if (!$this->isImageUploadByFileInfo($fileInfo))
      {
        return array('status' => 'error', 'message' => 'not image', 'type' => 'not_image');
      }
    }

    return null;
  }

  private function createResponActivityDataOfPost()
  {
    $this->loadHelperForUseOpJsonAPI();
    $activity = op_api_activity($this->createdActivity);

    $replies = $this->createdActivity->getReplies();
    if (0 < count($replies))
    {
      $activity['replies'] = array();

      foreach ($replies as $reply)
      {
        $activity['replies'][] = op_api_activity($reply);
      }
    }

    return $activity;
  }

  /**
   * なぜかPOSTAPIだとJSONレンダーがうまくうごかなかった
   */
  private function renderJSONDirect(array $data)
  {
    //header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data);
    exit;
  }

  /**
   * @todo ファイル情報じゃないのが含まれているので、それを分ける
   */
  private function createFileInfo()
  {
    $request = sfContext::getInstance()->getRequest();

    //開発を簡単にするためにコメントアウト
    $fileInfo = $_FILES['timeline-submit-upload'];
    $fileInfo['stream'] = fopen($fileInfo['tmp_name'], 'r');
    $fileInfo['dir_name'] = '/a'.$this->getUser()->getMember()->getId();
    $fileInfo['binary'] = stream_get_contents($fileInfo['stream']);
    $fileInfo['web_base_path'] = $request->getUriPrefix().$request->getRelativeUrlRoot();
    $fileInfo['member_id'] = $this->getUser()->getMemberId();

    return $fileInfo;
  }

  private function createActivityDataByRequest(sfWebRequest $request)
  {
    $saveData = $request->getParameterHolder()->getAll();
    $memberId = $this->getUser()->getMemberId();

    $this->createdActivity = $this->timeline->createPostActivityFromAPIByApiDataAndMemberId($saveData, $memberId);

    if ($this->isUploadImagePost())
    {
      $fileInfo = $this->createFileInfo($request);
      $this->timeline->createActivityImageByFileInfoAndActivityId($fileInfo, $this->createdActivity->getId());
    }
  }

  private function getErrorResponseIfBadRequestOfTweetPost(sfWebRequest $request)
  {
    $body = (string) $request['body'];

    $errorInfo = array('status' => 'error', 'type' => 'tweet');

    if (is_null($body) || '' == mb_ereg_replace('^(\s|　)+|(\s|　)+$', '', $body))
    {
      $errorInfo['message'] = 'body parameter not specified.';

      return $errorInfo;
    }

    if (mb_strlen($body) > self::TWEET_MAX_LENGTH)
    {
      $errorInfo['message'] = 'The body text is too long.';

      return $errorInfo;
    }

    if (isset($request['target']) && 'community' === $request['target'])
    {
      if (!isset($request['target_id']))
      {
        $errorInfo['message'] = 'target_id parameter not specified.';

        return $errorInfo;
      }
    }

    return null;
  }

  private function isImageUploadByFileInfo(array $fileInfo)
  {
    foreach (opTimelinePluginUtil::getUploadAllowImageTypeList() as $type)
    {
      $contentType = 'image/'.$type;

      if ($fileInfo['type'] === $contentType)
      {
        return true;
      }
    }

    return false;
  }

  public function executeSearch(sfWebRequest $request)
  {
    $parameters = $request->getGetParameters();

    if (isset($parameters['target']))
    {
      $this->forward400IfInvalidTargetForSearchAPI($parameters);
    }

    $activityData = $this->timeline->searchActivityDataByAPIRequestDataAndMemberId(
                    $request->getGetParameters(), $this->getUser()->getMemberId());

    $activitySearchData = $activityData->getData();
    //一回も投稿していない
    if (empty($activitySearchData))
    {
      return $this->renderJSON(array('status' => 'success', 'data' => array()));
    }

    $responseData = $this->timeline->createActivityDataByActivityDataAndViewerMemberIdForSearchAPI(
                    $activityData, $this->getUser()->getMemberId());

    $responseData = $this->timeline->addPublicFlagByActivityDataForSearchAPIByActivityData($responseData, $activityData);
    $responseData = $this->timeline->embedImageUrlToContentForSearchAPI($responseData);

    return $this->renderJSON(array('status' => 'success', 'data' => $responseData));
  }

  private function loadHelperForUseOpJsonAPI()
  {
    //op_api_activityを使用するために必要なヘルパーを読み込む
    $configuration = $this->getContext()->getConfiguration();
    $configuration->loadHelpers('opJsonApi');
    $configuration->loadHelpers('opUtil');
    $configuration->loadHelpers('Asset');
    $configuration->loadHelpers('Helper');
    $configuration->loadHelpers('Tag');
    $configuration->loadHelpers('sfImage');
  }

  private function forward400IfInvalidTargetForSearchAPI(array $params)
  {
    $validTargets = array('friend', 'community');

    if (!in_array($params['target'], $validTargets))
    {
      return $this->forward400('target parameter is invalid.');
    }

    if ('community' === $params['target'])
    {
      $this->forward400Unless(
              Doctrine::getTable('CommunityMember')->isMember($this->getUser()->getMemberId(), $params['target_id']),
              'You are not community member'
              );

      $this->forward400Unless($params['target_id'], 'target_id parameter not specified.');
    }
  }

  public function executeMember(sfWebRequest $request)
  {
    if ($request['id'])
    {
      $request['member_id'] = $request['id'];
    }

    if (isset($request['target']))
    {
      unset($request['target']);
    }

    $this->forward('activity', 'search');
  }

  public function executeFriends(sfWebRequest $request)
  {
    $request['target'] = 'friend';

    if (isset($request['member_id']))
    {
      $request['target_id'] = $request['member_id'];
      unset($request['member_id']);
    }
    elseif (isset($request['id']))
    {
      $request['target_id'] = $request['id'];
      unset($request['id']);
    }

    $this->forward('activity', 'search');
  }

  public function executeCommunity(sfWebRequest $request)
  {
    $request['target'] = 'community';

    if (isset($request['community_id']))
    {
      $request['target_id'] = $request['community_id'];
      unset($request['community_id']);
    }
    elseif (isset($request['id']))
    {
      $request['target_id'] = $request['id'];
      unset($request['id']);
    }
    else
    {
      $this->forward400('community_id parameter not specified.');
    }

    $this->forward('activity', 'search');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if (isset($request['activity_id']))
    {
      $activityId = $request['activity_id'];
    }
    elseif (isset($request['id']))
    {
      $activityId = $request['id'];
    }
    else
    {
      $this->forward400('activity_id parameter not specified.');
    }

    $activity = Doctrine::getTable('ActivityData')->find($activityId);

    $this->forward404Unless($activity, 'Invalid activity id.');

    $this->forward403Unless($activity->getMemberId() === $this->getUser()->getMemberId());

    $activity->delete();

    return $this->renderJSON(array('status' => 'success'));
  }

  public function executeMentions(sfWebRequest $request)
  {
    $builder = opActivityQueryBuilder::create()
                    ->setViewerId($this->getUser()->getMemberId())
                    ->includeMentions();

    $query = $builder->buildQuery()
                    ->andWhere('in_reply_to_activity_id IS NULL')
                    ->andWhere('foreign_table IS NULL')
                    ->andWhere('foreign_id IS NULL')
                    ->limit(20);

    $this->activityData = $query->execute();

    $this->setTemplate('array');
  }
}
