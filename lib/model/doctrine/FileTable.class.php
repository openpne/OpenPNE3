<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class FileTable extends Doctrine_Table
{
  protected $allowedPagerSizeList = array(
    20, 50, 100, 500,
  );

  public function getAllowedPagerSizeList()
  {
    return $this->allowedPagerSizeList;
  }

  public function getDefaultPagerSize()
  {
    return $this->allowedPagerSizeList[0];
  }

  public function getValidPagerSize($size)
  {
    if (in_array($size, $this->allowedPagerSizeList))
    {
      return $size;
    }

    return $this->getDefaultPagerSize();
  }

  public function retrieveByFilename($filename)
  {
    return $this->createQuery()
      ->where('name = ?', $filename)
      ->fetchOne();
  }

  public function getFilePager($page = 1, $size = 20)
  {
    $q = $this->getImageOrderdQuery()
      ->where('type NOT LIKE ?', 'image%');
    return $this->getPager($q, $page, $this->getValidPagerSize($size));
  }

  public function getImageFilePager($page = 1, $size = 20)
  {
    $q = $this->getImageOrderdQuery()
      ->where('type LIKE ?', 'image%');
    return $this->getPager($q, $page, $this->getValidPagerSize($size));
  }

  protected function getPager(Doctrine_Query $q, $page, $size)
  {
    $pager = new sfDoctrinePager('File', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  protected function getImageOrderdQuery()
  {
    return $this->createQuery()->orderBy('id DESC');
  }
}
