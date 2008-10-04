<?php

/**
 * sfOpenPNEActionFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEActionFilter extends sfFilter
{
  /**
   * @see sfFilter
   */
  final public function execute($filterChain)
  {
    $this->preFilter();
    $filterChain->execute();
    $this->postFilter();
  }

 /**
  * Executes this filter before an action.
  *
  * If you want to do something before an action, you have to override the preFilter() method.
  */
  protected function preFilter()
  {
  }

 /**
  * Executes this filter after an action.
  *
  * If you want to do something after an action, you have to override the postFilter() method.
  */
  protected function postFilter()
  {
  }
}
