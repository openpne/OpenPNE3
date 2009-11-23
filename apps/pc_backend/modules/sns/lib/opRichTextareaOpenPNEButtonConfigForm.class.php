<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opRichTextareaOpenPNEButtonConfigForm
 *
 * @package    OpenPNE
 * @subpackage form 
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opRichTextareaOpenPNEButtonConfigForm extends sfForm
{
  public function configure()
  {
    //initialize
    new opWidgetFormRichTextareaOpenPNE();

    $allButtons = opWidgetFormRichTextareaOpenPNE::getAllButtons();
    $buttons    = opWidgetFormRichTextareaOpenPNE::getButtons();
    $diff       = array_diff(array_keys($allButtons), array_keys($buttons));
    foreach ($buttons as $key => $button)
    {
      $this->setWidget($key, new sfWidgetFormInputCheckbox());
      $this->setValidator($key, new sfValidatorBoolean());
      $this->setDefault($key, true);
    }
    foreach ($diff as $buttonName)
    {
      $this->setWidget($buttonName, new sfWidgetFormInputCheckbox());
      $this->setValidator($buttonName, new sfValidatorBoolean());
      $this->setDefault($buttonName, false);
    }
    $this->widgetSchema->setNameFormat('button[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    $results = array();
    foreach ($this->getValues() as $key => $value)
    {
      if (!$value)
      {
        $results[] = $key;
      }
    }
    Doctrine::getTable('SnsConfig')->set('richtextarea_unenable_buttons', serialize($results));
  }
}
