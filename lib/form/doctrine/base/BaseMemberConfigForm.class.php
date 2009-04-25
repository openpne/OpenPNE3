<?php

/**
 * MemberConfig form base class.
 *
 * @package    form
 * @subpackage member_config
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMemberConfigForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'member_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => false)),
      'name'      => new sfWidgetFormInput(),
      'value'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorDoctrineChoice(array('model' => 'MemberConfig', 'column' => 'id', 'required' => false)),
      'member_id' => new sfValidatorDoctrineChoice(array('model' => 'Member')),
      'name'      => new sfValidatorString(array('max_length' => 64)),
      'value'     => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberConfig';
  }

}