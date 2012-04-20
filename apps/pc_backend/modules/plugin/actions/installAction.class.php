<?php

/**
 * install action.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Hiromi Hishida<info@77-web.com>
 */
class installAction extends sfAction
{
  public function execute($request)
  {
    $this->form = new PluginInstallForm();
    $this->form->bind($request->getParameter($this->form->getName()));
    
    if($this->form->isValid())
    {
      $options = array('name' => $this->form->getValue('name'));
      $arguments = array();
      if($this->form->getValue('version'))
      {
        $arguments['release'] = $this->form->getValue('version');
      }
      $installer = new opPluginInstallTask($this->dispatcher, new sfFormatter());
      
      try
      {
        chdir(sfConfig::get('sf_root_dir'));
        $installer->run($options, $arguments);
        $message = 'The plugin was installed successfully.';
      }
      catch (Exception $e)
      {
        $message = 'Failed : '.$e->getMessage();
      }
      
      $this->getUser()->setFlash('notice', $message);
      $this->redirect('plugin/list');
    }
  }
}