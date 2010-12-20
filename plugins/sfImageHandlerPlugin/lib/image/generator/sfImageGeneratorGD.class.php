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
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfImageGeneratorGD
{
  const ERROR_NOT_ALLOWED_SIZE = 101;

  protected
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
    $this->allowedSize = array_merge($this->allowedSize, sfConfig::get('sf_image_handler_allowed_size', array()));

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

 /**
  * Configures the output image size
  */
  public function setImageSize($width, $height)
  {
    if (!$this->checkSizeAllowed($width, $height))
    {
      throw new RuntimeException('Requested image size is not allowed', sfImageGeneratorGD::ERROR_NOT_ALLOWED_SIZE);
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

  public function output($outputFilename)
  {
    $result = false;

    if (!is_dir(dirname($outputFilename)))
    {
      $currentUmask = umask(0000);
      if (false === @mkdir(dirname($outputFilename), 0777, true))
      {
        throw new sfRuntimeException('Failed to make cache directory.');
      }
      umask($currentUmask);
    }

    switch ($this->format)
    {
      case 'png':
        $result = imagepng($this->outputImage, $outputFilename);
        break;
      case 'gif':
        $result = imagegif($this->outputImage, $outputFilename);
        break;
      default:
        $result = imagejpeg($this->outputImage, $outputFilename, $this->quality);
    }

    if ($result)
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
    if (!$sourceImage = imagecreatefromstring($binary))
    {
      throw new sfException('Cannnot read an image binary.');
    }

    // for mobile phone
    if ('jpg' === $this->format)
    {
      imageinterlace($sourceImage, 0);
    }

    $info = array('f' => $this->format, 'w' => '', 'h' => '');

    if (!$this->width && !$this->height)
    {
      $this->outputImage = $sourceImage;
      return $info;
    }

    $source = array(imagesx($sourceImage), imagesy($sourceImage));
    $want = array($this->width, $this->height);
    $output = $this->calcOutputImageSize($source, $want);

    $info['w'] = $this->width;
    $info['h'] = $this->height;

    if (!$this->isNeedResize($source, $output))
    {
      $this->outputImage = $sourceImage;
      return $info;
    }

    $outputImage = imagecreatetruecolor($output[0], $output[1]);
    $this->setTransparent($outputImage, $sourceImage);
    imagecopyresampled($outputImage, $sourceImage, 0, 0, 0, 0, $output[0], $output[1], $source[0], $source[1]);

    $this->outputImage = $outputImage;

    return $info;
  }

  protected function isNeedConvertFormat($format)
  {
    return (bool)($this->format !== $format);
  }

  protected function calcOutputImageSize(array $source, array $want)
  {
    list($sw, $sh) = $source;
    list($ww, $wh) = $want;

    if (!$ww)
    {
      $ww = $sw;
    }
    if (!$wh)
    {
      $wh = $sh;
    }

    if (!$this->checkSizeAllowed($ww, $wh))
    {
      $ww = $this->width = $sw;
      $wh = $this->height = $sh;
    }

    $ow = $sw;
    $oh = $sh;

    if ($ww < $sw)
    {
      $ow  = $ww;
      $oh = $sh * $ww / $sw;
    }
    if ($wh < $oh && $wh < $sh)
    {
      $ow  = $sw * $wh / $sh;
      $oh = $wh;
    }
    if (!$ow)
    {
      $ow = 1;
    }
    if (!$oh)
    {
      $oh = 1;
    }

    return array($ow, $oh);
  }

  protected function isNeedResize(array $source, array $output)
  {
    list($sw, $sh) = $source;
    list($ow, $oh) = $output;

    if (!$ow && !$oh)
    {
      return false;
    }
    if (!$sw || !$sh)
    {
      return true;
    }
    if ($sw <= $ow && $sh <= $oh)
    {
      return false;
    }

    return true;
  }

  protected function setTransparent(&$outputImage, &$sourceImage)
  {
    if ($this->format !== 'gif' && $this->format !== 'png')
    {
      return null;
    }

    $sourceIndex = imagecolortransparent($sourceImage);

    // a transparent color exists in the source index
    if ($sourceIndex >= 0)
    {
      imagetruecolortopalette($outputImage, true, 256);

      // gets a transparent color from the source image
      $transparentColor = imagecolorsforindex($sourceImage, $transparentIndex);

      // sets a transparent color to the output image
      imagecolorset($sourceImage, 0, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);
      imagefill($outputImage, 0, 0, 0);
      imagecolortransparent($outputImage, 0);
    }
    elseif ($this->format === 'png')
    {
      imagealphablending($outputImage, false);
      imagesavealpha($outputImage, true);

      // sets a transparent color
      $color = imagecolorallocatealpha($outputImage, 0, 0, 0, 127);
      imagefill($outputImage, 0, 0, $color);
    }
  }

  public function getFormat()
  {
    return $this->format;
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
}
