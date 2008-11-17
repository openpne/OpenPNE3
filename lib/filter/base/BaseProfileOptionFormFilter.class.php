<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * ProfileOption filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseProfileOptionFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_id' => new sfWidgetFormPropelChoice(array('model' => 'Profile', 'add_empty' => true)),
      'sort_order' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'profile_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Profile', 'column' => 'id')),
      'sort_order' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_option_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileOption';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Text',
      'profile_id' => 'ForeignKey',
      'sort_order' => 'Text',
    );
  }
}
