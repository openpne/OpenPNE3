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

  public function resizeImage($ratio, $image, $x, $y){
    $type = $this->getType();
    $resizex = $x*$ratio;
    $resizey = $y*$ratio;
    $resize = imagecreatetruecolor($resizex, $resizey);
    switch($type)
    {
      case 'image/jpeg':
        imagecopyresampled($resize, $image, 0, 0, 0, 0, $resizex, $resizey, $x, $y);
        ob_start();
        imagejpeg($resize);
        break;
      case 'image/gif':
        $alpha = imagecolortransparent($image);
        $trnprt_color = imagecolorsforindex($image, $alpha);
        $alpha = imagecolorallocate($resize, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
        imagefill($resize, 0, 0, $alpha);
        imagecolortransparent($resize, $alpha);
        imagecopyresampled($resize, $image, 0, 0, 0, 0, $resizex, $resizey, $x, $y);
        ob_start();
        imagegif($resize);
        break;
      case 'image/png':
        imagealphablending($resize, false);
        imagesavealpha($resize, true);
        imagecopyresampled($resize, $image, 0, 0, 0, 0, $resizex, $resizey, $x, $y);
        ob_start();
        imagepng($resize);
        break;
      default:
        break;
    }
    $ei = ob_get_contents();
    ob_end_clean();
    imagedestroy($image);
    imagedestroy($resize);

    return $ei;
  }

  public function measuredImage($uploadimage, $filesize){
    $ratio = sqrt($uploadimage/$filesize)/1.5;
    $image = imagecreatefromstring($this->FileBin->bin);
    $x = imagesx($image);
    $y = imagesy($image);
    $ei = $this->resizeImage($ratio, $image, $x, $y);

    return $ei;
  }

  public function contractedImage(){
      $uploadimage = sfConfig::get('op_resize_limit_size', 1024);
      $filesize = strlen($this->FileBin->bin);
      if ($filesize > $uploadimage)
      {
        $ei = $this->measuredImage($uploadimage, $filesize);
        $this->getFileBin()->setBin($ei);
      }
  }

  public function save(Doctrine_Connection $conn = null)
  {
    if ($this->isImage())
    {
      $class = sfImageHandler::getStorageClassName();
      $this->setName(call_user_func(array($class, 'getFilenameToSave'), $this->getName()), $class);
      $storage = call_user_func(array($class, 'create'), $this, $class);
      $this->contractedImage();
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
