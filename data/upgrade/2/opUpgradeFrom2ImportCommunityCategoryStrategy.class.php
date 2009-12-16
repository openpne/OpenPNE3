<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing community category.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2ImportCommunityCategoryStrategy extends opUpgradeSQLImportStrategy
{
  public function run()
  {
    $this->getDatabaseManager();
    $this->conn = Doctrine_Manager::connection();

    $this->conn->beginTransaction();
    try
    {
      $this->doRun();

      $this->conn->commit();
    }
    catch (Exception $e)
    {
      $this->conn->rollback();

      throw $e;
    }
  }

  public function doRun()
  {
    $this->conn->execute('ALTER TABLE c_commu CHANGE image_filename image_filename text CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    $this->conn->execute('ALTER TABLE c_commu CHANGE name name text CHARACTER SET utf8 COLLATE utf8_unicode_ci');

    // c_commu.name must be unique but it was restricted by application-level-check
    // so, on importing that, we should consider of duplicated names
    $duplicatedCommunityIds = (array)$this->conn->fetchColumn('SELECT a.c_commu_id FROM c_commu AS a WHERE a.name IN (SELECT b.name FROM c_commu AS b WHERE b.c_commu_id <> a.c_commu_id)');
    if ($duplicatedCommunityIds)
    {
      $duplicatedReplacements = implode(',', array_fill(0, count($duplicatedCommunityIds), '?'));
    }

    $parents = $this->conn->fetchAssoc('SELECT * FROM c_commu_category_parent');
    foreach ($parents as $parent)
    {
      $childs = $this->conn->fetchAssoc('SELECT * FROM c_commu_category WHERE c_commu_category_parent_id = ?', array($parent['c_commu_category_parent_id']));

      $newParentSQL = 'INSERT INTO community_category (name, is_allow_member_community, sort_order, lft, rgt, level)';
      $oldParentSQL = '(SELECT name, 1, sort_order, 1, '.((count($childs) + 1) * 2).', 0 FROM c_commu_category_parent WHERE c_commu_category_parent_id = ?)';

      $this->conn->execute($newParentSQL.' '.$oldParentSQL, array($parent['c_commu_category_parent_id']));

      $treeKey = $this->conn->lastInsertId();

      $this->conn->execute('UPDATE community_category SET tree_key = ? WHERE id = ?', array($treeKey, $treeKey));

      foreach ($childs as $i => $child)
      {
        $lft = ($i + 1) * 2;
        $rgt = $lft + 1;

        if ('2.12' == sfConfig::get('op_upgrade2_version'))
        {
          $isAllowMemberCommunityValue = 1;
        }
        else
        {
          $isAllowMemberCommunityValue = 'is_create_commu';
        }

        $newChildSQL = 'INSERT INTO community_category (name, is_allow_member_community, sort_order, lft, rgt, level, tree_key)';
        $oldChildSQL = '(SELECT name, '.$isAllowMemberCommunityValue.', sort_order, '.$lft.','.$rgt.', 1, '.$treeKey.' FROM c_commu_category WHERE c_commu_category_id = ?)';

        $this->conn->execute($newChildSQL.' '.$oldChildSQL, array($child['c_commu_category_id']));
        $categoryId = $this->conn->lastInsertId();

        $baseInsert = 'INSERT INTO community (id, name, file_id, community_category_id, created_at, updated_at)';
        $baseSelect = '(SELECT c_commu_id, name, '.$this->getSQLForFileId('image_filename').', '.$categoryId.', r_datetime, u_datetime FROM c_commu WHERE c_commu_category_id = ? AND c_commu_id';
        if ($duplicatedCommunityIds)
        {
          $uniqueSelect = $baseSelect.' NOT IN ('.$duplicatedReplacements.'))';
          $duplicateSelect = '(SELECT c_commu_id, c_commu_id, '.$this->getSQLForFileId('image_filename').', '.$categoryId.', r_datetime, u_datetime FROM c_commu WHERE c_commu_category_id = ? AND c_commu_id IN ('.$duplicatedReplacements.'))';

          $params = array_merge(array($child['c_commu_category_id']), $duplicatedCommunityIds);
          $this->conn->execute($baseInsert.' '.$uniqueSelect, $params);
          $this->conn->execute($baseInsert.' '.$duplicateSelect, $params);
        }
        else
        {
          $this->conn->execute($baseInsert.' '.$baseSelect.')', array($categoryId));
        }
      }
    }

    foreach ($duplicatedCommunityIds as $id)
    {
      $name = $this->conn->fetchOne('SELECT name FROM c_commu WHERE c_commu_id = ?', array($id));
      $this->updateToUniqueCommunityName($id, $name);
    }

    $this->conn->execute('INSERT INTO community_config (id, community_id, name, value, created_at, updated_at) (SELECT NULL, c_commu_id, ?, 1, NOW(), NOW() FROM c_commu WHERE is_regist_join = 1)', array('is_default'));
  }

  protected function updateToUniqueCommunityName($id, $baseName, $candidate = 1)
  {
    $name = $baseName;
    if (2 <= $candidate)
    {
      $name = sprintf('%s (%d)', $name, $candidate);
    }

    $isExists = (bool)$this->conn->fetchOne('SELECT id FROM community WHERE name = ?', array($name));
    if (!$isExists)
    {
      $this->conn->execute('UPDATE community SET name = ? WHERE id = ?', array($name, $id));

      return true;
    }

    $this->updateToUniqueCommunityName($id, $baseName, ($candidate + 1));
  }
}
