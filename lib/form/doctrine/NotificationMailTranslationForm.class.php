<?php

/**
 * NotificationMailTranslation form.
 *
 * @package    form
 * @subpackage NotificationMailTranslation
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class NotificationMailTranslationForm extends BaseNotificationMailTranslationForm
{
  public function configure()
  {
    unset($this['lang'], $this['id']);

    $this->setValidator('title', new sfValidatorString(array('required' => false)));
    $this->setValidator('template', new sfValidatorString(array('required' => false)));
  }

  public function updateDefaultsByConfig($config)
  {
    $culture = sfContext::getInstance()->getUser()->getCulture();

    if (!$this->getDefault('template') && isset($config['sample'][$culture]))
    {
      $this->setDefault('template', $config['sample'][$culture]);
    }
  }
}
