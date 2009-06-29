<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class BannerTable extends Doctrine_Table
{
  public function findByName($name)
  {
    return $this->createQuery()
      ->where('name = ?', $name)
      ->fetchOne();
  }

  public function retrieveTop()
  {
    return $this->createQuery()->orderBy('id')->fetchOne();
  }
}
