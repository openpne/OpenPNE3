<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CommunityMember filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCommunityMemberFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'community_id' => new sfWidgetFormPropelChoice(array('model' => 'Community', 'add_empty' => true)),
      'member_id'    => new sfWidgetFormPropelChoice(array('model' => 'Member', 'add_empty' => true)),
      'position'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'community_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Community', 'column' => 'id')),
      'member_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'position'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('community_member_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CommunityMember';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Text',
      'community_id' => 'ForeignKey',
      'member_id'    => 'ForeignKey',
      'position'     => 'Text',
    );
  }
}
