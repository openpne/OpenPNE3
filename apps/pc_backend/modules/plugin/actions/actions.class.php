<?php

/**
 * plugin actions.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class pluginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    try
    {
      $pluginManager = new opPluginManager($this->getContext()->getEventDispatcher());
    }
    catch (sfPluginException $e) {}

    $this->plugins = $pluginManager->getInstalledPlugins();

    $this->form = new PluginActivationForm(array(), array('plugins' => $this->plugins));

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($this->request->getParameter('plugin_activation'));
      $this->redirectIf($this->form->save(), 'plugin/list');
    }

    return sfView::SUCCESS;
  }
}
