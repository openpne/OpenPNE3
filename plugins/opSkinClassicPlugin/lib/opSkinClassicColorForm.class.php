<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opSkinClassicColorForm extends sfForm
{
  public function configure()
  {
    foreach (opSkinClassicConfig::getAllowdColors() as $color)
    {
      $this->setWidget($color, new sfWidgetFormInputText());
      $this->widgetSchema->setLabel($color, sfInflector::humanize($color));
      $this->setValidator($color, new sfValidatorCallback(array('callback' => array('opSkinClassicColorForm', 'validateHex'))));
      $this->setDefault($color, opSkinClassicConfig::get($color));
    }

    $this->widgetSchema->setNameFormat('color[%s]');
  }

  static public function validateHex($validator, $value, $arguments)
  {
    if (!in_array(strlen($value), array(3, 6)))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    $zfValidator = new Zend_Validate_Hex();
    if (!$zfValidator->isValid($value))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return $value;
  }

  public function save()
  {
    foreach ($this->getValues() as $k => $v)
    {
      opSkinClassicConfig::set($k, $v);
    }

    opToolkit::clearCache();
  }
}
