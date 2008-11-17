<?php

/**
 * NaviI18n form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseNaviI18nForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'caption' => new sfWidgetFormTextarea(),
      'id'      => new sfWidgetFormInputHidden(),
      'culture' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'caption' => new sfValidatorString(array('required' => false)),
      'id'      => new sfValidatorPropelChoice(array('model' => 'Navi', 'column' => 'id', 'required' => false)),
      'culture' => new sfValidatorPropelChoice(array('model' => 'NaviI18n', 'column' => 'culture', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('navi_i18n[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'NaviI18n';
  }


}
