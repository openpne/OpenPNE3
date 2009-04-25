<?php

/**
 * Community form base class.
 *
 * @package    form
 * @subpackage community
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCommunityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'name'                  => new sfWidgetFormInput(),
      'file_id'               => new sfWidgetFormDoctrineChoice(array('model' => 'File', 'add_empty' => true)),
      'community_category_id' => new sfWidgetFormDoctrineChoice(array('model' => 'CommunityCategory', 'add_empty' => true)),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => 'Community', 'column' => 'id', 'required' => false)),
      'name'                  => new sfValidatorString(array('max_length' => 64)),
      'file_id'               => new sfValidatorDoctrineChoice(array('model' => 'File', 'required' => false)),
      'community_category_id' => new sfValidatorDoctrineChoice(array('model' => 'CommunityCategory', 'required' => false)),
      'created_at'            => new sfValidatorDateTime(array('required' => false)),
      'updated_at'            => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('community[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Community';
  }

}