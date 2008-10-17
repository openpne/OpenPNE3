<?php

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class memberActions extends sfActions
{
 /**
  * Executes login action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin($request)
  {
    $this->getUser()->logout();

    $this->form = $this->getUser()->getAuthForm();

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('auth'));
      if ($this->form->isValid()) {
        $this->redirectIf($this->getUser()->login($this->form), 'member/home');
      }
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes logout action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogout($request)
  {
    $this->getUser()->logout();
    $this->redirect('member/login');
  }

 /**
  * Executes register action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegisterInput($request)
  {
    $this->form = $this->getUser()->getAuthForm();
    $this->form->setForRegisterWidgets($this->getUser()->getMember());

    if ($request->isMethod('post')) {
      $this->form->bindAll($request);
      if ($this->form->isValidAll()) {
        $result = $this->getUser()->register($this->form);
        $this->redirectIf($result, $this->getUser()->getRegisterEndAction());
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    return sfView::SUCCESS;
  }
}
