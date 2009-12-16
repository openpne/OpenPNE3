<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision35_RemovePositionColumn extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->removeColumn('community_member', 'position');
  }

  public function down()
  {
    $this->addColumn('community_member', 'position', 'string', '32', array(
      'default' => '',
      'comment' => 'Is pre member?',
    ));
  }

  public function preUp()
  {
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('CommunityeMember');
    $results = $conn->fetchAll('SELECT id, member_id, community_id, position from community_member where position <> ? AND position IS NOT NULL', array(''));
    foreach ($results as $result)
    {
      if (isset($result['position']) && $result['position'] && $result['position'] !== 'pre')
      {
        $communityMemberPosition = new CommunityMemberPosition();
        $communityMemberPosition->setMemberId($result['member_id']);
        $communityMemberPosition->setCommunityId($result['community_id']);
        $communityMemberPosition->setCommunityMemberId($result['id']);
        $communityMemberPosition->setName($result['position']);
        $communityMemberPosition->save();
      }
    }
  }

  public function postDown()
  {
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('CommunityMemberPosition');
    $results = $conn->fetchAll('SELECT community_member_id, name from community_member_position');
    foreach ($results as $result)
    {
      $conn->update(Doctrine::getTable('CommunityMember'), array('position' => $result['name']), array('id' => $result['community_member_id']));
    }
  }
}
