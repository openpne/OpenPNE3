<?php

class PluginInstallForm extends BaseForm
{
  public function configure()
  {
    $allPluginList = array_keys(sfContext::getInstance()->getConfiguration()->getAllPluginPaths());
    $this->setWidget('name', new sfWidgetFormInput());
    $this->setValidator('name', new sfValidatorString());
    
    $this->setWidget('version', new sfWidgetFormInput());
    $this->setValidator('version', new sfValidatorString(array('required' => false)));
  
    $this->getWidgetSchema()->setNameFormat($this->getName().'[%s]');
    $this->getWidgetSchema()->getFormFormatter()->setTranslationCatalogue('form_plugin_install');
    
    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'validatePlugin'))));
  }
  
  public function getName()
  {
    return 'plugin_install';
  }
  
  public function validatePlugin($validator, $values, $arguments = array())
  {
    if(isset($values['name']))
    {
      //whether the plugin is already installed
      if(in_array($values['name'], sfContext::getInstance()->getConfiguration()->getAllPlugins()))
      {
        throw new sfValidatorErrorSchema($validator, array('name' => new sfValidatorError($validator, 'The plugin is already installed.')));
      }
      
      $manager = new opPluginManager(sfContext::getInstance()->getEventDispatcher(), null, opPluginManager::getDefaultPluginChannelServerName());
      $manager->getEnvironment()->getRest()->setChannel(opPluginManager::getDefaultPluginChannelServerName());
      
      //whether the plugin exists in channel
      $info = $manager->retrieveChannelXml('p/'.strtolower($values['name']).'/info.xml');
      if(!$info)
      {
        throw new sfValidatorErrorSchema($validator, array('name' => new sfValidatorError($validator, 'Plugin not found.')));
      }
      
      //whether the plugin has stable release when no version specified
      if(empty($values['version']))
      {
        try
        {
          $manager->getPluginVersion($values['name'], 'stable');
        }
        catch(Exception $e)
        {
          throw new sfValidatorErrorSchema($validator, array('version' => new sfValidatorError($validator, 'No stable version available. Please specify a version.')));
        }
      }
    }
    return $values;
  }
}