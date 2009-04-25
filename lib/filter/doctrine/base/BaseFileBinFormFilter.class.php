<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * FileBin filter form base class.
 *
 * @package    filters
 * @subpackage FileBin *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseFileBinFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'bin'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'bin'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('file_bin_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'FileBin';
  }

  public function getFields()
  {
    return array(
      'file_id' => 'Number',
      'bin'     => 'Text',
    );
  }
}