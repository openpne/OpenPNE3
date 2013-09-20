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
class communityActions extends opCommunityAction
{
 /**
  * Executes edit action
  *
  * @param opWebRequest $request A request object
  */
  public function executeEdit(opWebRequest $request)
  {
    $this->enableImage = true;
    $result = parent::executeEdit($request);

    if ($this->community->isNew()) {
      sfConfig::set('sf_nav_type', 'default');
    }


    return $result;
  }

 /**
  * Executes joinlist action
  *
  * @param opWebRequest $request A request object
  */
  public function executeJoinlist(opWebRequest $request)
  {
    sfConfig::set('sf_nav_type', 'default');

    if ($request->hasParameter('id') && $request->getParameter('id') != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
    }

    return parent::executeJoinlist($request);
  }
}
