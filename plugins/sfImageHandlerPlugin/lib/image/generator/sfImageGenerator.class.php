<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2009 Kousuke Ebihara <ebihara@tejimaya.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageGenerator
 *
 * @package    sfImageHandlerPlugin
 * @subpackage image
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class sfImageGenerator
{
  const ERROR_NOT_ALLOWED_SIZE = 101;

  protected
    $quality     = 75,
    $width       = 0,
    $height      = 0,
    $format      = 'jpg',
    $allowedSize = null,
    $tmpfilename = null;

  public function __construct(array $options = array())
  {
    $this->initialize($options);
    $this->configure();
  }

  public function initialize($options)
  {
    $this->allowedSize = sfImageHandler::getAllowedSize();

    $options = array_merge(array('width' => $this->width, 'height' => $this->height), $options);
    $this->setImageSize($options['width'], $options['height']);

    if (isset($options['quality']))
    {
      $this->quality = $options['quality'];
    }

    if (isset($options['format']))
    {
      $this->format = $options['format'];
    }
  }

  public function configure()
  {
  }

  public function setImageSize($width, $height)
  {
    if (!$this->checkSizeAllowed($width, $height))
    {
      throw new RuntimeException('Requested image size is not allowed', sfImageGenerator::ERROR_NOT_ALLOWED_SIZE);
    }

    if (is_numeric($width))
    {
      $this->width = $width;
    }

    if (is_numeric($height))
    {
      $this->height = $height;
    }
  }

  abstract public function getBinary($type, $quality);

  abstract protected function doSave($outputFilename, $type, $quality);

  abstract protected function doResize($tmpfilename);

  public function getFormat()
  {
    return $this->format;
  }

  public function getAllowedSize()
  {
    return $this->allowedSize;
  }

  protected function checkSizeAllowed($w, $h)
  {
    // an empty string of width and height are allowed
    if ('' === $w && '' === $h)
    {
      return true;
    }

    return in_array($w.'x'.$h, $this->allowedSize);
  }

  public function output($outputFilename)
  {
    if (!is_dir(dirname($outputFilename)))
    {
      $currentUmask = umask(0000);
      if (false === @mkdir(dirname($outputFilename), 0777, true))
      {
        throw new sfRuntimeException('Failed to make cache directory.');
      }
      umask($currentUmask);
    }

    if ($this->doSave($outputFilename, $this->format, $this->quality))
    {
      return file_get_contents($outputFilename);
    }

    return false;
  }

  public function resize($binary, $format)
  {
    $this->tmpfilename = tempnam(sys_get_temp_dir(), 'OPIMG');
    file_put_contents($this->tmpfilename, $binary);

    if (!$this->checkSizeAllowed($this->width, $this->height))
    {
      $this->width = $this->height = '';
    }

    $this->doResize($this->tmpfilename);

    return array('f' => $this->format, 'w' => $this->width, 'h' => $this->height);
  }

  public function __destruct()
  {
    if ($this->tmpfilename)
    {
      @unlink($this->tmpfilename);
    }
  }
}
