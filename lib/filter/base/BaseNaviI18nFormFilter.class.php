<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * NaviI18n filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseNaviI18nFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'caption' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'caption' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('navi_i18n_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'NaviI18n';
  }

  public function getFields()
  {
    return array(
      'caption' => 'Text',
      'id'      => 'ForeignKey',
      'culture' => 'Text',
    );
  }
}
