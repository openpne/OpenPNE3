<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCommunityAction
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class opCommunityAction extends sfActions
{
  static protected function getAcl($community, $member = null)
  {
    return opCommunityAclBuilder::buildResource($community, array($member));
  }

  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id');

    $memberId = $this->getUser()->getMemberId();
    $this->isCommunityMember = Doctrine::getTable('CommunityMember')->isMember($memberId, $this->id);
    $this->isCommunityPreMember = Doctrine::getTable('CommunityMember')->isPreMember($memberId, $this->id);
    $this->isAdmin = Doctrine::getTable('CommunityMember')->isAdmin($memberId, $this->id);
    $this->isSubAdmin = Doctrine::getTable('CommunityMember')->isSubAdmin($memberId, $this->id);
    $this->isEditCommunity = $this->isAdmin || $this->isSubAdmin;
    $this->isDeleteCommunity = $this->isAdmin;

    $this->role = $memberId ? $memberId : 'alien';
  }

  public function postExecute()
  {
    if ($this->getUser()->getMember())
    {
      opToolkit::setIsSecure($this);
    }
  }

 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome(opWebRequest $request)
  {
    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community, 'Undefined community.');
    $acl = self::getAcl($this->community, $this->getUser()->getMember());
    $this->forward404Unless($acl->isAllowed($this->role, null, 'view'));
    $this->communityAdmin = $this->community->getAdminMember();
    $this->communitySubAdmins = $this->community->getSubAdminMembers();

    if (!$this->membersSize)
    {
      $this->membersSize = 9;
    }
    $this->members = $this->community->getMembers($this->membersSize, true);
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit(opWebRequest $request)
  {
    $this->forward404If($this->id && !$this->isEditCommunity);

    $this->community = Doctrine::getTable('Community')->find($this->id);

    if (!$this->community)
    {
      $this->community = new Community();
    }
    else
    {
      if ($request->isMethod('post') && $request->hasParameter('is_delete'))
      {
        $this->redirect('@community_delete');
      }
    }

    $this->communityForm       = new CommunityForm($this->community);
    $this->communityConfigForm = new CommunityConfigForm(array(), array('community' => $this->community));
    if (!sfConfig::get('app_is_mobile', false))
    {
      $this->communityFileForm = new CommunityFileForm(array(), array('community' => $this->community));
    }

    if ($request->isMethod('post'))
    {
      $params = $request->getParameter('community');
      $params['id'] = $this->id;
      $this->communityForm->bind($params);
      $this->communityConfigForm->bind($request->getParameter('community_config'));
      if($this->communityFileForm)
      {
        $this->communityFileForm->bind($request->getParameter('community_file'), $request->getFiles('community_file'));
        if ($this->communityForm->isValid() && $this->communityConfigForm->isValid() && $this->communityFileForm->isValid())
        {
          $this->communityForm->save();
          $this->communityConfigForm->save();
          $this->communityFileForm->save();

          $this->redirect('@community_home?id='.$this->community->getId());
        }
      }
      elseif ($this->communityForm->isValid() && $this->communityConfigForm->isValid())
      {
        $this->communityForm->save();
        $this->communityConfigForm->save();

        $this->redirect('@community_home?id='.$this->community->getId());
      }
    }
  }

 /**
  * Executes search action
  *
  * @param sfRequest $request A request object
  */
  public function executeSearch(opWebRequest $request)
  {
    sfConfig::set('sf_nav_type', 'default');

    $params = $request->getParameter('community', array());
    if ($request->hasParameter('search_query'))
    {
      $params = array_merge($params, array('name' => $request->getParameter('search_query', '')));
    }

    $this->filters = new CommunityFormFilter();
    $this->filters->bind($params);

    if (!isset($this->size))
    {
      $this->size = 20;
    }

    $this->pager = new opNonCountQueryPager('Community', $this->size);
    $q = $this->filters->getQuery()->orderBy('id desc');
    $this->pager->setQuery($q);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete(opWebRequest $request)
  {
    $this->forward404If($this->id && !$this->isDeleteCommunity);
    $this->community = Doctrine::getTable('Community')->find($this->id);

    if ($request->isMethod('post'))
    {
      if($request->hasParameter('is_delete'))
      {
        $request->checkCSRFProtection();

        if ($this->community)
        {
          $this->community->delete();
        }
        $this->redirect('community/search');
      }
      else
      {
        $this->redirect('@community_home?id=' . $this->id);
      }
    }
  }

 /**
  * Executes joinlist action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoinlist(opWebRequest $request)
  {
    $memberId = $request->getParameter('id', $this->getUser()->getMemberId());

    $this->member = Doctrine::getTable('Member')->find($memberId);
    $this->forward404Unless($this->member);

    if (!$this->size)
    {
      $this->size = 20;
    }

    $this->pager = Doctrine::getTable('Community')->getJoinCommunityListPager($memberId, $request->getParameter('page', 1), $this->size);

    if (!$this->pager->getNbResults())
    {
      return sfView::ERROR;
    }

    $this->crownIds = Doctrine::getTable('CommunityMember')->getCommunityIdsOfAdminByMemberId($memberId);

    return sfView::SUCCESS;
  }

 /**
  * Executes memberList action
  *
  * @param sfRequest $request A request object
  */
  public function executeMemberList(opWebRequest $request)
  {
    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community);

    if (!$this->size)
    {
      $this->size = 20;
    }
    $this->pager = Doctrine::getTable('Community')->getCommunityMemberListPager($this->id, $request->getParameter('page', 1), $this->size);

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }
    
    $this->crownIds = array(Doctrine::getTable('CommunityMember')->getCommunityAdmin($this->id)->getMemberId());
    
    return sfView::SUCCESS;
  }

 /**
  * Executes join action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoin(opWebRequest $request)
  {
    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community);

    if ($this->isCommunityMember || $this->isCommunityPreMember)
    {
      return sfView::ERROR;
    }

    $this->form = new opCommunityJoiningForm();
    if ($request->hasParameter('community_join'))
    {
      $this->form->bind($request->getParameter('community_join'));
      if ($this->form->isValid())
      {
        Doctrine::getTable('CommunityMember')->join($this->getUser()->getMemberId(), $this->id, $this->community->getConfig('register_policy'));
        self::sendJoinMail($this->getUser()->getMemberId(), $this->id);

        if ('close' !== $this->community->getConfig('register_policy'))
        {
          $this->getUser()->setFlash('notice', 'You have just joined to this %community%.');
        }

        $this->redirect('@community_home?id='.$this->id);
      }
    }

    return sfView::INPUT;
  }

 /**
  * Executes quit action
  *
  * @param sfRequest $request A request object
  */
  public function executeQuit(opWebRequest $request)
  {
    if (!$this->isCommunityMember || $this->isAdmin)
    {
      return sfView::ERROR;
    }

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->form = new opCommunityQuittingForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('community_quit'));
      if ($this->form->isValid())
      {
        Doctrine::getTable('CommunityMember')->quit($this->getUser()->getMemberId(), $this->id);
        $this->getUser()->setFlash('notice', 'You have just quitted this %community%.');
        $this->redirect('@community_home?id='.$this->id);
      }
    }
  }

 /**
  * Executes memberManage action
  *
  * @param sfRequest $request A request object
  */
  public function executeMemberManage(opWebRequest $request)
  {
    $this->redirectUnless($this->isAdmin || $this->isSubAdmin, '@error');

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->pager = Doctrine::getTable('Community')->getCommunityMemberListPager($this->id, $request->getParameter('page', 1));

    if (!$this->pager->getNbResults())
    {
      return sfView::ERROR;
    }
  }

 /**
  * Executes changeAdminRequest action
  *
  * @param sfRequest $request A request object
  */
  public function executeChangeAdminRequest(opWebRequest $request)
  {
    $this->forward404Unless($this->isAdmin);

    $this->member = Doctrine::getTable('Member')->find($request->getParameter('member_id'));
    $this->forward404Unless($this->member);

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->communityMember = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($this->member->getId(), $this->id);

    $this->forward404If($this->communityMember->getIsPre());
    $this->forward404If($this->communityMember->hasPosition(array('admin', 'admin_confirm')));

    $this->form = new opChangeCommunityAdminRequestForm();
    if ($request->hasParameter('admin_request'))
    {
      $this->form->bind($request->getParameter('admin_request'));
      if ($this->form->isValid())
      {
        Doctrine::getTable('CommunityMember')->requestChangeAdmin($this->member->getId(), $this->id);
        $this->redirect('@community_memberManage?id='.$this->id);
      }
    }

    return sfView::INPUT;
  }

 /**
  * Executes subAdminRequest action
  *
  * @param sfRequest $request A request object
  */
  public function executeSubAdminRequest(opWebRequest $request)
  {
    $this->forward404Unless($this->isAdmin);

    $this->member = Doctrine::getTable('Member')->find($request->getParameter('member_id'));
    $this->forward404Unless($this->member);

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->communityMember = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($this->member->getId(), $this->id);

    $this->forward404If($this->communityMember->getIsPre());
    $this->forward404If($this->communityMember->hasPosition(array('admin', 'admin_confirm', 'sub_admin', 'sub_admin_confirm')));

    $this->form = new opChangeCommunityAdminRequestForm();
    if ($request->hasParameter('admin_request'))
    {
      $this->form->bind($request->getParameter('admin_request'));
      if ($this->form->isValid())
      {
        Doctrine::getTable('CommunityMember')->requestSubAdmin($this->member->getId(), $this->id);
        $this->redirect('@community_memberManage?id='.$this->id);
      }
    }

    return sfView::INPUT;
  }

  public function executeRemoveSubAdmin(opWebRequest $request)
  {
    $this->forward404Unless($this->isAdmin);

    $this->member = Doctrine::getTable('Member')->find($request->getParameter('member_id'));
    $this->forward404Unless($this->member);

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->communityMember = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($this->member->getId(), $this->id);

    $this->forward404If($this->communityMember->getIsPre());
    $this->forward404If(!$this->communityMember->hasPosition('sub_admin'));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $this->communityMember->removePosition('sub_admin');
      $this->redirect('@community_memberManage?id='.$this->id);
    }

    return sfView::INPUT;
  }

 /**
  * Executes dropMember action
  *
  * @param sfRequest $request A request object
  */
  public function executeDropMember(opWebRequest $request)
  {
    $this->redirectUnless($this->isAdmin || $this->isSubAdmin, '@error');
    $member = Doctrine::getTable('Member')->find($request->getParameter('member_id'));
    $this->forward404Unless($member);

    $isCommunityMember = Doctrine::getTable('CommunityMember')->isMember($member->getId(), $this->id);
    $this->redirectUnless($isCommunityMember, '@error');
    $isAdmin = Doctrine::getTable('CommunityMember')->isAdmin($member->getId(), $this->id);
    $isSubAdmin = Doctrine::getTable('CommunityMember')->isSubAdmin($member->getId(), $this->id);
    $this->redirectIf($isAdmin || $isSubAdmin, '@error');

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      Doctrine::getTable('CommunityMember')->quit($member->getId(), $this->id);
      $this->redirect('@community_memberManage?id='.$this->id);
    }

    $this->member    = $member;
    $this->community = Doctrine::getTable('Community')->find($this->id);
    return sfView::INPUT;
  }

  public static function sendJoinMail($memberId, $communityId)
  {
    $communityMember = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      return false;
    }

    if (!$communityMember->getIsPre())
    {
      $community = Doctrine::getTable('community')->find($communityId);
      $member = Doctrine::getTable('Member')->find($memberId);
      $params = array(
        'subject'    => sfContext::getInstance()->getI18N()->__('%1% has just joined your %community%', array('%1%' => $member->name)),
        'admin'      => $community->getAdminMember(),
        'community'  => $community,
        'new_member' => $member,
      );

      $isSendPc     = $community->getConfig('is_send_pc_joinCommunity_mail');
      $isSendMobile = $community->getConfig('is_send_mobile_joinCommunity_mail');

      $options = array(
        'is_send_pc'     => (bool)(null === $isSendPc ? 1 : $isSendPc),
        'is_send_mobile' => (bool)(null === $isSendMobile ? 1 : $isSendMobile)
      );

      opMailSend::sendTemplateMailToMember('joinCommunity', $community->getAdminMember(), $params, $options);
    }
  }
}
