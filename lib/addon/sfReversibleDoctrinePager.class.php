<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfReversibleDoctrinePager class.
 *
 * @package    OpenPNE
 * @subpackage addon
 * @author     Rimpei Ogawa <owaga@tejimaya.com>
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfReversibleDoctrinePager extends sfDoctrinePager
{
  const
    DESC = 'DESC',
    ASC  = 'ASC';

  protected
    $results        = null,
    $sqlOrderColumn = 'id',
    $sqlOrder       = self::DESC,
    $listOrder      = self::ASC;

  public function getResults()
  {
    if (is_null($this->results))
    {
      $this->getQuery()->orderBy($this->sqlOrderColumn.' '.$this->sqlOrder);

      $this->results = parent::getResults();

      if ($this->sqlOrder !== $this->listOrder)
      {
        $obj = new Doctrine_Collection($this->results->getTable(), $this->results->getKeyColumn());
        $obj->fromArray(array_reverse($this->results->toArray(true)));
        $this->results = $obj;
      }
    }

    return $this->results;
  }

  public function getOlderPage()
  {
    return (self::ASC === $this->sqlOrder) ? $this->getPreviousPage() : $this->getNextPage();
  }

  public function getNewerPage()
  {
    return (self::ASC === $this->sqlOrder) ? $this->getNextPage() : $this->getPreviousPage();
  }

  public function hasOlderPage()
  {
    return (self::ASC === $this->sqlOrder) ? $this->hasPreviousPage() : $this->hasNextPage();
  }

  public function hasNewerPage()
  {
    return (self::ASC === $this->sqlOrder) ? $this->hasNextPage() : $this->hasPreviousPage();
  }

  public function hasPreviousPage()
  {
    return (1 < $this->getPage());
  }

  public function hasNextPage()
  {
    return ($this->getPage() < $this->getLastPage());
  }

  public function getFirstItem()
  {
    if (self::ASC === $this->listOrder)
    {
      return $this->getResults()->getFirst();
    }
    else
    {
      return $this->getResults()->getLast();
    }
  }

  public function getLastItem()
  {
    if (self::ASC === $this->listOrder)
    {
      return $this->getResults()->getLast();
    }
    else
    {
      return $this->getResults()->getFirst();
    }
  }

  public function setSqlOrderColumn($sqlOrderColumn)
  {
    $this->sqlOrderColumn = $sqlOrderColumn;
  }

  public function setSqlOrder($order)
  {
    $this->sqlOrder = self::normalizeOrder($order);
  }

  public function getSqlOrder()
  {
    return $this->sqlOrder;
  }

  public function setListOrder($order)
  {
    $this->listOrder = self::normalizeOrder($order);
  }

  public function getListOrder()
  {
    return $this->listOrder;
  }

  public function getFirstIndice()
  {
    return $this->getFirstItem()->getNumber();
  }

  public function getLastIndice()
  {
    return $this->getLastItem()->getNumber();
  }

  public static function normalizeOrder($order)
  {
    if (self::ASC === $order)
    {
      return self::ASC;
    }
    else
    {
      return self::DESC;
    }
  }
}
