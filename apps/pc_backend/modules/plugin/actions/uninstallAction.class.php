<?php

/**
 * uninstall action.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Hiromi Hishida<info@77-web.com>
 */
class uninstallAction extends sfAction
{
  public function execute($request)
  {
    $this->name = $request->getParameter('name');
    $this->redirectUnless($this->name, 'plugin/list');
    
    if($request->isMethod(sfRequest::POST))
    {
      $request->checkCSRFProtection();
      
      $uninstaller = new opPluginUninstallTask($this->dispatcher, new sfFormatter());
      try
      {
        chdir(sfConfig::get('sf_root_dir'));
        $uninstaller->run(array('name' => $this->name));
        $message = 'The plugin was uninstalled successfully.';
      }
      catch (Exception $e)
      {
        $message = 'Failed : '.$e->getMessage();
      }
      
      $this->getUser()->setFlash('notice', $message);
      $this->redirect('plugin/list');
    }
    $this->form = new BaseForm();
  }
}