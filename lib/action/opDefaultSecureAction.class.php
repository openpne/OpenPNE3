<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * default secure action.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opDefaultSecureAction extends sfAction
{
 /**
  * Executes secure action
  *
  * @param sfRequest $request A request object
  */
  public function execute($request)
  {
    $isForwardToLoginPage = false;

    $actionStack = sfContext::getInstance()->getController()->getActionStack();
    $stackSize = $actionStack->getSize();
    $preActionCredential = $actionStack->getEntry($stackSize - 2)->getActionInstance()->getCredential();

    if (is_array($preActionCredential))
    {
      $isForwardToLoginPage = in_array('SNSMember', $preActionCredential);
    }
    elseif ('SNSMember' === $preActionCredential)
    {
      $isForwardToLoginPage = true;
    }

    $this->forwardIf($isForwardToLoginPage, sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
  }
}
