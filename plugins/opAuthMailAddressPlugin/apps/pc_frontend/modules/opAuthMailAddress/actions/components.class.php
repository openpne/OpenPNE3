<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opAuthMailAddressComponents extends sfComponents
{
  public function executeRegisterBox($request)
  {
    $token = $request->getParameter('token');
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue('register_token', $token);
    opActivateBehavior::disable();
    $member = $memberConfig->getMember();
    $this->addressPre = $member->getConfig('pc_address_pre') || $member->getConfig('mobile_address_pre');
    opActivateBehavior::enable();
  }
}
