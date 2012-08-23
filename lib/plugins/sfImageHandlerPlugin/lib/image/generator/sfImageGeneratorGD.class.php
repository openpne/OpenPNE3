<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2010 Kousuke Ebihara <ebihara@php.net>
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
class sfImageGeneratorGD extends sfImageGeneratorImageTransform
{
  protected function creaateTransform()
  {
    $transform = Image_Transform::factory('GD');
    
    return $transform;
  }

  protected function disableInterlace()
  {
    imageinterlace($this->transform->getHandle(), 0);
  }

  protected function configureImageHandle()
  {
    if (!imageistruecolor($this->transform->getHandle()))
    {
      $this->transform->setOption('scaleMethod', 'pixel');
    }
  }

  protected function doSave($outputFilename, $type, $quality)
  {
    $this->configureImageHandle();

    if (!$this->transform->resized)
    {
      $result = $this->transform->crop($this->transform->new_x, $this->transform->new_y);
      if (PEAR::isError($result))
      {
        throw new sfException($result->getMessage());
      }
    }

    $result = $this->transform->save($outputFilename, $type, $quality);
    if (PEAR::isError($result))
    {
      throw new sfException($result->getMessage());
    }

    return true;
  }
}
