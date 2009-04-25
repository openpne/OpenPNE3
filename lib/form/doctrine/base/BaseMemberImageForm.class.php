<?php

/**
 * MemberImage form base class.
 *
 * @package    form
 * @subpackage member_image
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMemberImageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'member_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => false)),
      'file_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'File', 'add_empty' => false)),
      'is_primary' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => 'MemberImage', 'column' => 'id', 'required' => false)),
      'member_id'  => new sfValidatorDoctrineChoice(array('model' => 'Member')),
      'file_id'    => new sfValidatorDoctrineChoice(array('model' => 'File')),
      'is_primary' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_image[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberImage';
  }

}