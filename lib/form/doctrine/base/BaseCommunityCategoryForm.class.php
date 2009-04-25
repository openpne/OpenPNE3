<?php

/**
 * CommunityCategory form base class.
 *
 * @package    form
 * @subpackage community_category
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCommunityCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'name'                      => new sfWidgetFormInput(),
      'is_allow_member_community' => new sfWidgetFormInputCheckbox(),
      'tree_key'                  => new sfWidgetFormInput(),
      'sort_order'                => new sfWidgetFormInput(),
      'lft'                       => new sfWidgetFormInput(),
      'rgt'                       => new sfWidgetFormInput(),
      'level'                     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => 'CommunityCategory', 'column' => 'id', 'required' => false)),
      'name'                      => new sfValidatorString(array('max_length' => 64)),
      'is_allow_member_community' => new sfValidatorBoolean(),
      'tree_key'                  => new sfValidatorInteger(array('required' => false)),
      'sort_order'                => new sfValidatorInteger(array('required' => false)),
      'lft'                       => new sfValidatorInteger(array('required' => false)),
      'rgt'                       => new sfValidatorInteger(array('required' => false)),
      'level'                     => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('community_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CommunityCategory';
  }

}