<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * ProfileI18n filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseProfileI18nFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'caption' => new sfWidgetFormFilterInput(),
      'info'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'caption' => new sfValidatorPass(array('required' => false)),
      'info'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_i18n_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileI18n';
  }

  public function getFields()
  {
    return array(
      'caption' => 'Text',
      'info'    => 'Text',
      'id'      => 'ForeignKey',
      'culture' => 'Text',
    );
  }
}
