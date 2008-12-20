<?php

/**
 * Community filter form.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class CommunityFormFilter extends BaseCommunityFormFilter
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
    $this->widgetSchema->setNameFormat('community[%s]');
  }
}
