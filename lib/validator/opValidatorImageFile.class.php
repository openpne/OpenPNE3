<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorImageFile validates a date
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorImageFile extends sfValidatorFile
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setOption('mime_types', 'web_images');

    $maxFilesize = opConfig::get('image_max_filesize');
    switch (strtoupper(substr($maxFilesize, -1)))
    {
      case 'K' :
        $maxFilesize = (int)$maxFilesize * 1024;
        break;
      case 'M' :
        $maxFilesize = (int)$maxFilesize * 1024 * 1024;
        break;
    }
    
    $this->setOption('max_size', (int)$maxFilesize);
  }

  protected function doClean($value)
  {
    try
    {
      return parent::doClean($value);
    }
    catch (sfValidatorError $e)
    {
      if ($e->getCode() == 'max_size')
      {
        $arguments = $e->getArguments(true);
        throw new sfValidatorError($this, 'max_size', array('max_size' => opConfig::get('image_max_filesize'), 'size' => $arguments['size']));
      }
      throw $e;
    }
  }
}
