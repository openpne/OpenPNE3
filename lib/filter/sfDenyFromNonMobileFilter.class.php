<?php

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

      if (!$request->isMobile()) {
        if (!$this->isErrorAction()) {
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
}
