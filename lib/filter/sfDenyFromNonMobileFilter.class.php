<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfDenyFromNonMobileFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara
 */
class sfDenyFromNonMobileFilter extends sfFilter
{
  protected
    $errorModule = 'default',
    $errorAction = 'nonMobileError';

  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    $request = $this->context->getRequest();

    if (!$request->isMobile() && !$this->isErrorAction())
    {
      if ($url = $this->generatePcFrontendUrl())
      {
        $this->redirect($url);
      }
      else
      {
        $this->forwardToErrorAction();
      }
    }

    $filterChain->execute();
  }

  private function isErrorAction()
  {
    return ($this->errorModule == $this->context->getModuleName() && $this->errorAction == $this->context->getActionName());
  }

  private function forwardToErrorAction()
  {
    $this->context->getController()->forward($this->errorModule, $this->errorAction);

    throw new sfStopException();
  }

  private function generatePcFrontendUrl()
  {
    $parameters = $this->context->getRequest()->getParameterHolder()->getAll();
    $parameters['sf_route'] = $this->context->getRouting()->getCurrentRouteName();

    try
    {
      return $this->context->getConfiguration()->generateAppUrl('pc_frontend', $parameters, true);
    }
    catch (sfConfigurationException $e)
    {
      return false;
    }
  }

  private function redirect($url)
  {
    $this->context->getController()->redirect($url);
  }
}
