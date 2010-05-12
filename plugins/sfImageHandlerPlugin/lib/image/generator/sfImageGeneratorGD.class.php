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
    return Image_Transform::factory('GD');
  }

  protected function disableInterlace()
  {
    imageinterlace($this->transform->getHandle(), 0);
  }
}
