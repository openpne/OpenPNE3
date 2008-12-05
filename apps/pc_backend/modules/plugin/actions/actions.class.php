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

    $pluginManager = new opPluginManager($this->getContext()->getEventDispatcher());
    $this->plugins = $pluginManager->getInstalledPlugins();

    return sfView::SUCCESS;
  }
}
