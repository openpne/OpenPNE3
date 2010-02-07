<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opRememberLoginFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opRememberLoginFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    if ($this->isFirstCall() && !$this->context->getUser()->isAuthenticated())
    {
      if ($memberId = $this->context->getUser()->getRememberedMemberId())
      {
        $this->context->getUser()->login($memberId);
      }
    }
    $filterChain->execute();
  }
}
