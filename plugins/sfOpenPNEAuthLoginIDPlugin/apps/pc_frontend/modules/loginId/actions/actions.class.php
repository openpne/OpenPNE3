<?php

/**
 * loginId actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class loginIdActions extends sfActions
{
 /**
  * Executes registerEnd action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegisterEnd($request)
  {
    $member = $this->getUser()->getMember();
    $member->setIsActive(true);
    $member->save();

    $this->getUser()->setIsSNSMember(true);
    $this->redirect('member/home');
  }
}
