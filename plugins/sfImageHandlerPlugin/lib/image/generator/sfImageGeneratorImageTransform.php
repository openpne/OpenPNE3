<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2010 Kousuke Ebihara <ebihara@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageGeneratorImageTransform
 *
 * @package    sfImageHandlerPlugin
 * @subpackage image
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class sfImageGeneratorImageTransform extends sfImageGenerator
{
  protected $transform = null;

  public function configure()
  {
    require_once 'Image/Transform.php';

    $this->transform = $this->creaateTransform();
  }

  protected function doSave($outputFilename, $type, $quality)
  {
    $result = $this->transform->save($outputFilename, $type, $quality);
    if (PEAR::isError($result))
    {
      throw new sfException($result->getMessage());
    }

    return true;
  }

  protected function doResize($tmpfilename)
  {
    $result = $this->transform->load($tmpfilename);
    if (PEAR::isError($result))
    {
      throw new sfException($result->getMessage());
    }

    // for mobile phone
    if ('jpg' === $this->format)
    {
      $this->disableInterlace();
    }

    if ($this->width && $this->height)
    {
      $this->transform->fit($this->width, $this->height);
    }
  }

  abstract protected function creaateTransform();

  abstract protected function disableInterlace();
}
