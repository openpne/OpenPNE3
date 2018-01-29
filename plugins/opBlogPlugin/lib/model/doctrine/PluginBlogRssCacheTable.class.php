<?php
/**
 */
class PluginBlogRssCacheTable extends Doctrine_Table
{
  public function deleteByMemberId($memberId)
  {
    Doctrine_Query::create()
      ->delete('BlogRssCache')
      ->where('member_id = ?', $memberId)
      ->execute();
  }

  public function update($offset = 0, $size = 0)
  {
    $q = Doctrine::getTable('MemberConfig')->createQuery()
      ->where('name = ?', 'blog_url');

    if ($size)
    {
      $q->orderBy('member_id')
        ->limit($size)
        ->offset($offset);
    }
    $memberConfigList = $q->execute();

    foreach ($memberConfigList as $memberConfig)
    {
      $this->updateByMemberIdAndUrl($memberConfig->getMemberId(), $memberConfig->getValue());
    }

    return $memberConfigList->count();
  }

  public function countFeedUrl()
  {
    return Doctrine::getTable('MemberConfig')->createQuery()
      ->where('name = ?', 'blog_url')
      ->count();
  }

  public function updateByMemberId($memberId)
  {
    $memberConfig = Doctrine::getTable('MemberConfig')->findOneByNameAndMemberId('blog_url', $memberId);
    $this->updateByMemberIdAndUrl($memberId, $memberConfig->getValue());
  }

  public function getFriendBlogListByMemberId($memberId, $size = 20)
  {
    $friendIds = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($memberId);
    $memberIdList = array_diff($friendIds, $this->getAccessBlockedFriendMemberIds($memberId));

    if (!count($memberIdList))
    {
      return array();
    }

    return Doctrine::getTable('BlogRssCache')->createQuery()
      ->where('member_id IN ('.implode(',', $memberIdList).')')
      ->orderBy('date DESC')
      ->limit($size)
      ->execute();
  }

  public function getAllMembers($size = 20)
  {
    return Doctrine::getTable('BlogRssCache')->createQuery()
      ->orderBy('date DESC')
      ->limit($size)
      ->execute();
  }

  public function findByMember($member, $size = 20)
  {
    return $this->findByMemberId($member->getId(), $size);
  }

  public function findByMemberId($memberId, $size = 20)
  {
    return Doctrine::getTable('BlogRssCache')->createQuery()
      ->where('member_id = ?', $memberId)
      ->orderBy('date DESC')
      ->limit($size)
      ->execute();
  }

  public function getAccessBlockedFriendMemberIds($memberId)
  {
    $relationList = Doctrine::getTable('MemberRelationship')->createQuery()
      ->select('member_id_from AS id')
      ->where('member_id_to = ?', $memberId)
      ->andWhere('is_access_block = ?', true)
      ->execute(array(), Doctrine::HYDRATE_ARRAY);

    $memberIds = array();
    foreach ($relationList as $relation)
    {
      $memberIds[] = $relation['id'];
    }
    return $memberIds;
  }

  protected function updateByMemberIdAndUrl($memberId, $url)
  {
    $feed = opBlogPlugin::getFeedByUrl($url);
    foreach ($feed as $item)
    {
      $blogRssCache = $this->findOneByMemberIdAndLink(
        $memberId,
        $item['link']
      );

      if (($blogRssCache && $blogRssCache->getDate() == $item['date']) ||
        strtotime($item['date']) > time())
      {
        continue;
      }

      if (!$blogRssCache)
      {
        $blogRssCache = new BlogRssCache();
      }
      $blogRssCache->setMemberId($memberId);
      $blogRssCache->setTitle($item['title']);
      $blogRssCache->setDescription($item['description']);
      $blogRssCache->setLink($item['link']);
      $blogRssCache->setDate($item['date']);
      $blogRssCache->save();
    }

    return true;
  }
}
