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

    if (isset($config['title_configurable']) && !$config['title_configurable'])
    {
      unset($this['title']);
    }

    $sample = isset($config['sample'][$culture]) ? $config['sample'][$culture] : null;
    if (is_array($sample) && count($sample) >= 2)
    {
      if (!$this->getDefault('title'))
      {
        $this->setDefault('title', $sample[0]);
      }
      if (!$this->getDefault('template'))
      {
        $this->setDefault('template', $sample[1]);
      }
    }
    else if (!$this->getDefault('template') && $sample)
    {
      $this->setDefault('template', $sample);
    }
  }
}
