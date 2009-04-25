<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Blacklist filter form base class.
 *
 * @package    filters
 * @subpackage Blacklist *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseBlacklistFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'  => new sfWidgetFormFilterInput(),
      'memo' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'uid'  => new sfValidatorPass(array('required' => false)),
      'memo' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('blacklist_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Blacklist';
  }

  public function getFields()
  {
    return array(
      'id'   => 'Number',
      'uid'  => 'Text',
      'memo' => 'Text',
    );
  }
}