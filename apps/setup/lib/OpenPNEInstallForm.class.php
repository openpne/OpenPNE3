<?php

class OpenPNEInstallForm extends BaseForm
{
  public function configure()
  {
    //database settings
    $dbms = array('mysql'=>'mysql', 'postgres'=>'postgres(not supported)', 'sqlite'=>'sqlite(not supported)');
    $this->setWidget('dbms', new sfWidgetFormChoice(array('choices'=>$dbms)));
    $this->setValidator('dbms', new sfValidatorChoice(array('choices'=>array_keys($dbms))));
    
    $this->setWidget('dbhost', new sfWidgetFormInput(array('default'=>'127.0.0.1')));
    $this->setValidator('dbhost', new sfValidatorString());
    
    $this->setWidget('dbuser', new sfWidgetFormInput(array('default'=>'openpne')));
    $this->setValidator('dbuser', new sfValidatorString());
    
    $this->setWidget('dbpass', new sfWidgetFormInput(array()));
    $this->setValidator('dbpass', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('dbname', new sfWidgetFormInput(array('default'=>'openpne')));
    $this->setValidator('dbname', new sfValidatorString());

    $this->setWidget('dbport', new sfWidgetFormInput());
    $this->setValidator('dbport', new sfValidatorString(array('required' => false)));

    $this->setWidget('dbsock', new sfWidgetFormInput());
    $this->setValidator('dbsock', new sfValidatorString(array('required' => false)));
    
    //validate database settings
    $this->mergePostValidator(new sfValidatorCallback(array('callback'=>array($this, 'validateConnection'))));
    
    //first administrator settings
    $this->setWidget('first_admin_username', new sfWidgetFormInput(array('default'=>'admin')));
    $this->setValidator('first_admin_username', new sfValidatorString());
    
    $this->setWidget('first_admin_password', new sfWidgetFormInput(array('default'=>'password')));
    $this->setValidator('first_admin_password', new sfValidatorString());
    
    //first user settings
    $this->setWidget('first_user_email', new sfWidgetFormInput(array('default'=>'sns@example.com')));
    $this->setValidator('first_user_email', new sfValidatorEmail());
    $this->setWidget('first_user_password', new sfWidgetFormInput(array('default'=>'password')));
    $this->setValidator('first_user_password', new sfValidatorString());
    
    //plugins
    $plugins = $this->getAllPluginList();
    $this->setWidget('plugins', new sfWidgetFormChoice(array('expanded'=>true, 'multiple'=>true, 'choices'=>$plugins, 'default'=>array_keys($plugins))));
    $this->setValidator('plugins', new sfValidatorChoice(array('multiple'=>true, 'choices'=>array_keys($plugins))));
    
    
    //i18n & name format
    $this->getWidgetSchema()->setNameFormat($this->getName().'[%s]');
    $this->getWidgetSchema()->getFormFormatter()->setTranslationCatalogue('form_install');
  }
  
  public function getName()
  {
    return 'install';
  }
  
  public function validateConnection($validator, $values, $arguments = array())
  {
    //PENDING: validate connection here
    return $values;
  }
  
  //plugins.yml作成時にも利用するためpublicで
  public function getAllPluginList()
  {
    require sfConfig::get('sf_data_dir').'/version.php';
    $url = opPluginManager::getPluginListBaseUrl().OPENPNE_VERSION.'.yml';
    $list = sfYaml::load(file_get_contents($url));
    
    $plugins = array();
    if(is_array($list))
    {
      foreach($list as $name => $data)
      {
        $plugins[$name] = $name;
      }
    }
    return $plugins;
  }
}