<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing sql.
 *
 * The specified sql is parsed as PHP script. Items in the "params" option are usable
 * as assigned variables from the sql (PHP script).
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeSQLImportStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $path = $this->options['dir'].DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.$this->options['name'].'.sql';
    if (!file_exists($path))
    {
      throw new RuntimeException('The specified sql doesn\'t exist.');
    }

    $db = $this->getDatabaseManager()->getDatabase('doctrine');
    $this->conn = $db->getDoctrineConnection();

    $this->conn->beginTransaction();
    $this->conn->execute('SET FOREIGN_KEY_CHECKS = 0');  // for mysql

    $sql = opToolkit::unifyEOLCharacter($this->parseSql($path));
    $queries = explode("\n", $sql);

    try
    {
      $this->executeQueries($queries);
      $this->conn->commit();
    }
    catch (Exception $e)
    {
      $this->conn->rollback();
      $this->conn->execute('SET FOREIGN_KEY_CHECKS = 0');  // for mysql

      throw $e;
    }

    $this->conn->execute('SET FOREIGN_KEY_CHECKS = 0');  // for mysql
  }

  protected function executeQueries($queries)
  {
    foreach ($queries as $query)
    {
      $query = trim($query);
      if (!$query)
      {
        continue;
      }

      $this->conn->execute($query);
    }
  }

  protected function parseSql($file)
  {
    // The "params" option is settled, its items are imported as variables.
    if (!empty($this->options['params']))
    {
      extract($this->options['params']);
    }

    ob_start();
    include $file;
    $result = ob_get_clean();

    return $result;
  }

  protected function getSQLForFileId($name)
  {
    return '(SELECT id FROM file WHERE name = '.$name.' LIMIT 1)';
  }
}
