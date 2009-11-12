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
}
