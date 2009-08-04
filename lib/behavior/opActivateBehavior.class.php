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
  protected $_options = array(
    'name'    => 'is_active',
    'default' => false,
  );

  protected static $enabled = true;

  public function setTableDefinition()
  {
    $this->hasColumn($this->_options['name'], 'boolean', 1, array('default' => $this->_options['default'], 'notnull' => true));
    $this->index($this->_options['name'].'_INDEX', array(
      'fields' => array($this->_options['name']),
    ));

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
