<?php

/**
 * Plugin Activation Form
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PluginActivationForm extends sfForm
{
  public function configure()
  {
    $plugins = $this->getOption('plugins');

    foreach ($plugins as $plugin)
    {
      $option = array(
        'default' => $plugin->getIsActive(),
        'value_attribute_value' => '1',
      );
      $this->setWidget($plugin->getName(), new sfWidgetFormInputCheckbox($option));
      $this->getWidget($plugin->getName())->setLabel($plugin->getName());

      $this->setValidator($plugin->getName(), new sfValidatorBoolean());
    }

    $type = $this->getOption('type');
    if ('auth' === $type)
    {
      $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateAuthPlugins'))));
    }
    elseif ('skin' === $type)
    {
      $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateSkinPlugins'))));
    }

    $this->widgetSchema->setNameFormat('plugin_activation[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      return false;
    }

    $plugins = $this->getOption('plugins');

    foreach ($this->values as $key => $value)
    {
      $plugins[$key]->setIsActive($value);
    }

    opToolkit::clearCache();

    return true;
  }

  public function validateAuthPlugins($validator, $values, $arguments)
  {
    $count = 0;

    $plugins = $this->getOption('plugins');
    foreach ($values as $key => $value)
    {
      if (isset($plugins[$key]) && $value)
      {
        $count++;
      }
    }

    if (!$count)
    {
      throw new sfValidatorError($validator, 'You must activate at least an authentication plugin.');
    }

    return $values;
  }

  public function validateSkinPlugins($validator, $values, $arguments)
  {
    $count = 0;

    $plugins = $this->getOption('plugins');
    foreach ($values as $key => $value)
    {
      if (isset($plugins[$key]) && $value)
      {
        $count++;
      }
    }

    if (1 !== $count)
    {
      throw new sfValidatorError($validator, 'You must activate only an skin plugin.');
    }

    return $values;
  }
}
