<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class File extends BaseFile
{
  public function __toString()
  {
    return (string)$this->getName();
  }

  public function getImageFormat()
  {
    if (!$this->isImage())
    {
      return false;
    }

    $type = explode('/', $this->getType());
    $result = $type[1];

    if ($result === 'jpeg')
    {
      $result = 'jpg';
    }

    return $result;
  }

  public function isImage()
  {
    $type = $this->getType();
    if ($type === 'image/jpeg'
      || $type === 'image/gif'
      || $type === 'image/png')
    {
      return true;
    }

    return false;
  }

  public function setFromValidatedFile(sfValidatedFile $obj)
  {
    $this->setType($obj->getType());
    $this->setOriginalFilename($obj->getOriginalName());
    $this->setName(strtr($obj->generateFilename(), '.', '_'));

    $bin = new FileBin();
    $bin->setBin(file_get_contents($obj->getTempName()));
    $this->setFileBin($bin);
  }

  public function save(Doctrine_Connection $conn = null)
  {
    if ($this->isImage())
    {
      $class = sfImageHandler::getStorageClassName();
      $this->setName(call_user_func(array($class, 'getFilenameToSave'), $this->getName()), $class);
      $storage = call_user_func(array($class, 'create'), $this, $class);
      $type = $this->getType();
      if ($type === 'image/jpeg')
      {
        $cls = exif_read_data("data://image/jpeg;base64,".base64_encode($this->FileBin->bin));
        $exif = $cls['Orientation'];
        $image = imagecreatefromstring($this->FileBin->bin);
        switch ($exif)
        {
          case 3:
            $image = imagerotate($image, 180, 0);
            break;
          case 6:
            $imagewidth = imagesy($image);
            $imageheight = imagesx($image);
            $image = imagerotate($image, -90, 0);
            imagecopyresampled ($image, $image, 0, 0, (imagesx($image) - $imagewidth)/2, (imagesy($image) - $imageheight)/2, $imagewidth, $imageheight, $imagewidth, $imageheight);
            break;
          case 8:
            $imagewidth = imagesy($image);
            $imageheight = imagesx($image);
            $image = imagerotate($image, 90, 0);
            imagecopyresampled ($image, $image, 0, 0, (imagesx($image) - $imagewidth)/2, (imagesy($image) - $imageheight)/2, $imagewidth, $imageheight, $imagewidth, $imageheight);
            break;
          default:
            break;
        }
        ob_start();
        imagejpeg($image);
        $ei = ob_get_contents();
        ob_end_clean();
        $this->getFileBin()->setBin($ei);
      }
      $storage->saveBinary($this->getFileBin());
    }
    $this->setFilesize(strlen($this->FileBin->bin));
    return parent::save($conn);
  }

  public function delete(Doctrine_Connection $conn = null)
  {
    if ($this->isImage())
    {
      $class = sfImageHandler::getStorageClassName();
      $storage = call_user_func(array($class, 'create'), $this, $class);
      $storage->deleteBinary();
    }
    return parent::delete($conn);
  }
}
