<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityConfigTable extends Doctrine_Table
{
  public function retrievesByCommunityId($communityId)
  {
    return $this->createQuery()
      ->where('community_id = ?', $communityId)
      ->execute();
  }

  public function retrieveByNameAndCommunityIdQuery($name, $communityId)
  {
    return $this->createQuery()
      ->where('name = ?', $name)
      ->andWhere('community_id = ?', $communityId);
  }

  public function retrieveByNameAndCommunityId($name, $communityId)
  {
    $q = $this->retrieveByNameAndCommunityIdQuery($name, $communityId);

    return $q->fetchOne();
  }

  public function retrieveValueByNameAndCommunityId($name, $communityId)
  {
    $q = $this->retrieveByNameAndCommunityIdQuery($name, $communityId);

    $result = $q->select('value')
      ->fetchOne(array(), Doctrine::HYDRATE_NONE);

    if (!$result)
    {
      return null;
    }

    return $result[0];
  }
}
