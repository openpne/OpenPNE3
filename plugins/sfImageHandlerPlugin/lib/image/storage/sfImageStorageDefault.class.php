<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2010 Kousuke Ebihara <ebihara@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageStorageDefault
 *
 * @package    sfImageHelper
 * @subpackage storage
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class sfImageStorageDefault
{
  public $file = null;

  protected function __construct()
  {
  }

  static public function getUrlToImage($filename, $size, $format, $absolute = false)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Asset');

    $sizeDirName = 'w_h';
    if ($size)
    {
      list($width, $height) = explode('x', $size, 2);
      $sizeDirName = 'w'.$width.'_h'.$height;
    }

    $filepath = 'img/'.$format.'/'.$sizeDirName.'/'.$filename.'.'.$format;

    return _compute_public_path($filepath, 'cache', $format, $absolute);
  }

  static public function getFilenameToSave($filename)
  {
    return $filename;
  }

  static public function create(File $file)
  {
    $instance = new self();
    $instance->file = $file;

    return $instance;
  }

  static public function find($filename)
  {
    $instance = new self();

    $instance->file = Doctrine::getTable('File')->retrieveByFilename($filename);

    if (!$instance->file->isImage())
    {
      return null;
    }

    return $instance;
  }

  public function saveBinary(FileBin $bin)
  {
    return $bin->save();
  }

  public function deleteBinary()
  {
    return $this->file->getFileBin()->delete();
  }

  public function getBinary()
  {
    return $this->file->getFileBin()->getBin();
  }

  public function getFormat()
  {
    return $this->file->getImageFormat();
  }
}
