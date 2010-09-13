<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityCategoryTable extends Doctrine_Table
{
  //TODO: use findAll()
  public function retrieveAll()
  {
    return $this->createQuery()->execute();
  }

  //TODO: use getTree()->fetchRoots()
  public function retrieveAllRoots()
  {
    return $this->createQuery()
      ->where('lft = 1')
      ->execute();
  }

  public function retrieveAllChildren($sort = true)
  {
    return $this->getAllChildrenQuery($sort)->execute();
  }

  public function getAllChildrenQuery($sort = true)
  {
    $q = $this->createQuery()->where('lft > 1');
    if ($sort)
    {
      $q->orderBy('sort_order');
    }

    return $q;
  }
}
