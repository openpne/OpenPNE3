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
    $config = $this->getContext()->getConfiguration();

    try
    {
      $pluginManager = new opPluginManager($this->getContext()->getEventDispatcher());
    }
    catch (sfPluginException $e) {}

    $this->plugins = $pluginManager->getInstalledPlugins();

    return sfView::SUCCESS;
  }
}
