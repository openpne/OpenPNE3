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

 /**
  * Executes profile action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfile($request)
  {
    $id = $this->getRequestParameter('id', $this->getUser()->getMemberId());
    $this->member = MemberPeer::retrieveByPk($id);

    return sfView::SUCCESS;
  }

 /**
  * Executes editProfile action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditProfile($request)
  {
    $profiles = $this->getUser()->getMember()->getProfiles();
    $this->form = new ProfileForm($profiles);
    $this->form->setConfigWidgets();

    $profile = $request->getParameter('profile');

    if ($profile) {
      $this->form->bind($profile);
      if ($this->form->isValid()) {
        $result = $this->form->save($this->getUser()->getMemberId());
        $this->redirectIf($result, 'member/profile');
      }
    }

    return sfView::SUCCESS;
  }
}
