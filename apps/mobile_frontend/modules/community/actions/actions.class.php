<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.net>
 */
class communityActions extends sfOpenPNECommunityAction
{
  /**
   * Executes home action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeHome(sfWebRequest $request)
  {
    $this->membersSize = 5;

    return parent::executeHome($request);
  }

  /**
   * Executes joinlist action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeJoinlist(sfWebRequest $request)
  {
    $this->size = 10;

    return parent::executeJoinlist($request);
  }

  /**
   * Executes memberList action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeMemberList(sfWebRequest $request)
  {
    $this->size = 10;

    return parent::executeMemberList($request);
  }

  /**
   * Executes search action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeSearch(sfWebRequest $request)
  {
    sfConfig::set('sf_nav_type', 'default');

    $params = $request->getParameter('community', array());
    $this->isResult = false;
    $this->category_id = 0;
    if (isset($params['name']))
    {
      $params['name'] = $params['name'];
      $this->isResult = true;
    }
    if (isset($params['community_category_id']))
    {
      $this->category_id = $params['community_category_id'];
      $params['community_category_id'] = $this->category_id;
      $this->isResult = true;
    }

    $this->filters = new CommunityFormFilter();
    $this->filters->bind($params);
    $q = $this->filters->getQuery();

    $this->pager = new sfDoctrinePager('Community', 10);
    $this->pager->setQuery($q);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->categorys = Doctrine::getTable('CommunityCategory')
      ->createQuery()
      ->where('lft > 1')
      ->execute();

    return sfView::SUCCESS;
  }

  /**
   * Executes detail action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeDetail(sfWebRequest $request)
  {
    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community, 'Undefined community.');
    $this->community_admin = Doctrine::getTable('CommunityMember')->getCommunityAdmin($this->id);
    $this->community_admin = Doctrine::getTable('Member')->find($this->community_admin->getMemberId());

    return sfView::SUCCESS;
  }

  /**
   * Executes configImage action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeConfigImage(sfWebRequest $request)
  {
    $this->forward404Unless($this->id && $this->isEditCommunity);
    $this->community = Doctrine::getTable('Community')->find($this->id);
  }

  /**
   * Executes deleteImage action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeDeleteImage($request)
  {
    $this->forward404Unless($this->id && $this->isEditCommunity);

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community->getImageFileName());

    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $this->community->getFile()->delete();
      $this->redirect('community/configImage?id='.$this->id);
    }
  }
}
