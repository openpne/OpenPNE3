<?php

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
  }
}
