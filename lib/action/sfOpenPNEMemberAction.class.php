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
    if ('homepage' === sfContext::getInstance()->getRouting()->getCurrentRouteName())
    {
      if (isset($this->request['a']))
      {
        $this->handleOpenPNE2FormatUrl();
      }
    }

    $this->id = $this->getRequestParameter('id', $this->getUser()->getMemberId());

    $this->relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->getUser()->getMemberId(), $this->id);
    if (!$this->relation) {
      $this->relation = new MemberRelationship();
      $this->relation->setMemberIdFrom($this->getUser()->getMemberId());
      $this->relation->setMemberIdTo($this->id);
    }
  }

  protected function handleOpenPNE2FormatUrl()
  {
    $path = sfConfig::get('sf_app_config_dir').'/op2urls.php';
    if (!is_file($path))
    {
      return null;
    }

    $list = include_once($path);
    if (array_key_exists($this->request['a'], $list))
    {
      $table = $list[$this->request['a']];
      $this->forward404Unless($table);

      unset($this->request['m'], $this->request['a']);
      foreach ($table['params'] as $k => $v)
      {
        if (isset($this->request[$k]))
        {
          $this->request[$v] = $this->request[$k];
          unset($this->request[$k]);
        }
      }

      if (isset($table['route']))
      {
        $this->redirect($table['route'], $this->request->getParameterHolder()->getAll());
      }
      else
      {
        unset($this->request['module'], $this->request['action']);
        $this->redirect($table['url'].'?'.http_build_query($this->request->getParameterHolder()->getAll()));
      }
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

    $routing = sfContext::getInstance()->getRouting();
    if ('homepage' !== $routing->getCurrentRouteName()
      && 'login' !== $routing->getCurrentRouteName()
    )
    {
      $this->getUser()->setFlash('notice', 'Please login');
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
    opActivateBehavior::disable();
    $mode = (sfConfig::get('app_is_mobile') ? 'mobile' : 'pc');
    $this->forward404Unless(opToolkit::isEnabledRegistration($mode));

    $this->form = $this->getUser()->getAuthAdapter()->getAuthRegisterForm();

    if ($request->isMethod('post'))
    {
      $this->form->bindAll($request);
      if ($this->form->isValidAll())
      {
        $result = $this->getUser()->register($this->form);
        opActivateBehavior::enable();
        $this->redirectIf($result, $this->getUser()->getRegisterEndAction());
      }
    }

    opActivateBehavior::enable();
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
  * Executes search action
  *
  * @param sfRequest $request A request object
  */
  public function executeSearch($request)
  {
    $params = $request->getParameter('member', array());
    if ($request->hasParameter('search_query'))
    {
      $params = array_merge($params, array('name' => $request->getParameter('search_query', '')));
    }

    $this->filters = new opMemberProfileSearchForm();
    $this->filters->bind($params);

    if (!isset($this->size))
    {
      $this->size = 20;
    }

    $this->pager = new sfDoctrinePager('Member', $this->size);
    $q = $this->filters->getQuery()->orderBy('id desc');
    $this->pager->setQuery($q);
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
    $this->redirectIf($this->relation->isAccessBlocked(), '@error');

    $id = $this->getRequestParameter('id', $this->getUser()->getMemberId());
    $this->member = Doctrine::getTable('Member')->find($id);
    
    $this->forward404Unless($this->member, 'Undefined member.');

    if (!$this->friendsSize)
    {
      $this->friendsSize = 9;
    }
    $this->friends = $this->member->getFriends($this->friendsSize, true);

    if (!$this->communitiesSize)
    {
      $this->communitiesSize = 9;
    }
    $this->communities = $this->member->getJoinCommunities($this->communitiesSize, true);
    $this->crownIds = Doctrine::getTable('CommunityMember')->getCommunityIdsOfAdminByMemberId($id);

    $birthday = Doctrine::getTable('MemberProfile')->getViewableProfileByMemberIdAndProfileName($id, 'op_preset_birthday');
    $this->targetDay = $birthday ? opToolkit::extractTargetDay((string)$birthday) : false;

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

    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($type.'_token', $memberId);
    $this->forward404Unless($memberConfig);
    $this->forward404Unless((bool)$request->getParameter('token') !== $memberConfig->getValue());

    $option = array('member' => $memberConfig->getMember());
    $this->form = new sfOpenPNEPasswordForm(array(), $option);

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('password'));
      if ($this->form->isValid())
      {
        $config = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($type, $memberId);
        $pre = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($type.'_pre', $memberId);

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
          $token = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($type.'_token', $memberId);
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
    $filteredCategory = $this->filterConfigCategory();
    $this->categories = $filteredCategory['category'];
    $this->categoryCaptions = $filteredCategory['captions'];

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
        $this->getUser()->setFlash('notice', $this->form->getCompleteMessage());

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
    $image = Doctrine::getTable('MemberImage')->find($request->getParameter('member_image_id'));
    $this->forward404Unless($image);
    $this->forward404Unless($image->getMemberId() == $this->getUser()->getMemberId());

    $image->delete();

    $this->redirect('member/configImage');
  }

  public function executeChangeMainImage($request)
  {
    $image = Doctrine::getTable('MemberImage')->find($request->getParameter('member_image_id'));
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

    // to admin
    $mail = new sfOpenPNEMailSend();
    $mail->setSubject(opConfig::get('sns_name') . '退会者情報');
    $mail->setGlobalTemplate('deleteAccountMail', $param);
    $mail->send(opConfig::get('admin_mail_address'), opConfig::get('admin_mail_address'));

    // to member
    $param['subject'] = sfContext::getInstance()->getI18N()->__('Leaving from this site is finished');
    sfOpenPNEMailSend::sendTemplateMail('leave', $member->getEmailAddress(), opConfig::get('admin_mail_address'), $param);
  }

  protected function filterConfigCategory()
  {
    $categories = sfConfig::get('openpne_member_category');
    $categoryCaptions = array();
    $categoryAttributes = sfConfig::get('openpne_member_category_attribute');

    $ignoredSnsConfig = Doctrine::getTable('SnsConfig')->get('ignored_sns_config', array());
    if ($ignoredSnsConfig)
    {
      $ignoredSnsConfig = unserialize($ignoredSnsConfig);
    }

    if (isset($categories['mail']))
    {
      if (sfConfig::get('sf_app') == 'pc_frontend')
      {
        $dailyNewsNotification = Doctrine::getTable('NotificationMail')->findOneByName('pc_dailyNews');
      }
      elseif (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $dailyNewsNotification = Doctrine::getTable('NotificationMail')->findOneByName('mobile_dailyNews');
      }
      if ($dailyNewsNotification && !$dailyNewsNotification->is_enabled)
      {
        unset($categories['mail']);
      }
    }

    foreach ($categories as $key => $value)
    {
      $title = $key;

      if (isset($categoryAttributes[$key]['depending_sns_config']))
      {
        $snsConfig = $categoryAttributes[$key]['depending_sns_config'];
        if (!opConfig::get($snsConfig))
        {
          unset($categories[$key]);
          continue;
        }
      }

      if (in_array($key, $ignoredSnsConfig))
      {
        unset($categories[$key]);
        continue;
      }

      $enabledKey = 'enable_pc';
      if (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $enabledKey = 'enable_mobile';
      }

      if (isset($categoryAttributes[$key][$enabledKey]))
      {
        if (!$categoryAttributes[$key][$enabledKey])
        {
          unset($categories[$key]);
          continue;
        }
      }

      if (!empty($categoryAttributes[$key]['caption']))
      {
        $title = $categoryAttributes[$key]['caption'];
      }

      $categoryCaptions[$key] = $title;
    }

    return array('category' => $categories, 'captions' => $categoryCaptions);
  }

  public function executeShowActivity($request)
  {
    $this->forward404Unless($this->id);
    $this->forward404If($this->relation->isAccessBlocked());

    if (!isset($this->size))
    {
      $this->size = 20;
    }

    $this->member = Doctrine::getTable('Member')->find($this->id);
    $this->pager = Doctrine::getTable('ActivityData')->getActivityListPager($this->id, null, $request->getParameter('page', 1), $this->size);
  }

  public function executeDeleteActivity($request)
  {
    $this->forward404Unless($request->hasParameter('id'));

    $this->activity = Doctrine::getTable('ActivityData')->find($this->id);
    $this->forward404Unless($this->activity instanceof ActivityData);
    $this->forward404Unless($this->activity->getMemberId() == $this->getUser()->getMemberId());

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->activity->delete();
      $this->getUser()->setFlash('notice', 'An activity was deleted.');
      $this->redirect('friend/showActivity');
    }

    return sfView::INPUT;
  }

  public function executeUpdateActivity($request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->forward404Unless(opConfig::get('is_allow_post_activity'));
      $newObject = new ActivityData();
      $newObject->setMemberId($this->getUser()->getMemberId());
      $this->form = new ActivityDataForm($newObject);
      $params = $request->getParameter('activity_data');
      $this->form->bind($params);
      if ($this->form->isValid())
      {
        $this->form->save();
        if ($request->isXmlHttpRequest())
        {
          $activities = Doctrine::getTable('ActivityData')->getFriendActivityList();
          $this->getContext()->getConfiguration()->loadHelpers('Partial');
          return $this->renderText(get_partial('default/activityRecord', array('activity' => $this->form->getObject())));
        }
        else
        {
          $this->redirect($params['next_uri']);
        }
      }
      else
      {
        if ($request->isXmlHttpRequest())
        {
          $this->getResponse()->setStatusCode(500);
        }
        else
        {
          $this->getUser()->setFlash('error', 'Failed to post activity.');
          if (isset($params['next_uri']))
          {
            $this->redirect($params['next_uri']);
          }
          $this->redirect('@homepage');
        }
      }
    }
    return sfView::NONE;
  }
}
