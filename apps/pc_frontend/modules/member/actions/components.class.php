<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class memberComponents extends sfComponents
{
  public function executeProfileListBox($request)
  {
    if ($request->hasParameter('id'))
    {
      $this->member = MemberPeer::retrieveByPk($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
  }
}
