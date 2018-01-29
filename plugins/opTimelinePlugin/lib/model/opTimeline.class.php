<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTimeline
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 */

class opTimeline
{

  /**
   * @var opTimelineUser
   */
  private $user;

  private $imageContentSize;
  private $baseUrl;


  public function __construct(opTimelineUser $user, array $params)
  {
    $this->user = $user;

    $this->imageContentSize = $params['image_size'];
    $this->baseUrl = $params['base_url'];
  }

  const COMMENT_DISPLAY_MAX = 10;
  const MINIMUM_IMAGE_WIDTH = 285;

  public function addPublicFlagByActivityDataForSearchAPIByActivityData(array $responseDataList, $activityDataList)
  {
    $publicFlags = array();
    foreach ($activityDataList as $activity)
    {
      $publicFlags[$activity->getId()] = $activity->getPublicFlag();
    }

    $publicStatusTextList = array(
        ActivityDataTable::PUBLIC_FLAG_OPEN => 'open',
        ActivityDataTable::PUBLIC_FLAG_SNS => 'sns',
        ActivityDataTable::PUBLIC_FLAG_FRIEND => 'friend',
        ActivityDataTable::PUBLIC_FLAG_PRIVATE => 'private'
    );

    foreach ($responseDataList as &$data)
    {
      $publicFlag = $publicFlags[$data['id']];
      $data['public_status'] = $publicStatusTextList[$publicFlag];
    }
    unset($data);

    return $responseDataList;
  }

  /**
   * メソッドを実行する前にopJsonApiをロードしておく必要がある
   */
  public function createActivityDataByActivityDataAndViewerMemberIdForSearchAPI($activityDataList, $viewerMemberId)
  {
    $activityIds = array();
    foreach ($activityDataList as $activity)
    {
      $activityIds[] = $activity->getId();
    }

    if (empty($activityIds))
    {
      return array();
    }

    $replyActivityDataList = $this->findReplyActivityDataByActivityIdsGroupByActivityId($activityIds);

    $memberIds = $this->extractionMemberIdByActivityDataAndReplyActivityDataRows(
                    $activityDataList, $replyActivityDataList);
    $memberDataList = $this->user->createMemberDataByViewerMemberIdAndMemberIdsForAPIResponse($viewerMemberId, $memberIds);

    $responseDataList = $this->createActivityDataByActivityDataAndMemberDataForSearchAPI($activityDataList, $memberDataList);

    foreach ($responseDataList as &$response)
    {
      $id = $response['id'];

      if (isset($replyActivityDataList[$id]))
      {
        $replies = $replyActivityDataList[$id];

        $response['replies'] = $this->createActivityDataByActivityDataRowsAndMemberDataForSearchAPI($replies['data'], $memberDataList);
        $response['replies_count'] = $replies['count'];
      }
      else
      {
        $response['replies'] = null;
        $response['replies_count'] = 0;
      }
      $response['body'] = htmlspecialchars($response['body'], ENT_QUOTES, 'UTF-8', false);
      $response['body_html'] = htmlspecialchars($response['body_html'], ENT_QUOTES, 'UTF-8', false);
    }
    unset($response);

    return $responseDataList;
  }

  private function extractionMemberIdByActivityDataAndReplyActivityDataRows($activities, $replyActivitiyRows)
  {
    $memberIds = array();
    foreach ($activities as $activity)
    {
      $memberIds[] = $activity->getMemberId();
    }

    foreach ($replyActivitiyRows as $activityDataList)
    {
      foreach ($activityDataList['data'] as $activityData)
      {
        $memberIds[] = $activityData['member_id'];
      }
    }

    $memberIds = array_unique($memberIds);

    return $memberIds;
  }

