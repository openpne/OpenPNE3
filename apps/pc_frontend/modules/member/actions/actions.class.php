<?php

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class memberActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    $this->redirect('member/login');
  }

 /**
  * Executes login action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin($request)
  {
    $this->form = $this->getUser()->getAuthForm();
    $auth = $request->getParameter('auth');

    if ($auth) {
      $this->form->bind($auth);

      if ($this->form->isValid()) {
        $this->redirectIf($this->getUser()->login($this->form), 'member/home');
      }

      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes register action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegisterInput($request)
  {
    $this->form = $this->getUser()->getAuthForm();
    $this->form->setForRegisterWidgets();

    $auth = $request->getParameter('auth');

    if ($auth) {
      $this->form->bind($auth);

      if ($this->form->isValid()) {
        $member = new Member();
        $member->setIsActive(false);
        $member->save();

        $this->redirectIf(
          $this->getUser()->register($member->getId(), $this->form),
          $this->getUser()->getRegisterEndAction()
        );
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
