<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCheckEnabledApplicationFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara
 */
class opCheckEnabledApplicationFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
      $current = $this->context->getRouting()->getCurrentRouteName();
      $configName = 'enable_'.$this->getParameter('app', 'pc');

      if (!opConfig::get($configName))
      {
        if ($current !== 'error')
        {
          $this->context->getController()->redirect('@error');
          throw new sfStopException();
        }
      }

      $filterChain->execute();
  }
}
