<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2009 Kousuke Ebihara <ebihara@tejimaya.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageGeneratorGD
 *
 * @package    sfImageHandlerPlugin
 * @subpackage image
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class sfImageGeneratorGD
{
  protected
    $transform = null,

    $quality     = 75,
    $width       = 0,
    $height      = 0,
    $format      = 'jpg',
    $allowedSize = array('76x76', '120x120', '180x180', '240x320', '600x600'),
    $outputImage = null;

  public function __construct(array $options = array())
  {
    $this->initialize($options);
    $this->configure();
  }

 /**
  * Initializes this generator.
  */
  public function initialize($options)
  {
    require_once 'Image/Transform.php';

    $this->transform = Image_Transform::factory('GD');

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

    $this->allowedSize = array_merge($this->allowedSize, sfConfig::get('sf_image_handler_allowed_size', array()));
  }

  public function configure()
  {
  }

 /**
  * Configures the output image size
  */
  public function setImageSize($width, $height)
  {
    if (is_numeric($width))
    {
      $this->width = $width;
    }

    if (is_numeric($height))
    {
      $this->height = $height;
    }
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

    if ($this->transform->save($outputFilename, $type, $this->quality))
    {
      return file_get_contents($outputFilename);
    }

    return false;
  }

 /**
  * Resizes an input image
  */
  public function resize($binary, $format)
  {
    $tmpfilename = tempnam(sys_get_temp_dir(), 'OPIMG');
    file_put_contents($tmpfilename, $binary);

    $result = $this->transform->load($tmpfilename);
    @unlink($tmpfilename);
    if (PEAR::isError($result))
    {
      throw new sfException($result->getMessage());
    }

    // for mobile phone
    if ('jpg' === $this->format)
    {
      imageinterlace($this->transform->getHandle(), 0);
    }

    if (!$this->checkSizeAllowed($this->width, $this->height))
    {
      $this->width = $this->height = '';
    }

    if ($this->width && $this->height)
    {
      $this->transform->fit($this->width, $this->height);
    }

    $this->outputImage = $this->transform->getHandle();

    return array('f' => $this->format, 'w' => $this->width, 'h' => $this->height);
  }

  public function getFormat()
  {
    return $this->format;
  }

  protected function checkSizeAllowed($w, $h)
  {
    return in_array($w.'x'.$h, $this->allowedSize);
  }
}
