<?php

/**
 * sfOpenPNEMemberAction
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class sfOpenPNEMemberAction extends sfActions
{
  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id', $this->getUser()->getMemberId());

    $this->relation = MemberRelationshipPeer::retrieveByFromAndTo($this->getUser()->getMemberId(), $this->id);
    if (!$this->relation) {
      $this->relation = new MemberRelationship();
      $this->relation->setMemberIdFrom($this->getUser()->getMemberId());
      $this->relation->setMemberIdTo($this->id);
    }
  }

 /**
  * Executes login action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin($request)
  {
    $this->getUser()->logout();

    $this->forms = $this->getUser()->getAuthForms();

    if ($request->hasParameter('authMode'))
    {
      if ($this->getUser()->login())
      {
        $this->redirectIf($this->getUser()->hasCredential('SNSRegisterBegin'), 'member/registerInput');
        $this->redirectIf($this->getUser()->hasCredential('SNSRegisterFinish'), $this->getUser()->getRegisterEndAction());
        $this->redirectIf($this->getUser()->hasCredential('SNSMember'), 'member/home');
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
    $this->form = $this->getUser()->getAuthAdapter()->getAuthRegisterForm();

    if ($request->isMethod('post'))
    {
      $this->form->bindAll($request);
      if ($this->form->isValidAll())
      {
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
    $this->getUser()->getMember()->updateLastLoginTime();
    return sfView::SUCCESS;
  }

 /**
  * Executes profile action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfile($request)
  {
    $this->redirectIf($this->relation->isAccessBlocked(), '@error');

    $id = $this->getRequestParameter('id', $this->getUser()->getMemberId());
    $this->member = MemberPeer::retrieveByPk($id);
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

    $profiles = $this->getUser()->getMember()->getProfiles();
    $this->profileForm = new MemberProfileForm($profiles);
    $this->profileForm->setConfigWidgets();

    if ($request->isMethod('post'))
    {
      $this->memberForm->bind($request->getParameter('member'));
      $this->profileForm->bind($request->getParameter('profile'));
      if ($this->memberForm->isValid() && $this->profileForm->isValid())
      {
        $this->memberForm->save();
        $this->profileForm->save($this->getUser()->getMemberId());
        $this->redirect('member/profile');
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes config complete action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigComplete($request)
  {
    $type = $request->getParameter('type');
    $this->forward404Unless($type);

    $memberId = $request->getParameter('id');

    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($type.'_token', $memberId);
    $this->forward404Unless($memberConfig);
    $this->forward404Unless((bool)$request->getParameter('token') !== $memberConfig->getValue());

    $option = array('member' => $memberConfig->getMember());
    $this->form = new sfOpenPNEPasswordForm(array(), $option);

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('password'));
      if ($this->form->isValid())
      {
        $config = MemberConfigPeer::retrieveByNameAndMemberId($type, $memberId);
        $pre = MemberConfigPeer::retrieveByNameAndMemberId($type.'_pre', $memberId);

        if (!$config)
        {
          $config = new MemberConfig();
          $config->setName($type);
        }

        $config->setValue($pre->getValue());

        if ($config->save())
        {
          $pre->delete();
          $token = MemberConfigPeer::retrieveByNameAndMemberId($type.'_token', $memberId);
          $token->delete();
        }

        $this->redirect('member/home');
      }
    }

    return sfView::SUCCESS;
  }

}
