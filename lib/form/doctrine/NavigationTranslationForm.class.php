<?php

/**
 * NavigationTranslation form.
 *
 * @package    form
 * @subpackage NavigationTranslation
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class NavigationTranslationForm extends BaseNavigationTranslationForm
{
  public function configure()
  {
    $this->setWidget('caption', new sfWidgetFormInput());
    $this->setValidator('caption', new opValidatorString(array('trim' => true, 'required' => true)));
  }
}
