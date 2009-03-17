<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class addCommunityCategory extends opMigration
{
  public function up()
  {
    $this->createTable('community_category', array(
      'id' => array(
        'type'          => 'integer',
        'length'        => 4,
        'notnull'       => true,
        'autoincrement' => true,
      ),

      'lft_key' => array(
        'type'    => 'integer',
        'length'  => 4,
        'default' => '0',
      ),

      'rht_key' => array(
        'type'    => 'integer',
        'length'  => 4,
        'default' => '0',
      ),

      'tree_key' => array(
        'type'    => 'integer',
        'length'  => 4,
        'default' => null,
      ),

      'name' => array(
        'type'    => 'string',
        'length'  => 64,
        'default' => '',
        'notnull' => true,
      ),

      'sort_order' => array(
        'type'    => 'integer',
        'length'  => 4,
        'default' => null,
      ),

      'is_allow_member_community' => array(
        'type'    => 'integer',
        'length'  => 1,
        'default' => '1',
      ),
    ), array('primary' => array('id')));

    $this->addColumn('community', 'community_category_id', 'integer', array(
      'length'  => 4,
      'default' => null,
      'notnull' => false,
    ));
  }

  public function postUp()
  {
    $conn = Doctrine_Manager::connection();

    $conn->export->createForeignKey('community', array(
      'name'         => 'community_FK_2',
      'local'        => 'community_category_id',
      'foreign'      => 'id',
      'foreignTable' => 'community_category',
      'onDelete'     => 'SET NULL'
    ));

    $conn->export->createIndex('community_category', 'community_category_I_1', array('fields' => array('lft_key'  => array())));
    $conn->export->createIndex('community_category', 'community_category_I_2', array('fields' => array('rht_key'  => array())));
    $conn->export->createIndex('community_category', 'community_category_I_3', array('fields' => array('tree_key' => array())));

    $category = new CommunityCategory();
    $category->setName('コミュニティカテゴリ');
    $category->setIsAllowMemberCommunity(true);
    $category->save();
    $treeKey = $category->getId();

    $child = new CommunityCategory();
    $child->setName('地域');
    $child->setTreeKey($treeKey);
    $child->setIsAllowMemberCommunity(true);
    $child->save();

    $child = new CommunityCategory();
    $child->setName('グルメ');
    $child->setTreeKey($treeKey);
    $child->setIsAllowMemberCommunity(true);
    $child->save();

    $child = new CommunityCategory();
    $child->setName('スポーツ');
    $child->setTreeKey($treeKey);
    $child->setIsAllowMemberCommunity(true);
    $child->save();
  }

  public function down()
  {
  }
}
