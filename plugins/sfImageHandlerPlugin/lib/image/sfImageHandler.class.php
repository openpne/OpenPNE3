<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2009 Kousuke Ebihara <ebihara@tejimaya.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageHandler
 *
 * @package    sfImageHandlerPlugin
 * @subpackage image
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfImageHandler
{
  protected
    $generator = null,
    $storage   = null,
    $options   = array();

  protected static
    $allowedSize = array(),
    $allowedFormat = array('png', 'gif', 'jpg');

  public function __construct(array $options = array())
  {
    $this->initialize($options);
    $this->configure();
  }

  public function configure()
  {
  }

  public static function getAllowedFormat()
  {
    return self::$allowedFormat;
  }

  public static function getAllowedSize()
  {
    if (self::$allowedSize)
    {
      return self::$allowedSize;
    }

    self::$allowedSize = array_merge(
      array('76x76', '120x120', '180x180', '240x320', '600x600'),
      sfConfig::get('sf_image_handler_allowed_size', array())
    );

    return self::$allowedSize;
  }

  public function initialize($options)
  {
    if (isset($options['filename']))
    {
      $class = self::getStorageClassName();
      $this->storage = call_user_func(array($class, 'find'), $options['filename'], $class);
    }

    if (!sfConfig::has('op_image_generator_name'))
    {
      $isMagick = sfConfig::get('op_use_imagemagick', 0);

      if ((2 == $isMagick) || (1 == $isMagick && 'gif' === $options['format']))
      {
        sfConfig::set('op_image_generator_name', 'IM');
      }
      else
      {
        sfConfig::set('op_image_generator_name', 'GD');
      }
    }

    $className = 'sfImageGenerator'.sfConfig::get('op_image_generator_name');
    if (!class_exists($className))
    {
      throw new RuntimeException(sprintf('The specified image handler, %s is not found', $className));
    }

    $this->generator = new $className($options);
    $this->options = $options;
  }

  public function createImage()
  {
    $contents = $this->storage->getBinary();

    $info = $this->generator->resize($contents, $this->storage->getFormat());

    $filename = self::getPathToFileCache($info['f'], $info['w'], $info['h'], $this->options['filename']);

    return $this->generator->output($filename);
  }

  public function getGenerator()
  {
    return $this->generator;
  }

  public function getStorage()
  {
    return $this->storage;
  }

  public function isValidSource()
  {
    return (bool)$this->storage;
  }

  public function getContentType()
  {
    $format = $this->generator->getFormat();
    if ($format === 'jpg')
    {
      return 'image/jpeg';
    }

    return 'image/'.$format;
  }

  static public function getStorageClassName()
  {
    return 'sfImageStorage'.sfConfig::get('op_image_storage', 'Default');
  }

  static public function getPathToFileCache($format, $width, $height, $filename)
  {
    return sprintf('%s/cache/img/%s/w%s_h%s/%s.%2$s', sfConfig::get('sf_web_dir'), $format, $width, $height, $filename);
  }

  static public function clearFileCache($filename)
  {
    $sizes = array_merge(self::getAllowedSize(), array('raw'));
    $formats = self::getAllowedFormat();

    $filesystem = new sfFilesystem();

    foreach ($sizes as $size)
    {
      if ('raw' !== $size)
      {
        $s = explode('x', $size);
        $width = $s[0];
        $height = $s[1];
      }
      else
      {
        $width = $height = '';
      }

      foreach ($formats as $format)
      {
        $path = self::getPathToFileCache($format, $width, $height, $filename);
        if (is_file($path))
        {
          @$filesystem->remove($path);
        }
      }
    }
  }
}
