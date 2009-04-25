<?php

/**
 * FileBin form base class.
 *
 * @package    form
 * @subpackage file_bin
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseFileBinForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'file_id' => new sfWidgetFormInputHidden(),
      'bin'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'file_id' => new sfValidatorDoctrineChoice(array('model' => 'FileBin', 'column' => 'file_id', 'required' => false)),
      'bin'     => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('file_bin[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'FileBin';
  }

}