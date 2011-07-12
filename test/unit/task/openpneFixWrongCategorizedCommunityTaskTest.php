<?php

$fixture = 'fix_wrong_categorized_community';

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test();

class opInteractiveTaskTestHandlerFixCommunityCategories extends opInteractiveTaskTestHandler
{
  protected $fixture;

  public function __construct($t, $fixture)
  {
    parent::__construct($t);

    $this->fixture = $fixture;
  }

  public function beginTask()
  {
    $this->execute('openpne:fix-wrong-categorized-community --env=test')
      ->outputUntilLiteral('(タスクの実行を続行しますか？ [Y/n])');

    return $this;
  }

  public function outputUntilSelectSpecifyCommunity()
  {
    $this->outputUntilLiteral('このような場合は手動での修復を選ぶか、もしくはこのタスクによる修復をあきらめてください。');

    return $this;
  }

  public function importTestData()
  {
    $path = dirname(__FILE__).'/../../fixtures/'.$this->fixture.'/op2_test_data.sql';
    if (!is_file($path))
    {
      throw new LogicException('Specified sql file is unreadable.');
    }

    ob_start();
    include $path;
    $result = ob_get_clean();
    $queries = explode("\n", $result);

    $conn = opDoctrineQuery::getMasterConnectionDirect();

    $q = '';
    foreach ($queries as $query)
    {
      $query = trim($query);
      if (!$query)
      {
        continue;
      }

      $q .= $query;
      if (';' === $q[strlen($q) - 1])
      {
        $conn->execute($q);
        $q = '';
      }
    }

    return $this;
  }

  public function dropOpenPNE2Tables()
  {
    $tables = array(
      'c_commu_category_parent', 'c_commu_category', 'c_commu',
    );

    $conn = opDoctrineQuery::getMasterConnectionDirect();

    foreach ($tables as $table)
    {
      $conn->execute('DROP TABLE IF EXISTS '.$table);
    }

    return $this;
  }

  public function getCommunityIdByItsName($name)
  {
    $conn = opDoctrineQuery::getMasterConnectionDirect();
    $result = $conn->fetchOne('SELECT id FROM community WHERE name = ?', array($name));

    return $result;
  }

  public function getCommunityCategoryByCommunityId($id)
  {
    $conn = opDoctrineQuery::getMasterConnectionDirect();
    $result = $conn->fetchOne('SELECT community_category_id FROM community WHERE id = ?', array($id));

    return $result;
  }

  public function testMissingOpenPNE2TableError($comment = '')
  {
    $error = 'This task needs some OpenPNE 2 tables (c_commu, c_commu_category and c_commu_category_parent) in your master DB.'.'(このタスクの実行には OpenPNE 2 のテーブル (c_commu, c_commu_category, c_commu_category_parent) が必要です。)';

    $this->testError($error, $comment);

    return $this;
  }

  public function outputUntilShowExampleSql()
  {
    $this->outputUntilLiteral('以下の SQL に要件にあった WHERE 節を追加し、実行してください。', true);

    return $this;
  }

  public function __call($method, $args = array())
  {
    // stop error propagation from Lime test framework
    @call_user_func_array(array($this->t, $method), $args);

    return $this;
  }
}

new sfDatabaseManager($configuration);

$matches = array();

$handler = new opInteractiveTaskTestHandlerFixCommunityCategories($t, $fixture);
$handler
  ->beginTask()
  ->input('n')
  ->output()
  ->testOutput('>> fix-wrong-categorized-community task aborted', 'Aborted when the user input "n"')

  ->dropOpenPNE2Tables()
  ->beginTask()
  ->input('y')
  ->testMissingOpenPNE2TableError('Failed when missing OpenPNE 2 tables')

  ->importTestData()
  ->beginTask()
  ->input('y')
  ->outputUntilSelectSpecifyCommunity()
  ->input('n')
  ->outputUntilShowExampleSql()
  ->output()  // empty line
  ->output()
  ->testOutput('INSERT IGNORE INTO community (id, name, file_id, community_category_id, created_at, updated_at) (SELECT c_commu_id, name, (SELECT id FROM file WHERE name = image_filename LIMIT 1), NULL, r_datetime, u_datetime FROM c_commu WHERE c_commu_id IN (5))', 'SQL for salvaging CommunityE is displayed')
  ->outputUntil('/UPDATE community SET community_category_id/', $matches)
  ->testOutput('UPDATE community SET community_category_id = 3 WHERE id IN (SELECT c_commu_id FROM c_commu WHERE c_commu_category_id = 1)', 'Converting SQL for c_commu_category_id = 1 is displayed')
  ->output()
  ->testOutput('UPDATE community SET community_category_id = 5 WHERE id IN (SELECT c_commu_id FROM c_commu WHERE c_commu_category_id = 3)', 'Converting SQL for c_commu_category_id = 3 is displayed')
  ->output()
  ->testOutput('>> fix-wrong-categorized-community task finished', 'Task was finished successfully')

  ->importTestData()
  ->beginTask()
  ->input('y')
  ->outputUntil('/^Auto\-detected communities which OpenPNE 3 recognized to fix are ID\:"([0-9]+)" to ID\:"([0-9]+)".$/', $matches)
  ->is('1', $matches[1], 'Start of auto-detected community is ID:1')
  ->is('6', $matches[2], 'End of auto-detected community is ID:6')
  ->outputUntilSelectSpecifyCommunity()
  ->input('y')
  ->info('Start convertion')
  ->output()
  ->testOutput('Created new community from OpenPNE2 : 5', 'Created communityE from OpenPNE 2 data')
  ->output()
  ->testOutput('OpenPNE 2 category=1 => OpenPNE 3 category=3 : 2 communities are moved', 'Moves a community from c_commu_category=1 to community_category=3')
  ->output()
  ->testOutput('OpenPNE 2 category=3 => OpenPNE 3 category=5 : 1 communities are moved', 'Moves a community from c_commu_category=3 to community_category=5')
  ->output() // end of the task
  ->testOutput('>> fix-wrong-categorized-community task finished', 'Task was finished successfully')
  ->is($handler->getCommunityCategoryByCommunityId(1), 3, 'CommunityA belongs to category1')
  ->is($handler->getCommunityCategoryByCommunityId(2), 5, 'CommunityB belongs to category3')
  ->is($handler->getCommunityCategoryByCommunityId(3), 5, 'CommunityC belongs to category3')
  ->is($handler->getCommunityCategoryByCommunityId(4), 3, 'CommunityD belongs to category1')
  ->is($handler->getCommunityCategoryByCommunityId(5), 3, 'CommunityE belongs to category1')
  ->is($handler->getCommunityCategoryByCommunityId(6), 3, 'CommunityF belongs to category1')
  ->ok(!$handler->getCommunityIdByItsName('communityG'), 'Removed CommunityG are not salvaged')
;