  private function createActivityDataByActivityDataAndMemberDataForSearchAPI($activityDataList, $memberData)
  {
    $activityIds = array();
    foreach ($activityDataList as $activity)
    {
      $activityIds[] = $activity->getId();
    }

    $activityImageUrls = $this->findActivityImageUrlsByActivityIds($activityIds);

    $responseDataList = array();
    foreach ($activityDataList as $activity)
    {
      if (isset($activityImageUrls[$activity->getId()]))
      {
        //@todo symfonyの形式に変更させる
        //$activityImageUrl = sf_image_path($activityImageUrls[$activity->getId()], array(), true);

        $activityImageUrl = $activityImageUrls[$activity->getId()];
      }
      else
      {
        $activityImageUrl = null;
      }

      $imageUrls = $this->getImageUrlInfoByImageUrl($activityImageUrl);

      $responseData['id'] = $activity->getId();
      $responseData['member'] = $memberData[$activity->getMemberId()];

      $responseData['body'] = preg_replace('/<br\s\/>/', '&lt;br&nbsp;/&gt;', $activity->getBody());
      $responseData['body_html'] = op_activity_linkification(nl2br(op_api_force_escape($responseData['body'])));
      $responseData['uri'] = $activity->getUri();
      $responseData['source'] = $activity->getSource();
      $responseData['source_uri'] = $activity->getSourceUri();

      $responseData['image_url'] = $imageUrls['small'];
      $responseData['image_large_url'] = $imageUrls['large'];
      $responseData['created_at'] = date('r', strtotime($activity->getCreatedAt()));

      $responseDataList[] = $responseData;
    }

    return $responseDataList;
  }

  private function getImageUrlInfoByImageUrl($imageUrl)
  {
    if (is_null($imageUrl))
    {
      return array(
        'large' => null,
        'small' => null,
      );
    }

    $imagePath = $this->convertImageUrlToImagePath($imageUrl);

    if (!file_exists($imagePath))
    {
      return array(
        'large' => opTimelineImage::getNotImageUrl(),
        'small' => opTimelineImage::getNotImageUrl(),
      );
    }

    $minimumDirPath = opTimelineImage::findUploadDirPath($imagePath, self::MINIMUM_IMAGE_WIDTH);
    $imageName = pathinfo($imagePath, PATHINFO_BASENAME);
    $minimumImagePath = $minimumDirPath.'/'.$imageName;

    if (!file_exists($minimumImagePath))
    {
      return array(
        'large' => $imageUrl,
        'small' => $imageUrl,
      );
    }

    $minimumImageUrl = str_replace(sfConfig::get('sf_web_dir'), $this->baseUrl, $minimumImagePath);

    return array(
      'large' => $imageUrl,
      'small' => $minimumImageUrl,
    );
  }

  private function convertImageUrlToImagePath($imageUrl)
  {
    $match = array();
    preg_match("/(https?:\/\/.*)(\/cache)/", $imageUrl, $match);

    return str_replace($match[1], sfConfig::get('sf_web_dir'), $imageUrl);
  }

