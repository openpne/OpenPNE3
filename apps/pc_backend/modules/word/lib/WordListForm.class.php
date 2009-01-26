<?php

/**
 * Word Configs Form
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.net>
 */
class WordConfigsForm extends sfForm
{
  public function configure()
  {
    $wordConfigs = $this->getOption('word_configs');

    foreach ($wordConfigs as $wordConfig)
    {
      $option = array(
        'type' => 'text',
        'size' => 30,
        'value' => $wordConfig->getValue(),
      );
      $this->setWidget($wordConfig->getName(), new sfWidgetFormInput(array(), $option));
      $this->getWidget($wordConfig->getName())->setLabel($wordConfig->getCaption());

      $this->setValidator($wordConfig->getName(), new sfValidatorString(array('required' => true)));
    }

    $this->widgetSchema->setNameFormat('word_configs[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      return false;
    }

    $wordConfigs = $this->getOption('word_configs');

    foreach ($this->values as $key => $value)
    {
      foreach ($wordConfigs as &$wordConfig)
      {
        if ($wordConfig->getName() === $key)
        {
          $wordConfig->setValue($value);
          $wordConfig->save();
          break;
        }
      }
    }

    return true;
  }
}
