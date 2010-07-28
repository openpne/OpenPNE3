<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * default actions.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
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
