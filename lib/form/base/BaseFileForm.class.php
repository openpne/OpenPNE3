<?php

/**
 * File form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseFileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInput(),
      'original_filename' => new sfWidgetFormTextarea(),
      'bin'               => new sfWidgetFormInput(),
      'type'              => new sfWidgetFormInput(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'File', 'column' => 'id', 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 64)),
      'original_filename' => new sfValidatorString(array('required' => false)),
      'bin'               => new sfValidatorPass(array('required' => false)),
      'type'              => new sfValidatorString(array('max_length' => 64)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
      'updated_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'File', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'File';
  }


}
