<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * MemberConfig filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMemberConfigFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id' => new sfWidgetFormPropelChoice(array('model' => 'Member', 'add_empty' => true)),
      'name'      => new sfWidgetFormFilterInput(),
      'value'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'member_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'name'      => new sfValidatorPass(array('required' => false)),
      'value'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_config_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberConfig';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Text',
      'member_id' => 'ForeignKey',
      'name'      => 'Text',
      'value'     => 'Text',
    );
  }
}
