<?php

/**
 * CommunityConfig form base class.
 *
 * @package    form
 * @subpackage community_config
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCommunityConfigForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'community_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Community', 'add_empty' => false)),
      'name'         => new sfWidgetFormInput(),
      'value'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => 'CommunityConfig', 'column' => 'id', 'required' => false)),
      'community_id' => new sfValidatorDoctrineChoice(array('model' => 'Community')),
      'name'         => new sfValidatorString(array('max_length' => 64)),
      'value'        => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('community_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CommunityConfig';
  }

}