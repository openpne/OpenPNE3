<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
