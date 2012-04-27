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
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthMailAddressPluginAction extends opAuthAction
{
  public function executeHelpLoginError($request)
  {
  }

  public function executePasswordRecovery($request)
  {
    $this->form = new opAuthMailAddressPasswordRecoveryForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request['password_recovery']);
      if ($this->form->isValid())
      {
        $this->form->sendMail();

        $this->getUser()->setFlash('notice', 'Sent you a mail for completing your password recovery. If you cannot receive the mail, please retry a password recovery process.');
        $this->redirect('member/login');
      }
    }
  }

  public function executePasswordRecoveryComplete($request)
  {
    $this->forward404Unless(isset($request['id']) && isset($request['token']));
    $this->member = Doctrine::getTable('Member')->find($request['id']);
    $this->forward404Unless($this->member && $this->member->getConfig('password_recovery_token') === $request['token']);

    $this->form = new opAuthMailAddressPasswordChangeForm();
    $this->form->member = $this->member;
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request['password_change']);
      if ($this->form->isValid())
      {
        $this->form->save();

        Doctrine::getTable('MemberConfig')->findOneByMemberIdAndName($this->member->id, 'password_recovery_token')->delete();

        $this->getUser()->setFlash('notice', 'Your password is now changed.');
        $this->redirect('member/login');
      }
    }
  }
}
