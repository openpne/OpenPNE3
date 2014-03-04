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
      $uploadimage = sfConfig::get('op_resize_limit_size', 1024);
      $class = sfImageHandler::getStorageClassName();
      $this->setName(call_user_func(array($class, 'getFilenameToSave'), $this->getName()), $class);
      $storage = call_user_func(array($class, 'create'), $this, $class);
<<<<<<< HEAD
      $filesize = strlen($this->FileBin->bin);
      if ($filesize > $uploadimage)
      {
        $ratio = sqrt($uploadimage/$filesize)/1.5;
        $type = $this->getType();
        $image = imagecreatefromstring($this->FileBin->bin);
        $x = imagesx($image);
        $y = imagesy($image);
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
=======
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
>>>>>>> f20e9a37a32f26ceb8d17e7a05443ac584562b91
            break;
          default:
            break;
        }
<<<<<<< HEAD
        $ei = ob_get_contents();
        ob_end_clean();
        $this->getFileBin()->setBin($ei);
        imagedestroy($image);
        imagedestroy($resize);
=======
        ob_start();
        imagejpeg($image);
        $ei = ob_get_contents();
        ob_end_clean();
        $this->getFileBin()->setBin($ei);
>>>>>>> f20e9a37a32f26ceb8d17e7a05443ac584562b91
      }
      $storage->saveBinary($this->getFileBin());
    }
    $this->setFilesize(strlen($this->FileBin->bin));
<<<<<<< HEAD

=======
>>>>>>> f20e9a37a32f26ceb8d17e7a05443ac584562b91
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
