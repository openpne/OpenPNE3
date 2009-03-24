<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
      if ($uri = $this->getUser()->login())
      {
        $this->redirectIf($this->getUser()->hasCredential('SNSRegisterBegin'), 'member/registerInput');
        $this->redirectIf($this->getUser()->hasCredential('SNSRegisterFinish'), $this->getUser()->getRegisterEndAction());
        $this->redirectIf($this->getUser()->hasCredential('SNSMember'), $uri);
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
    $mode = (sfConfig::get('app_is_mobile') ? 'mobile' : 'pc');
    $this->forward404Unless(opToolkit::isEnabledRegistration($mode));

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
    
    $this->forward404Unless($this->member, 'Undefined member.');

    $c = new Criteria();
    $c->addAscendingOrderByColumn(Propel::getDB()->random(time()));

    if (!$this->friendsSize)
    {
      $this->friendsSize = 9;
    }
    $this->friends = $this->member->getFriends($this->friendsSize, $c);

    if (!$this->communitiesSize)
    {
      $this->communitiesSize = 9;
    }
    $this->communities = $this->member->getJoinCommunities($this->communitiesSize, $c);
    $this->crownIds = CommunityMemberPeer::getCommunityIdsOfAdminByMemberId($id);

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
          $config->setMemberId($memberId);
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

 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig($request)
  {
    $this->categories = sfConfig::get('openpne_member_category');

    $this->categoryCaptions = array();
    $categoryAttributes = sfConfig::get('openpne_member_category_attribute');

    foreach ($this->categories as $key => $value)
    {
      $title = $key;

      $enabledKey = 'enable_pc';
      if (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $enabledKey = 'enable_mobile';
      }

      if (isset($categoryAttributes[$key][$enabledKey]))
      {
        if (!$categoryAttributes[$key][$enabledKey])
        {
          unset($this->categories[$key]);
          continue;
        }
      }

      if (!empty($categoryAttributes[$key]['caption']))
      {
        $title = $categoryAttributes[$key]['caption'];
      }

      $this->categoryCaptions[$key] = $title;
    }

    $this->categoryName = $request->getParameter('category', null);
    if ($this->categoryName)
    {
      $this->forward404Unless(array_key_exists($this->categoryName, $this->categories), 'Undefined category');
      $formClass = 'MemberConfig'.ucfirst($this->categoryName).'Form';
      $this->form = new $formClass($this->getUser()->getMember());
    }

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save($this->getUser()->getMemberId());
        $this->redirect('member/config?category='.$this->categoryName);
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes invite action
  *
  * @param sfRequest $request A request object
  */
  public function executeInvite($request)
  {
    if (
      !$this->getUser()->getAuthAdapter()->getAuthConfig('invite_mode')
      || !opToolkit::isEnabledRegistration()
    )
    {
      return sfView::ERROR;
    }

    $this->form = new InviteForm(null, array('invited' => true));
    $this->form->setOption('is_link', true);
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save();

        return sfView::SUCCESS;
      }
    }

    $id = $this->getUser()->getMemberId();
    $this->invites = $this->getUser()->getMember()->getInvitingMembers();

    $this->listform = new InvitelistForm(
      array(),
      array('invites' => $this->invites)
    );
    if ($request->isMethod('post'))
    {
      $this->listform->bind($request->getParameter('invitelist'));
      if ($this->listform->isValid())
      {
        $this->listform->save();
        $this->redirect('member/invite');
      }
    }
 
    return sfView::INPUT;
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    if (1 == $this->getUser()->getMemberId())
    {
      return sfView::ERROR;
    }

    $this->form = new sfOpenPNEPasswordForm(array(), array('member' => $this->getUser()->getMember()));
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('password'));
      if ($this->form->isValid())
      {
        $member = $this->getUser()->getMember();
        $this->getUser()->getMember()->delete();
        $this->sendDeleteAccountMail($member);
        $this->getUser()->setFlash('notice', '退会が完了しました');
        $this->getUser()->logout();
        $this->redirect('member/login');
      }
    }

    return sfView::INPUT;
  }

  public function executeConfigImage($request)
  {
    return sfView::SUCCESS;
  }

  public function executeDeleteImage($request)
  {
    $image = MemberImagePeer::retrieveByPk($request->getParameter('member_image_id'));
    $this->forward404Unless($image);
    $this->forward404Unless($image->getMemberId() == $this->getUser()->getMemberId());

    $image->delete();

    $this->redirect('member/configImage');
  }

  public function executeChangeMainImage($request)
  {
    $image = MemberImagePeer::retrieveByPk($request->getParameter('member_image_id'));
    $this->forward404Unless($image);
    $this->forward404Unless($image->getMemberId() == $this->getUser()->getMemberId());

    $currentImage = $this->getUser()->getMember()->getImage();
    $currentImage->setIsPrimary(false);
    $currentImage->save();
    $image->setIsPrimary(true);
    $image->save();

    $this->redirect('member/configImage');
  }


  protected function sendDeleteAccountMail($member)
  {
    $param = array(
      'member'   => $member,
    );
    $mail = new sfOpenPNEMailSend();
    $mail->setSubject(opConfig::get('sns_name') . '退会者情報');
    $mail->setTemplate('global/deleteAccountMail', $param);
    $mail->send(opConfig::get('admin_mail_address'), opConfig::get('admin_mail_address'));
  }
}
