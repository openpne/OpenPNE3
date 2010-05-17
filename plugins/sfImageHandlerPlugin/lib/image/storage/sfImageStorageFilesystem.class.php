<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2010 Kousuke Ebihara <ebihara@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageStorageFilesystem
 *
 * @package    sfImageHelper
 * @subpackage storage
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class sfImageStorageFilesystem extends sfImageStorageDefault
{
  public function getPathToImage($filename)
  {
    $baseDir = sfConfig::get('op_image_storage_filesystem_master_dir');
    if (!is_dir($baseDir))
    {
      throw new RuntimeException('"op_image_storage_filesystem_master_dir" must be configured in OpenPNE.yml');
    }
    $path = $baseDir.DIRECTORY_SEPARATOR.str_replace(DIRECTORY_SEPARATOR, '-', $filename);

    return $path;
  }

  public function saveBinary(FileBin $bin)
  {
    $this->file->setFileBin(null);

    return file_put_contents($this->getPathToImage($this->file->getName()), $bin->getBin());
  }

  public function deleteBinary()
  {
    $path = $this->getPathToImage($this->file->getName());
    if (is_file($path))
    {
      return @unlink($path);
    }

    return parent::deleteBinary();
  }

  public function getBinary()
  {
    $path = $this->getPathToImage($this->file->getName());
    if (is_file($path))
    {
      return file_get_contents($path);
    }

    return parent::getBinary();
  }
}
