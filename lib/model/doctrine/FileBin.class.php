<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class FileBin extends BaseFileBin
{
  protected
    $isFirstSave = true,
    $isFirstDelete = true;

  public function save(Doctrine_Connection $conn = null)
  {
    if ($this->getFile()->isImage() || !$this->isFirstSave)
    {
      $this->isFirstSave = true;

      return parent::save($conn);
    }

    $this->isFirstSave = false;
    $this->setFile(Doctrine::getTable('File')->find($this->getFileId()));

    $class = sfImageHandler::getStorageClassName();
    $storage = call_user_func(array($class, 'create'), $this->getFile());

    return $storage->saveBinary($this);
  }

  public function delete(Doctrine_Connection $conn = null)
  {
    if ($this->getFile()->isImage() || !$this->isFirstDelete)
    {
      $this->isFirstDelete = true;

      return parent::save($conn);
    }

    $this->isFirstDelete = false;

    $class = sfImageHandler::getStorageClassName();
    $storage = call_user_func(array($class, 'create'), $this->getFile());

    return $storage->deleteBinary();
  }
}
