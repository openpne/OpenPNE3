<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Navi filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseNaviFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'type' => new sfWidgetFormFilterInput(),
      'uri'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type' => new sfValidatorPass(array('required' => false)),
      'uri'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('navi_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Navi';
  }

  public function getFields()
  {
    return array(
      'id'   => 'Text',
      'type' => 'Text',
      'uri'  => 'Text',
    );
  }
}
