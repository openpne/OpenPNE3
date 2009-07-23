<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfOpenPNECommunityComponents
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class sfOpenPNECommunityComponents extends sfComponents
{
  public function executeCautionAboutCommunityMemberPre()
  {
    $memberId = sfContext::getInstance()->getUser()->getMemberId();

    $this->communityMembers = Doctrine::getTable('CommunityMember')->getCommunityMembersPre($memberId);
  }
}
