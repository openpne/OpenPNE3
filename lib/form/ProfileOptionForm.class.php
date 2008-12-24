<?php

/**
 * ProfileOption form.
 *
 * @package    form
 * @subpackage profile_option
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ProfileOptionForm extends BaseProfileOptionForm
{
  public function configure()
  {
    $options = array();
    if ($this->object && $this->object->getId()) {
      $options['id_format'] = '%s' . $this->object->getId();
    }

    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'profile_id' => new sfWidgetFormInputHidden(),
      'sort_order' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'ProfileOption', 'column' => 'id', 'required' => false)),
      'profile_id'         => new sfValidatorPropelChoice(array('model' => 'Profile', 'column' => 'id', 'required' => true)),
      'sort_order' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_option[%s]');

    $this->embedI18n(array('ja_JP'));
  }
}
