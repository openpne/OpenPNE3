<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * MemberProfile filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMemberProfileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id'         => new sfWidgetFormPropelChoice(array('model' => 'Member', 'add_empty' => true)),
      'profile_id'        => new sfWidgetFormPropelChoice(array('model' => 'Profile', 'add_empty' => true)),
      'profile_option_id' => new sfWidgetFormPropelChoice(array('model' => 'ProfileOption', 'add_empty' => true)),
      'value'             => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'member_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'profile_id'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Profile', 'column' => 'id')),
      'profile_option_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'ProfileOption', 'column' => 'id')),
      'value'             => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberProfile';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Text',
      'member_id'         => 'ForeignKey',
      'profile_id'        => 'ForeignKey',
      'profile_option_id' => 'ForeignKey',
      'value'             => 'Text',
    );
  }
}
