<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision34_AddIsPreColumn extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->column($direction, 'community_member', 'is_pre', 'boolean', null, array(
      'default' => 0,
      'notnull' => true,
      'comment' => 'Member\\\'s position in this community',
    ));
  }

  public function postUp()
  {
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('CommunityeMember');
    $results = $conn->fetchAll('SELECT id FROM community_member WHERE position = ?', array('pre'));
    foreach ($results as $result)
    {
      $conn->update(Doctrine::getTable('CommunityMember'), array('is_pre' => true), array('id' => $result['id']));
    }
  }

  public function preDown()
  {
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('CommunityeMember');
    $results = $conn->fetchAll('SELECT id FROM community_member WHERE is_pre = ?', array(true));
    foreach ($results as $result)
    {
      $conn->update(Doctrine::getTable('CommunityMember'), array('position' => 'pre'), array('id' => $result['id']));
    }
  }
}
