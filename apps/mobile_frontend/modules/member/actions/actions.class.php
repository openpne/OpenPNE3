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

    $this->forms = $this->getUser()->getAuthForms();

    if ($request->isMethod('post'))
    {
      $authForm = $this->getUser()->getAuthForm();
      $authForm->bind($request->getParameter('auth'.$authForm->getAuthMode()));
      if ($authForm->isValid())
      {
        $this->redirectIf($this->getUser()->login($authForm), 'member/home');
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

 /**
  * Executes profile action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfile($request)
  {
    $id = $this->getRequestParameter('id', $this->getUser()->getMemberId());
    $this->member = MemberPeer::retrieveByPk($id);
    $this->friends = FriendPeer::retrievesByMemberId($id);
    $this->communities = CommunityPeer::retrievesByMemberId($id);
    $this->forward404Unless($this->member, 'Undefined member.');

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
    $this->memberForm->setMobileFormFormatter();

    $profiles = $this->getUser()->getMember()->getProfiles();
    $this->profileForm = new MemberProfileForm($profiles);
    $this->profileForm->setMobileFormFormatter();
    $this->profileForm->setConfigWidgets();

    if ($request->isMethod('post')) {
      $this->memberForm->bind($request->getParameter('member'));
      $this->profileForm->bind($request->getParameter('profile'));
      if ($this->memberForm->isValid() && $this->profileForm->isValid()) {
        $this->memberForm->save();
        $this->profileForm->save($this->getUser()->getMemberId());
        $this->redirect('member/profile');
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes configUID action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigUID($request)
  {
    $option = array('member' => $this->getUser()->getMember());
    $this->passwordForm = new sfOpenPNEPasswordForm(array(), $option);

    if ($request->isMethod('post')) {
      $this->passwordForm->bind($request->getParameter('password'));
      if ($this->passwordForm->isValid()) {
        $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('mobile_uid', $this->getUser()->getMemberId());
        if (!$memberConfig) {
          $memberConfig = new MemberConfig();
          $memberConfig->setMember($this->getUser()->getMember());
          $memberConfig->setName('mobile_uid');
        }
        $memberConfig->setValue($request->getMobileUID());
        $this->redirectIf($memberConfig->save(), 'member/configUID');
      }
    }

    return sfView::SUCCESS;
  }
}
