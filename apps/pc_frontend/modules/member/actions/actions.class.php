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
    $this->memberForm = new MemberForm();

    $this->authForm = $this->getUser()->getAuthForm();
    $this->authForm->setForRegisterWidgets();

    $this->profileForm = new ProfileForm();
    $this->profileForm->setRegisterWidgets();

    $member = $request->getParameter('member');
    $auth = $request->getParameter('auth');
    $profile = $request->getParameter('profile');

    if ($member && $auth && $profile) {
      $this->memberForm->bind($member);
      $this->authForm->bind($auth);
      $this->profileForm->bind($profile);

      if ($this->memberForm->isValid() && $this->authForm->isValid() && $this->profileForm->isValid()) {
        $memberResult = $this->memberForm->save();
        $profileResult = $this->profileForm->save($memberResult->getId());
        $formResult = $this->getUser()->register($memberResult->getId(), $this->authForm);
        $this->redirectIf(($memberResult && $profileResult && $formResult), $this->getUser()->getRegisterEndAction());
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
    $this->memberForm = new MemberForm($this->getUser()->getMember());

    $profiles = $this->getUser()->getMember()->getProfiles();
    $this->profileForm = new ProfileForm($profiles);
    $this->profileForm->setConfigWidgets();

    $member = $request->getParameter('member');
    $profile = $request->getParameter('profile');

    if ($member && $profile) {
      $this->memberForm->bind($member);
      $this->profileForm->bind($profile);
      if ($this->memberForm->isValid() && $this->profileForm->isValid()) {
        $this->memberForm->save();
        $this->profileForm->save($this->getUser()->getMemberId());
        $this->redirect('member/profile');
      }
    }

    return sfView::SUCCESS;
  }
}
