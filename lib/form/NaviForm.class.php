<?php

/**
 * Navi form.
 *
 * @package    form
 * @subpackage navi
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class NaviForm extends BaseNaviForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id' => new sfWidgetFormInputHidden(),
      'uri' => new sfWidgetFormInput(),
      'type' => new sfWidgetFormInputHidden(),
    ));

    $this->widgetSchema->setNameFormat('navi[%s]');
    $this->embedI18n(array('ja_JP'));
  }
}
