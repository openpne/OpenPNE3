<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opNonCountQueryPager
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opNonCountQueryPager extends sfDoctrinePager
{
  protected $results = null;

  protected function executeQueryAndFetchCount(Doctrine_Connection $conn = null)
  {
    if (!$conn)
    {
      $conn = opDoctrineQuery::getMasterConnectionDirect();
    }

    $p = $this->getQuery();
    $p->specifyConnection($conn);

    $conn->beginTransaction();

    $this->results = $p->setIsFoundRows(true)
      ->execute(array());

    $count = $conn->fetchOne('SELECT FOUND_ROWS()');
    $this->setNbResults($count);

    $conn->commit();
  }

  public function init(Doctrine_Connection $conn = null)
  {
    $p = $this->getQuery();
    if (!($p->getConnection() instanceof Doctrine_Connection_Mysql))
    {
      return parent::init();
    }

    $p->offset(0);
    $p->limit(0);

    if ($this->getPage() == 0 || $this->getMaxPerPage() == 0)
    {
      $this->setLastPage(0);
    }
    else
    {
      $offset = ($this->getPage() - 1) * $this->getMaxPerPage();

      $p->offset($offset);
      $p->limit($this->getMaxPerPage());
    }

    $this->executeQueryAndFetchCount($conn);

    if (0 == $this->getNbResults())
    {
      $this->setLastPage(0);
    }
    else
    {
      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
    }
  }

  public function getResults($hydrationMode = Doctrine::HYDRATE_RECORD)
  {
    if (!($this->getQuery()->getConnection() instanceof Doctrine_Connection_Mysql))
    {
      return parent::getResults($hydrationMode);
    }

    return $this->results;
  }
}
