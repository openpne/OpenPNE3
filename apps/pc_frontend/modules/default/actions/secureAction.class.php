<?php

/**
 * default actions.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class secureAction extends sfAction
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

    if (is_array($preActionCredential)) {
      $isForwardToLoginPage = in_array('SNSMember', $preActionCredential);
    } elseif ($preActionCredential == 'SNSMember') {
      $isForwardToLoginPage = true;
    }

    $this->forwardIf($isForwardToLoginPage, sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
  }
}
