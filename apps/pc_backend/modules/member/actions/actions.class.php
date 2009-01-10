<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class memberActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('member', 'list');
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    $params = $request->getParameter('member', array());

    $this->form = new opMemberProfileSearchForm();
    $this->form->bind($params);

    $this->pager = new sfPropelPager('Member', 20);
    if ($params)
    {
      $this->pager->setCriteria($this->form->getCriteria());
    }
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->profiles = ProfilePeer::retrievesAll();

    return sfView::SUCCESS;
  }
}
