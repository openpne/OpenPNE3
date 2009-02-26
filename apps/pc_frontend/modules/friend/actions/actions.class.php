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
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class friendActions extends sfOpenPNEFriendAction
{
  public function preExecute()
  {
    parent::preExecute();

    if ($this->id == $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'default');
    }
  }

  public function executeList(sfWebRequest $request)
  {
    $this->size = 50;

    parent::executeList($request);
  }

  public function executeShowImage(sfWebRequest $request)
  {
    $this->forward404Unless($this->id);

    $this->member = MemberPeer::retrieveByPk($this->id);
    $this->forward404Unless($this->member, 'Undefined member.');

    return sfView::SUCCESS;
  }
}
