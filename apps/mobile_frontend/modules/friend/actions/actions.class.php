<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * friend actions.
 *
 * @package    OpenPNE
 * @subpackage friend
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class friendActions extends opFriendAction
{
  /**
   * Executes list action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeList(sfWebRequest $request)
  {
    $this->size = 10;

    return parent::executeList($request);
  }

 /**
  * Execute show activities action
  *
  * @param sfWebRequest $request a request object
  */
  public function executeShowActivity(sfWebRequest $request)
  {
    $this->size = 10;

    return parent::executeShowActivity($request);
  }
}
