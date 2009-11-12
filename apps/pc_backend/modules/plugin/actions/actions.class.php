<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
    $pluginManager = new opInstalledPluginManager();

    $this->plugins = $pluginManager->getInstalledPlugins();

    $this->form = new PluginActivationForm(array(), array('plugins' => $this->plugins));

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($this->request->getParameter('plugin_activation'));
      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirectIf($this->form->save(), 'plugin/list');
    }

    return sfView::SUCCESS;
  }
}