  private function createActivityDataByActivityDataRowsAndMemberDataForSearchAPI($activityDataRows, $memberDataList)
  {

    $responseDataList = array();
    foreach ($activityDataRows as $row)
    {
      $responseData['id'] = $row['id'];
      $responseData['member'] = $memberDataList[$row['member_id']];

      $responseData['body'] = htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8', false);
      $responseData['body_html'] = op_activity_linkification(nl2br(htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8', false)));
      $responseData['uri'] = $row['uri'];
      $responseData['source'] = $row['source'];
      $responseData['source_uri'] = $row['source_uri'];

      //コメントでは画像を投稿できない
      $responseData['image_url'] = null;
      $responseData['image_large_url'] = null;
      $responseData['created_at'] = date('r', strtotime($row['created_at']));

      $responseDataList[] = $responseData;
    }

    return $responseDataList;
  }

  public function findReplyActivityDataByActivityIdsGroupByActivityId(array $activityIds)
  {
    static $queryCacheHash;

    if (!$queryCacheHash)
    {
      $q = Doctrine_Query::create();
      $q->from('ActivityData');
      $q->whereIn('in_reply_to_activity_id', $activityIds);
      $q->orderBy('in_reply_to_activity_id, created_at DESC');
      $searchResult = $q->fetchArray();

      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $searchResult = $q->fetchArray();
    }

    $replies = array();
    foreach ($searchResult as $row)
    {
      $targetId = $row['in_reply_to_activity_id'];

      if (!isset($replies[$targetId]['data']) || count($replies[$targetId]['data']) < self::COMMENT_DISPLAY_MAX)
      {
        $replies[$targetId]['data'][] = $row;
      }

      if (isset($replies[$targetId]['count']))
      {
        $replies[$targetId]['count']++;
      }
      else
      {
        $replies[$targetId]['count'] = 1;
      }
    }

    return $replies;
  }

  public function searchActivityDataByAPIRequestDataAndMemberId($requestDataList, $memberId)
  {
    $builder = opActivityQueryBuilder::create()
                    ->setViewerId($memberId);

    if (isset($requestDataList['target']))
    {
      if ('friend' === $requestDataList['target'])
      {
        $builder->includeFriends($requestDataList['target_id'] ? $requestDataList['target_id'] : null);
      }

      if ('community' === $requestDataList['target'])
      {
        $builder
                ->includeSelf()
                ->includeFriends()
                ->includeSns()
                ->setCommunityId($requestDataList['target_id']);
      }
    }
    else
    {
      if (isset($requestDataList['member_id']))
      {
        $builder->includeMember($requestDataList['member_id']);
      }
      else
      {
        $builder
                ->includeSns()
                ->includeFriends()
                ->includeSelf();
      }
    }

    $query = $builder->buildQuery();

    if (isset($requestDataList['keyword']))
    {
      $query->andWhereLike('body', $requestDataList['keyword']);
    }

    $globalAPILimit = sfConfig::get('op_json_api_limit', 20);
    if (isset($requestDataList['count']) && (int) $requestDataList['count'] < $globalAPILimit)
    {
      $query->limit($requestDataList['count']);
    }
    else
    {
      $query->limit($globalAPILimit);
    }

    if (isset($requestDataList['max_id']))
    {
      $query->addWhere('id <= ?', $requestDataList['max_id']);
    }

    if (isset($requestDataList['since_id']))
    {
      $query->addWhere('id > ?', $requestDataList['since_id']);
    }

    if (isset($requestDataList['activity_id']))
    {
      $query->addWhere('id = ?', $requestDataList['activity_id']);
    }

    $query->andWhere('in_reply_to_activity_id IS NULL');

    return $query->execute();
  }

  public function findActivityImageUrlsByActivityIds(array $actvityIds)
  {
    $query = new opDoctrineQuery();
    $query->select('activity_data_id, uri');
    $query->from('ActivityImage');
    $query->andWhereIn('activity_data_id', $actvityIds);

    $searchResult = $query->fetchArray();

    $imageUrls = array();
    foreach ($searchResult as $row)
    {
      $imageUrls[$row['activity_data_id']] = $row['uri'];
    }

    return $imageUrls;
  }

  public function embedImageUrlToContentForSearchAPI(array $responseDataList)
  {
    $imageUrls = array();
    foreach ($responseDataList as $row)
    {
      if (!is_null($row['image_url']))
      {
        if ('large' === $this->imageContentSize)
        {
          $imageUrls[$row['id']] = $row['image_large_url'];
        }
        else
        {
          $imageUrls[$row['id']] = $row['image_url'];
        }
      }
    }

    foreach ($responseDataList as &$data)
    {
      $id = $data['id'];

      if (isset($imageUrls[$id]))
      {
        $data['body'] = $data['body'].' '.$imageUrls[$id];
        $data['body_html'] = $data['body_html'].'<a href="'.$imageUrls[$id].'" rel="lightbox"><div><img src="'.$imageUrls[$id].'"></div></a>';
      }
    }

    return $responseDataList;
  }

  public function createPostActivityFromAPIByApiDataAndMemberId($apiData, $memberId)
  {
    $body = (string) $apiData['body'];

    $options = array();

    if (isset($apiData['public_flag']))
    {
      $options['public_flag'] = $apiData['public_flag'];
    }

    if (isset($apiData['in_reply_to_activity_id']))
    {
      $options['in_reply_to_activity_id'] = $apiData['in_reply_to_activity_id'];
    }

    if (isset($apiData['uri']))
    {
      $options['uri'] = $apiData['uri'];
    }
    elseif (isset($apiData['url']))
    {
      $options['uri'] = $apiData['url'];
    }

    if (isset($apiData['target']) && 'community' === $apiData['target'])
    {
      $options['foreign_table'] = 'community';
      $options['foreign_id'] = $apiData['target_id'];
    }

    $options['source'] = 'API';

    return Doctrine::getTable('ActivityData')->updateActivity($memberId, $body, $options);
  }

  public function createActivityImageByFileInfoAndActivityId(array $fileInfo, $activityId)
  {
    $file = new File();
    $file->setOriginalFilename(basename($fileInfo['name']));
    $file->setType($fileInfo['type']);

    $fileFormat = $file->getImageFormat();
    if (is_null($fileFormat) || '' == $fileFormat)
    {
      $fileFormat = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
    }

    $fileBaseName = md5(time()).'_'.$fileFormat;
    $filename = 'ac_'.$fileInfo['member_id'].'_'.$fileBaseName;

    $file->setName($filename);
    $file->setFilesize($fileInfo['size']);
    $bin = new FileBin();
    $bin->setBin($fileInfo['binary']);
    $file->setFileBin($bin);
    $file->save();

    $activityImage = new ActivityImage();
    $activityImage->setActivityDataId($activityId);
    $activityImage->setFileId($file->getId());
    $activityImage->setUri($this->getActivityImageUriByfileInfoAndFilename($fileInfo, $filename));
    $activityImage->setMimeType($file->type);
    $activityImage->save();

    $this->createUploadImageFileByFileInfoAndSaveFileName($fileInfo, $filename);

    return $activityImage;
  }

  private function getActivityImageUriByfileInfoAndFilename($fileInfo, $filename)
  {
    //ファイルテーブルの名前だと拡張式がついていない
    $filename = opTimelineImage::addExtensionToBasenameForFileTable($filename);
    $uploadPath = opTimelineImage::findUploadDirPath($filename);
    $uploadBasePath = str_replace(sfConfig::get('sf_web_dir'), '', $uploadPath);

    return $fileInfo['web_base_path'].$uploadBasePath.'/'.$filename;
  }

  private function createUploadImageFileByFileInfoAndSaveFileName($fileInfo, $filename)
  {
    $filename = opTimelineImage::addExtensionToBasenameForFileTable($filename);
    $uploadDirPath = opTimelineImage::findUploadDirPath($fileInfo['name']);

    $fileSavePath = $uploadDirPath.'/'.$filename;

    opTimelineImage::copyByResourcePathAndTargetPath($fileInfo['tmp_name'], $fileSavePath);

    $imageSize = opTimelineImage::getImageSizeByPath($fileSavePath);
    //画像が縮小サイズより小さい場合は縮小した画像を作成しない
    if ($imageSize['width'] <= self::MINIMUM_IMAGE_WIDTH)
    {
      return true;
    }

    $minimumDirPath = opTimelineImage::findUploadDirPath($fileInfo['name'], self::MINIMUM_IMAGE_WIDTH);
    $minimumPath = $minimumDirPath.'/'.basename($fileSavePath);

    $paths = array(
        'resource' => $fileSavePath,
        'target' => $minimumPath,
    );

    opTimelineImage::createMinimumImageByWidthSizeAndPaths(self::MINIMUM_IMAGE_WIDTH, $paths);
  }

  public static function getViewPhoto()
  {
    $viewPhoto = Doctrine::getTable('SnsConfig')->get('op_timeline_plugin_view_photo', false);
    if (false !== $viewPhoto)
    {
      return $viewPhoto;
    }
    return 1;
  }
}
