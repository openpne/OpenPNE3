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

  public function __construct(array $options = array())
  {
    $this->initialize($options);
    $this->configure();
  }

  public function configure()
  {
  }

 /**
  * Initializes this handler.
  */
  public function initialize($options)
  {
    $this->generator = new sfImageGeneratorGD($options);
    $this->options = $options;

    if (isset($options['filename']))
    {
      $this->storage = Doctrine::getTable('File')->retrieveByFilename($options['filename']);
    }
  }

  public function createImage()
  {
    $contents = $this->storage->getFileBin()->getBin();

    $info = $this->generator->resize($contents, $this->storage->getImageFormat());

    $filename = sprintf('%s/cache/img/%s/w%s_h%s/%s.%2$s', sfConfig::get('sf_web_dir'), $info['f'], $info['w'], $info['h'], $this->options['filename']);
    return $this->generator->output($filename);
  }

  public function isValidSource()
  {
    if (!$this->storage)
    {
      return false;
    }

    if (!$this->storage->isImage())
    {
      return false;
    }

    return true;
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

}
