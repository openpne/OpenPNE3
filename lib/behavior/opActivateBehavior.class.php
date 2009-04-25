<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opActivateBehavior extends Doctrine_Template
{
  protected static $enabled = true;

  public function setTableDefinition()
  {
    $this->hasColumn('is_active', 'boolean', 1, array('default' => false, 'notnull' => true));
    $this->addListener(new opActivateListener());
  }

  public static function enable()
  {
    self::$enabled = true;
  }

  public static function disable()
  {
    self::$enabled = false;
  }

  public static function getEnabled()
  {
    return self::$enabled;
  }
}
