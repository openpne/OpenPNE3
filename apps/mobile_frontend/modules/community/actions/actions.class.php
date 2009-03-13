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

    parent::executeJoinlist($request);
  }

  /**
   * Executes memberList action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeMemberList(sfWebRequest $request)
  {
    $this->size = 10;

    parent::executeMemberList($request);
  }
}
