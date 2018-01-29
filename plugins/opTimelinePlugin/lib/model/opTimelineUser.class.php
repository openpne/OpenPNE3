<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTimelineUser
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 */

class opTimelineUser
{

  public function createMemberDataByViewerMemberIdAndMemberIdsForAPIResponse($viewerMemberId, $memberIds)
  {

    $friendAndBlocks = $this->findFriendMemberIdsAndBlockMemberIdsByMemberId($viewerMemberId);
    $imageUrls = $this->findImageFileUrlsByMemberIds($memberIds);

    $introductionId = $this->findIntroductionIdFromProfile();
    $introductions = $this->findMemberIntroductionByMemberIdsAndIntroductionId($memberIds, $introductionId);

    $firendCounts = $this->findFriendCountByMemberIds($memberIds);

    $memberNames = $this->findMemberNamesByMemberIds($memberIds);

    $memberDataList = array();

    foreach ($memberIds as $memberId)
    {
      $memberData = array();
      $memberData['id'] = $memberId;
      $memberData['profile_image'] = $imageUrls[$memberId];
      $memberData['screen_name'] = $memberNames[$memberId];
      $memberData['name'] = $memberNames[$memberId];
      $memberData['profile_url'] = op_api_member_profile_url($memberId);
      $memberData['friend'] = isset($friendAndBlocks['friend'][$memberId]);
      $memberData['blocking'] = isset($friendAndBlocks['block'][$memberId]);
      $memberData['self'] = $viewerMemberId === $memberId;
      $memberData['friends_count'] = $firendCounts[$memberId];
      $memberData['self_introduction'] = isset($introductions[$memberId]) ? (string) $introductions[$memberId] : null;

      $memberDataList[$memberId] = $memberData;
    }

    return $memberDataList;
  }

  public function findMemberNamesByMemberIds(array $memberIds)
  {
    static $queryCacheHash;

    $q = Doctrine_Query::create();

    if (!$queryCacheHash)
    {
      $q = Doctrine_Query::create();
      $q->from('Member m');
      $q->select("name");

      $q->whereIn('id', $memberIds);
      $searchResult = $q->fetchArray();

      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $searchResult = $q->fetchArray();
    }

    $names = array();
    foreach ($searchResult as $row)
    {
      $names[$row['id']] = $row['name'];
    }

    return $names;
  }

  /**
   *
   * @param array $memberIds
   * @return array (member_id => friend_count)
   */
  public function findFriendCountByMemberIds(array $memberIds)
  {
    static $queryCacheHash;

    $q = Doctrine_Query::create();

    if (!$queryCacheHash)
    {

      //innerjoinをするとエラーが出てしまったので、２回SQLを実行する
      $inactiveIds = Doctrine::getTable('Member')->getInactiveMemberIds();

      $q = Doctrine_Query::create();
      $q->from('MemberRelationship mr');
      $q->select("member_id_to as member_id, COUNT('*')");

      $q->whereIn('member_id_to', $memberIds);
      $q->AndWhereNotIn('member_id_from', $inactiveIds);
      $q->groupBy('member_id_to');

      $searchResult = $q->fetchArray();

      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $searchResult = $q->fetchArray();
    }

    $friendCounts = array();
    foreach ($searchResult as $row)
    {
      $friendCounts[$row['member_id']] = $row['COUNT'];
    }

    return $friendCounts;
  }

    public function findIntroductionIdFromProfile()
  {
    static $queryCacheHash;

    $q = Doctrine_Query::create();

    if (!$queryCacheHash)
    {
      $q->from('Profile');
      $q->select('id');
      $q->where("name = 'op_preset_self_introduction'");

      $searchResult = $q->fetchArray();
      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $searchResult = $q->fetchArray();
    }

    if (empty($searchResult))
    {
      return false;
    }

    return $searchResult[0]['id'];
  }

  /**
   *
   * @return array
   *  (memberId => Introduction)
   */
  public function findMemberIntroductionByMemberIdsAndIntroductionId(array $memberIds, $introductionId)
  {
    static $queryCacheHash;

    $q = Doctrine_Query::create();

    if (!$queryCacheHash)
    {
      $q->from('MemberProfile');
      $q->select('value, member_id');
      $q->where('profile_id = ?', $introductionId);
      $q->andWhereIn('member_id', $memberIds);

      $searchResult = $q->fetchArray();
      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $searchResult = $q->fetchArray();
    }

    $profiles = array();
    foreach ($searchResult as $row)
    {
      $profiles[$row['member_id']] = $row['value'];
    }

    return $profiles;
  }

  /**
   *
   * @return array
   *   (memberId => imagePath...)
   */
  public function findImageFileUrlsByMemberIds($memberIds)
  {
    static $queryCacheHash;

    $q = Doctrine_Query::create();

    if (!$queryCacheHash)
    {
      $q->from('MemberImage mi, mi.File f');
      $q->select('mi.member_id, f.name');

      $searchResult = $q->fetchArray();
      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $searchResult = $q->fetchArray();
    }

    $imageUrls = array();

    foreach ($searchResult as $row)
    {
      $image = sf_image_path($row['File']['name'], array('size' => '48x48'), true);
      $imageUrls[$row['member_id']] = $image;
    }

    //画像を設定していないユーザーはno_imageにする
    foreach ($memberIds as $id)
    {
      if (!isset($imageUrls[$id]))
      {
        $imageUrls[$id] = opTimelineImage::getNotImageUrl();
      }
    }

    return $imageUrls;
  }

  /**
   *
   * @return array
   *   friend => array(memberId...)
   *   block  => array(memberId...)
   */
  public function findFriendMemberIdsAndBlockMemberIdsByMemberId($memberId)
  {
    static $queryCacheHash;

    $q = Doctrine_Query::create()->from('MemberRelationship mr');
    $q->select('member_id_from, is_friend, is_access_block');

    $q->where('member_id_to = ?', $memberId);
    $q->andWhere('is_friend = 1 OR is_access_block = 1 ');

    if (!$queryCacheHash)
    {
      $searchResult = $q->fetchArray();
      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);

      $searchResult = $q->fetchArray();
    }

    $friendIds = array();
    $blockIds = array();

    foreach ($searchResult as $row)
    {
      if ($row['is_friend'])
      {
        $friendIds[] = $row['member_id_from'];
      }

      if ($row['is_access_block'])
      {
        $blockIds[] = $row['member_id_from'];
      }
    }

    return array('friend' => $friendIds, 'block' => $blockIds);
  }
}
