<?php

/**
 * Member form.
 *
 * @package    form
 * @subpackage member
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class MemberForm extends BaseMemberForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'name' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'name' => new sfValidatorString(array('max_length' => 64)),
    ));

    $this->widgetSchema->setNameFormat('member[%s]');
  }

  protected function doSave($con = null)
  {
    if ($this->isNew()) {
      $this->object->setIsActive(false);
    }

    parent::doSave($con);
  }
}
