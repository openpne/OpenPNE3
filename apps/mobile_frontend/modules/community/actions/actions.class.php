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

    $this->pager = new sfPropelPager('Community', 20);
    $this->pager->setCriteria($this->filters->getCriteria());
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->categorys = CommunityCategoryPeer::retrieveAllChildren();

    return sfView::SUCCESS;
  }
}
