<?php

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class communityActions extends sfOpenPNECommunityAction
{
 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    $result = parent::executeEdit($request);

    if (!$this->community) {
      sfConfig::set('sf_navi_type', 'default');
    }

    return $result;
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->pager = new sfPropelPager('Community', 20);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    if (!$this->pager->getNbResults())
    {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes joinlist action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoinlist($request)
  {
    sfConfig::set('sf_navi_type', 'default');

    if ($request->hasParameter('id') && $request->getParameter('id') != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_navi_type', 'friend');
    }

    return parent::executeJoinlist($request);
  }

}
