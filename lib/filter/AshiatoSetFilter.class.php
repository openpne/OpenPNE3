<?php

/**
 * AshiatoSetFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here < @tejimaya.com>
 */
class AshiatoSetFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    $filterChain->execute();
    $context    = $this->getContext();
    $controller = $context->getController();
    $user       = $context->getUser();
    $request    = $context->getRequest();

    $module = $request->getParameter('module');
    $action = $request->getParameter('action');
    //暫定対応
    if($module == 'member' && $request->getParameter('id') && $user->getMemberId()){
      AshiatoPeer::setAshiatoMember($request->getParameter('id'), $user->getMemberId());
    }
  }
}
