<?php

/**
 * default actions.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Hiromi Hishida
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
  public function executeInstall(sfWebRequest $request)
  {
    
    $this->form = new OpenPNEInstallForm();
    
    if($request->isMethod(sfRequest::POST))
    {
      if($this->getUser()->getAttribute('setup_install'))
      {
        try
        {
          $request->checkCSRFProtection();
        }
        catch(Exception $e)
        {
          $this->getUser()->setAttribute('setup_install');
          $this->redirect('@homepage');
        }
        
        $install = $this->getUser()->getAttribute('setup_install');
        
        $fileSystem = new sfFileSystem();
        $root = sfConfig::get('sf_root_dir');
        //PENDING: receive manual setting and reflect it to config/OpenPNE.yml
        $fileSystem->copy($root.'/config/OpenPNE.yml.sample', $root.'/config/OpenPNE.yml');
        $fileSystem->copy($root.'/config/ProjectConfiguration.class.php.sample', $root.'/config/ProjectConfiguration.class.php');
        
        $plugins = $this->form->getAllPluginList();
        $selectedPlugins = (array)$install['plugins'];
        $pergedPlugins = array();
        foreach($plugins as $name)
        {
          if(!in_array($name, $selectedPlugins))
          {
            $pergedPlugins[$name] = array('install'=>false);
          }
        }
        if(count($pergedPlugins) > 0)
        {
          file_put_contents(sfConfig::get('sf_config_dir').'/plugins.yml', sfYaml::dump($pergedPlugins));
        }
        
        $settings = array();
        $settings['dbms'] = $install['dbms'];
        $settings['dbuser'] = $install['dbuser'];
        $settings['dbpassword'] = $install['dbpass'];
        $settings['dbhost'] = $install['dbhost'];
        $settings['dbname'] = $install['dbname'];
        
        chdir(sfConfig::get('sf_root_dir'));
        
        $configuration = $this->getContext()->getConfiguration();
        $dispatcher = $configuration->getEventDispatcher();
        $task = new OpenPNEFastInstallTask($dispatcher, new sfFormatter());
        $task->run(array(), $settings);

        //merge first administrator settings
        $firstAdmin = Doctrine::getTable('AdminUser')->find(1);
        $firstAdmin->setUsername($install['first_admin_username']);
        $firstAdmin->setPassword($install['first_admin_password']);
        $firstAdmin->save();
        
        //merge first user settings. update fixture
        $firstMember = Doctrine::getTable('Member')->find(1);
        $firstMember->setConfig('pc_address', $install['first_user_email']);
        $firstMember->setConfig('password', md5($install['first_user_password']));
        
        $this->getUser()->setAttribute('setup_install', null);
        
        $fileSystem->remove(sfConfig::get('sf_web_dir').'/setup.php');
        
        //FIXME: ugly way to find redirect destination
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
        $scriptName = sfConfig::get('sf_debug') ? 'setup_dev.php/' : 'setup.php/';
        $url = str_replace($scriptName, 'pc_backend.php', url_for('@homepage', true));
        
        $this->redirect($url);
      }
      
      $this->form->bind($request->getParameter($this->form->getName()));
      if($this->form->isValid())
      {
        $this->getUser()->setAttribute('setup_install', $this->form->getValues());
        $this->confirmForm = new BaseForm();
        return sfView::SUCCESS;
      }
    }
    
    return sfView::INPUT;
  }
  
}
