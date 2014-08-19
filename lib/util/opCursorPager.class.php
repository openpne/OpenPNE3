<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCursorPager provides functionality that allows you to do pagination based on cursor (max_id, since_id).
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opCursorPager implements IteratorAggregate
{
  protected
    $baseQuery = null,
    $maxPerPage = 20,
    $sinceId = null,
    $maxId = null,
    $objects = null;

  public function __construct($baseQuery)
  {
    $this->baseQuery = $baseQuery;
  }

  public function getMaxPerPage()
  {
    return $this->maxPerPage;
  }

  public function setMaxPerPage($maxPerPage)
  {
    $this->maxPerPage = $maxPerPage;
  }

  public function getSinceId()
  {
    return $this->sinceId;
  }

  public function setSinceId($sinceId)
  {
    $this->sinceId = $sinceId;
  }

  public function getMaxId()
  {
    return $this->maxId;
  }

  public function setMaxId($maxId)
  {
    $this->maxId = $maxId;
  }

  public function fetch()
  {
    $query = clone $this->baseQuery;
    $alias = $query->getRootAlias();

    if (null !== $this->sinceId)
    {
      $query->addWhere($alias.'.id > ?', $this->sinceId);
    }

    if (null !== $this->maxId)
    {
      $query->addWhere($alias.'.id <= ?', $this->maxId);
    }

    $query->limit($this->maxPerPage);
    $query->orderBy($alias.'.id');

    $this->objects = $query->execute();

    $query->free();
  }

  protected function checkFetched()
  {
    if (null === $this->objects)
    {
      throw new LogicException('You must call fetch() method.');
    }
  }

  public function getResults()
  {
    $this->checkFetched();

    return $this->objects->getData();
  }

  public function getIterator()
  {
    $this->checkFetched();

    return new ArrayIterator($this->getResults());
  }
}
