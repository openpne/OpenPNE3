<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginMemberApplicationTable
 *
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

class PluginMemberApplicationTable extends Doctrine_Table
{
  /**
   * find one by application and member
   *
   * @param Application $application
   * @param Member      $member
   * @return MemberApplication
   */
  public function findOneByApplicationAndMember($application, $member)
  {
    return $this->createQuery()
      ->where('application_id = ?', $application->getId())
      ->andWhere('member_id =?', $member->getId())
      ->fetchOne();
  }

 /**
  * get member applications
  *
  * @param integer $memberId
  * @param integer $viewerId
  * @return Doctrine_Collection
  */
  public function getMemberApplications($memberId, $viewerId = null, $isCheckActive = true)
  {
    if ($viewerId === null)
    {
      $viewerId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $q = $this->createQuery('ma')
      ->where('member_id = ?', $memberId);
    if ($memberId != $viewerId)
    {
      $dql = 'ma.public_flag = ?';
      $dqlParams = array('public');

      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($memberId, $viewerId);
      if ($relation && $relation->isFriend())
      {
        $dql .= ' OR ma.public_flag = ?';
        $dqlParams[] = 'friends';
      }
      $q->andWhere('('.$dql.')', $dqlParams);
    }

    if ($isCheckActive)
    {
      $q->innerJoin('ma.Application a')
        ->andWhere('is_active = ?', true);
    }

    $q->orderBy('sort_order');
    return $q->execute();
  }
}
