<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * SnsConfig filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseSnsConfigFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'  => new sfWidgetFormFilterInput(),
      'value' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'  => new sfValidatorPass(array('required' => false)),
      'value' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sns_config_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SnsConfig';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Text',
      'name'  => 'Text',
      'value' => 'Text',
    );
  }
}
