<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2010 Kousuke Ebihara <ebihara@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageGeneratorIM
 *
 * @package    sfImageHandlerPlugin
 * @subpackage image
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class sfImageGeneratorIM extends sfImageGeneratorImageTransform
{
  protected function creaateTransform()
  {
    if (!defined('IMAGE_TRANSFORM_IM_PATH') && sfConfig::has('op_imagemagick_path'))
    {
      // follow 2.x format (for BC reason)
      $path = dirname(sfConfig::get('op_imagemagick_path')).DIRECTORY_SEPARATOR;

      define('IMAGE_TRANSFORM_IM_PATH', $path);
    }

    $result = Image_Transform::factory('IM');
    if (PEAR::isError($result))
    {
      throw new RuntimeException($result->getMessage());
    }

    return $result;
  }

  protected function disableInterlace()
  {
    $this->transform->command['interlace'] = '-interlace none';
  }
}
