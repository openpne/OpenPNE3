<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthMailAddress actions.
 *
 * @package    OpenPNE
 * @subpackage opAuthMailAddressPlugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthMailAddressActions extends opAuthMailAddressPluginAction
{
  public function executeRequestRegisterURL($request)
  {
    $adapter = new opAuthAdapterMailAddress('MailAddress');
    if ($adapter->getAuthConfig('invite_mode') < 2)
    {
      $this->forward404();
    }

    $this->forward404Unless(opToolkit::isEnabledRegistration());

    return sfView::INPUT;
  }

  public function executeRegister($request)
  {
    $this->getUser()->setCurrentAuthMode('MailAddress');

    $token = $request->getParameter('token');
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue('register_token', $token);
    $this->forward404Unless($memberConfig, 'This URL is invalid.');

    opActivateBehavior::disable();
    $authMode = $memberConfig->getMember()->getConfig('register_auth_mode');
    $mobileAddressPre = $memberConfig->getMember()->getConfig('mobile_address_pre');
    opActivateBehavior::enable();

    if ('MobileUID' === $authMode)
    {
      $authMode = 'MailAddress';
    }
    $this->forward404Unless($authMode === $this->getUser()->getCurrentAuthMode());

    if (!$mobileAddressPre)
    {
      
      return sfView::ERROR;
    }

    $this->getUser()->setMemberId($memberConfig->getMemberId());
    $this->getUser()->setIsSNSRegisterBegin(true);

    $this->redirect('member/registerInput?token='.$token);
  }
}
