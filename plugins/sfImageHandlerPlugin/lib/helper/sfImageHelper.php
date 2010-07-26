<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2009 Kousuke Ebihara <ebihara@tejimaya.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfImageHelper.
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

/**
 * Returns a image tag for sfImageHandler.
 *
 * @param string $filename
 * @param array  $options
 *
 * @return string  An image tag.
 */
function image_tag_sf_image($filename, $options = array())
{
  if (empty($options['alt']))
  {
    $options['alt'] = '';
  }

  if (!$filename)
  {
    if (isset($options['no_image']))
    {
      $filename = $options['no_image'];
      unset($options['no_image']);
    }
    else
    {
      $filename = 'no_image.gif';
    }
    return image_tag($filename, $options);
  }

  $filepath = sf_image_path($filename, $options);
  if (isset($options['size']))
  {
    unset($options['size']);
  }
  if (isset($options['no_image']))
  {
    unset($options['no_image']);
  }

  return image_tag($filepath, $options);
}

function sf_image_path($filename, $options = array(), $absolute = false)
{
  if (isset($options['f']))
  {
    $f = $options['f'];
    unset($options['f']);
  }
 elseif (is_callable(array($filename, 'getType')))
  {
    $f = str_replace('image/', '', $filename->getType());
  }
  else
  {
    $parts = explode('_', $filename);
    $f = array_pop($parts);
  }

  if ($f !== 'jpg' && $f !== 'png' && $f !== 'gif')
  {
    $f = 'jpg';
  }

  $size = null;
  if (isset($options['size']))
  {
    $size = $options['size'];
    unset($options['size']);
  }

  $class = sfImageHandler::getStorageClassName();

  return  call_user_func(array($class, 'getUrlToImage'), $filename, $size, $f, $absolute);
}
