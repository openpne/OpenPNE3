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
  * Executes search action
  *
  * @param sfRequest $request A request object
  */
  public function executeSearch($request)
  {
    $this->filters = new CommunityFormFilter();
    $this->filters->bind($request->getParameter('community'));

    $this->pager = new sfPropelPager('Community', 20);
    $this->pager->setCriteria($this->filters->getCriteria());
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

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
