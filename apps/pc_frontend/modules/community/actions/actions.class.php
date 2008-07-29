<?php

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class communityActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    $this->forward('default', 'module');
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    $this->community = CommunityPeer::retrieveByPk($request->getParameter('id'));
    $this->form = new CommunityForm($this->community);

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('community'));
      if ($this->form->isValid()) {
        $community = $this->form->save();
        $this->redirect('community/home?id=' . $community->getId());
      }
    }
  }

 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->community = CommunityPeer::retrieveByPk($request->getParameter('id'));
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

    return sfView::SUCCESS;
  }
}
