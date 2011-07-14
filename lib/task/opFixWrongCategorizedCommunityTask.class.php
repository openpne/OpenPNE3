<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opFixWrongCategorizedCommunityTask
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opFixWrongCategorizedCommunityTask extends sfDoctrineBaseTask
{
  protected $dbManager;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace        = 'openpne';
    $this->name             = 'fix-wrong-categorized-community';
    $this->briefDescription = 'Fixes wrong categorized communities by upgrading from OpenPNE 2';
    $this->detailedDescription = <<<EOF
The [openpne:fix-wrong-categorized-community|INFO] task fixes wrong categorized communities by upgrading from OpenPNE 2.
Call it with:

  [./symfony openpne:fix-wrong-categorized-community|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->openDatabaseConnection();

    if (!$this->askToBegin())
    {
      $this->logSection('fix-wrong-categorized-community', 'task aborted');

      return;
    }

    if (!$this->hasOpenPNE2CommunityTable())
    {
      throw new RuntimeException('This task needs some OpenPNE 2 tables (c_commu, c_commu_category and c_commu_category_parent) in your master DB.'.PHP_EOL.'(このタスクの実行には OpenPNE 2 のテーブル (c_commu, c_commu_category, c_commu_category_parent) が必要です。)');
    }

    $this->changeCollectionForConvert();
    $map = $this->createCategoryMappingTable();

    list($min, $max) = $this->detectTargetRecords();
    if ($this->askToRecoverWrongCategorized($min, $max))
    {
      foreach ($map as $old => $new)
      {
        if ($new)
        {
          $list = $this->detectLackedCommunityIdsByCategoryId($old, $max);
          $this->createNewCommunityFromOpenPNE2($list);

          $result = $this->moveCommunities($old, $new);
          $this->log(sprintf('OpenPNE 2 category=%d => OpenPNE 3 category=%d : %d communities are moved', $old, $new, $result));
        }
      }
    }
    else
    {
      $messages = array(
        'Please add some WHERE clause which you need to following SQL and execute it.',
        '以下の SQL に要件にあった WHERE 節を追加し、実行してください。',
        '',
      );

      foreach ($map as $old => $new)
      {
        if ($new)
        {
          $idList = $this->detectLackedCommunityIdsByCategoryId($old, $max);
          if ($idList)
          {
            $idListString = implode(',', $idList);
            $messages[] = $this->generateSQLToSalvageLackedCommunity($idListString);
          }

          $messages[] = $this->createMoveCommunityQuery($old, $new);
        }
      }

      $this->logBlock($messages, 'INFO');
    }

    $this->logSection('fix-wrong-categorized-community', 'task finished');
  }

  protected function askToBegin()
  {
    $messages = array(
      'This task fixes some communities which belongs to wrong category by upgrading from OpenPNE2.',
      '(このタスクは、 OpenPNE 2 からのアップグレードによって誤ったカテゴリと紐づいてしまったコミュニティを修復します)',
      'If you haven\'t upgraded from OpenPNE 2, or you want to repair them by your hand, cancel this task.',
      '(OpenPNE 2 からのアップグレードを実施していなかったり、手動での修復を望む場合、このタスクを中断してください)',
      '',
      'Do you continue this task? [Y/n]',
      '(タスクの実行を続行しますか？ [Y/n])',
    );

    return $this->askConfirmation($messages);
  }

  protected function askToRecoverWrongCategorized($min, $max)
  {
    $messages = array(
      sprintf('Auto-detected communities which OpenPNE 3 recognized to fix are ID:"%d" to ID:"%d".', $min, $max),
      sprintf('(OpenPNE 3 が修正するべきだと自動認識したコミュニティは ID:"%d" から ID:"%d" です)', $min, $max),
      '',
      'This task tries to fix these communities automatically by the following steps:',
      '(このタスクは以下のステップによってコミュニティを自動修復しようとします)',
      '    1. Salvage a missing community from OpenPNE 2 data. "Missing community" has data of community member, but doesn\'t have data of itself.',
      '      (欠損したコミュニティを OpenPNE 2 のデータから復旧します。「欠損したコミュニティ」には、メンバーのデータは存在するものの、',
      '       コミュニティ自体のデータが存在していません)',
      '    2. Re-categorize all communities which are imported from OpenPNE 2',
      '      (OpenPNE 2 から存在していたすべてのコミュニティのカテゴリ分けをもう一度おこないます)',
      '',
      'Do you want to fix these communities automatically? [Y/n]',
      '(これらのコミュニティを自動修復しますか？) [Y/n]',
      '',
      '# NOTE: If you want to fix communities manually, please input "n" here.',
      '    If you have already re-categorized community or have changed names of community category for repairing wrong-categorized community,',
      '    this task may try to "fix" these wrong categorization by bad guessing. In this case, you should select manual detection or give up ',
      '    fix them by using this task.',
      '※注意: 手動でコミュニティを修復したい場合、ここでは "n" を選択してください。',
      '    誤ってカテゴリ分けされたコミュニティを修復する目的で、既にコミュニティカテゴリの紐付け直しやカテゴリ名の変更をおこなっている場合、',
      '    このタスクは誤った推測のもとで誤ったカテゴリ分けの修復をおこなおうとします。',
      '    このような場合は手動での修復を選ぶか、もしくはこのタスクによる修復をあきらめてください。',
    );

    return $this->askConfirmation($messages);
  }

  protected function openDatabaseConnection()
  {
    $this->dbManager = new sfDatabaseManager($this->configuration);
  }

  protected function createMoveCommunityQuery($oldCategoryId, $newCategoryId)
  {
    return sprintf('UPDATE community SET community_category_id = %d WHERE id IN (SELECT c_commu_id FROM c_commu WHERE c_commu_category_id = %d)', $newCategoryId, $oldCategoryId);
  }

  protected function detectLackedCommunityIdsByCategoryId($oldCategoryId, $oldMaxId)
  {
    $conn = opDoctrineQuery::getMasterConnectionDirect();

    return $conn->fetchColumn('SELECT c_commu_id FROM c_commu WHERE c_commu_category_id = ? AND c_commu_id NOT IN (SELECT id FROM community WHERE id <= ?) AND c_commu_id IN (SELECT community_id FROM community_member WHERE community_id = c_commu_id)', array($oldCategoryId, $oldMaxId));
  }

  protected function createNewCommunityFromOpenPNE2($idList)
  {
    if (!$idList)
    {
      return null;
    }

    $conn = opDoctrineQuery::getMasterConnectionDirect();
    $idListString = implode(',', $idList);

    $conn->exec($this->generateSQLToSalvageLackedCommunity($idListString));
    $this->log('Created new community from OpenPNE2 : '.$idListString);
  }

  protected function generateSQLToSalvageLackedCommunity($idListString)
  {
    // sorry for this duplicated code (original is in "opUpgradeFrom2ImportCommunityCategoryStrategy")
    $file = '(SELECT id FROM file WHERE name = image_filename LIMIT 1)';
    $sql = 'INSERT IGNORE INTO community (id, name, file_id, community_category_id, created_at, updated_at) (SELECT c_commu_id, name, '.$file.', NULL, r_datetime, u_datetime FROM c_commu WHERE c_commu_id IN ('.$idListString.'))';

    return $sql;
  }

  protected function moveCommunities($oldCategoryId, $newCategoryId)
  {
    $conn = opDoctrineQuery::getMasterConnectionDirect();
    $result = $conn->exec($this->createMoveCommunityQuery($oldCategoryId, $newCategoryId));

    return $result;
  }

  protected function createCategoryMappingTable()
  {
    $parentTable = array();
    $categoryTable = array();

    $conn = opDoctrineQuery::getMasterConnectionDirect();

    $oldParents = $conn->fetchAll('SELECT * FROM c_commu_category_parent');
    foreach ($oldParents as $oldParent)
    {
      $newParentId = $conn->fetchOne('SELECT id FROM community_category WHERE name = ? AND lft = ?', array($oldParent['name'], 1));
      $parentTable[$oldParent['c_commu_category_parent_id']] = $newParentId;
    }

    $oldCategories = $conn->fetchAll('SELECT * FROM c_commu_category');
    foreach ($oldCategories as $oldCategory)
    {
      $parentId = (int)$parentTable[$oldCategory['c_commu_category_parent_id']];
      $newCategoryId = ($parentId) ? $conn->fetchOne('SELECT id FROM community_category WHERE name = ? AND tree_key = ? AND level = ?', array($oldCategory['name'], $parentId, 1)) : false;
      $categoryTable[$oldCategory['c_commu_category_id']] = $newCategoryId;
    }

    return $categoryTable;
  }

  protected function hasOpenPNE2CommunityTable()
  {
    $conn = opDoctrineQuery::getMasterConnectionDirect();

    $tables = array(
      'c_commu_category_parent', 'c_commu_category', 'c_commu',
    );

    foreach ($tables as $table)
    {
      if (!$conn->import->tableExists($table))
      {
        return false;
      }
    }

    return true;
  }

  protected function changeCollectionForConvert()
  {
    $conn = opDoctrineQuery::getMasterConnectionDirect();
    $conn->execute('ALTER TABLE c_commu CHANGE image_filename image_filename text CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    $conn->execute('ALTER TABLE c_commu CHANGE name name text CHARACTER SET utf8 COLLATE utf8_unicode_ci');
  }

  protected function detectTargetRecords()
  {
    $conn = opDoctrineQuery::getMasterConnectionDirect();

    $min = $conn->fetchOne('SELECT MIN(c_commu_id) FROM c_commu');
    $max = $conn->fetchOne('SELECT MAX(c_commu_id) FROM c_commu');

    return array($min, $max);
  }
}
