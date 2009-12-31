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
    $this->type = $request->getParameter('type', 'application');

    $pluginManager = new opInstalledPluginManager();

    if ('skin' === $this->type)
    {
      $this->plugins = $pluginManager->getInstalledSkinPlugins();
    }
    elseif ('auth' === $this->type)
    {
      $this->plugins = $pluginManager->getInstalledAuthPlugins();
    }
    else
    {
      $this->plugins = $pluginManager->getInstalledApplicationPlugins();
    }

    $this->form = new PluginActivationForm(array(), array('plugins' => $this->plugins, 'type' => $this->type));

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($this->request->getParameter('plugin_activation'));
      if ($this->form->isValid())
      {
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->form->save();
      }
      else
      {
        $this->getUser()->setFlash('error', $this->form->getErrorSchema()->getMessage());
      }

      $this->redirect('plugin/list?type='.$this->type);
    }

    return sfView::SUCCESS;
  }
}
