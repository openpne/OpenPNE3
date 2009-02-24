<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opDoctrineMigrationProcess extends Doctrine_Migration_Process
{
  protected $connection = null;

  public function __construct(Doctrine_Connection $connection)
  {
    $this->connection = $connection;
  }

  public function getConnection($tableName)
  {
    return $this->connection;
  }
}
