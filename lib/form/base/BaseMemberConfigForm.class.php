<?php

/**
 * MemberConfig form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseMemberConfigForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'member_id' => new sfWidgetFormPropelChoice(array('model' => 'Member', 'add_empty' => false)),
      'name'      => new sfWidgetFormInput(),
      'value'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'MemberConfig', 'column' => 'id', 'required' => false)),
      'member_id' => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id')),
      'name'      => new sfValidatorString(array('max_length' => 64)),
      'value'     => new sfValidatorString(array('required' => false)),
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
