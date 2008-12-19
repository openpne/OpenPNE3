<?php

/**
 * Member filter form.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberFormFilter extends BaseMemberFormFilter
{
  public function configure()
  {
    $this->setWidgets(array(
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'       => new sfValidatorPass(),
    ));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->widgetSchema->setNameFormat('member[%s]');
  }
}
