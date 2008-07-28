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
    $this->getUser()->logout();

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
    $this->authForm = $this->getUser()->getAuthForm();
    $this->authForm->setForRegisterWidgets();

    $this->profileForm = new ProfileForm();
    $this->profileForm->setRegisterWidgets();

    $auth = $request->getParameter('auth');
    $profile = $request->getParameter('profile');

    if ($auth && $profile) {
      $this->authForm->bind($auth);
      $this->profileForm->bind($profile);

      if ($this->authForm->isValid() && $this->profileForm->isValid()) {
        $member = new Member();
        $member->setIsActive(false);
        $member->save();

        $profileResult = $this->profileForm->save($member->getId());
        $formResult = $this->getUser()->register($member->getId(), $this->authForm);
        $this->redirectIf(($profileResult && $formResult), $this->getUser()->getRegisterEndAction());
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

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->pager = new sfPropelPager('Member', 20);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    return sfView::SUCCESS;
  }
}
