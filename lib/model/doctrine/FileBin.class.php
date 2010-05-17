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
  protected $isFirstDelete = true;

  public function delete(Doctrine_Connection $conn = null)
  {
    if (!$this->getFile()->isImage() || $this->getBin())
    {
      return parent::delete($conn);
    }

    if (!$this->isFirstDelete)
    {
      $this->isFirstDelete = true;

      return parent::delete($conn);
    }

    $this->isFirstDelete = false;

    $class = sfImageHandler::getStorageClassName();
    $storage = call_user_func(array($class, 'create'), $this->getFile(), $class);

    return $storage->deleteBinary();
  }
}
