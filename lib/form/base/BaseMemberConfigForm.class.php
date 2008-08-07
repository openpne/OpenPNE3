<?php

/**
 * MemberConfig form base class.
 *
 * @package    form
 * @subpackage member_config
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseMemberConfigForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'member_id' => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => false)),
      'name'      => new sfWidgetFormInput(),
      'value'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'MemberConfig', 'column' => 'id', 'required' => false)),
      'member_id' => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id')),
      'name'      => new sfValidatorString(array('max_length' => 64)),
      'value'     => new sfValidatorString(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'MemberConfig', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('member_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberConfig';
  }


}
