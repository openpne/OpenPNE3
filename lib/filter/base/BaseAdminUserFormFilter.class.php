<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * AdminUser filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseAdminUserFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormFilterInput(),
      'password' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorPass(array('required' => false)),
      'password' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('admin_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AdminUser';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Text',
      'username' => 'Text',
      'password' => 'Text',
    );
  }
}
