<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfOpenPNECommunityAction
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class sfOpenPNECommunityAction extends sfActions
{
  public function preExecute()
  {
    $this->id = $this->getRequestParameter('id');

    $memberId = $this->getUser()->getMemberId();
    $this->isCommunityMember = Doctrine::getTable('CommunityMember')->isMember($memberId, $this->id);
    $this->isCommunityPreMember = Doctrine::getTable('CommunityMember')->isPreMember($memberId, $this->id);
    $this->isAdmin = Doctrine::getTable('CommunityMember')->isAdmin($memberId, $this->id);
    $this->isEditCommunity = $this->isAdmin;
  }

 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community, 'Undefined community.');
    $this->community_admin = $this->community->getAdminMember();

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
  public function executeEdit($request)
  {
    if ($this->id && !$this->isEditCommunity)
    {
      $this->forward('default', 'secure');
    }

    $this->community = Doctrine::getTable('Community')->find($this->id);
    if (!$this->community)
    {
      $this->community = new Community();
    }
    else
    {
      if ($request->isMethod('post') && $request->hasParameter('is_delete'))
      {
        $this->redirect('community/delete');
      }
    }

    $this->communityForm       = new CommunityForm($this->community);
    $this->communityConfigForm = new CommunityConfigForm(array(), array('community' => $this->community));
    $this->communityFileForm = isset($this->enableImage) && $this->enableImage ?
      new CommunityFileForm(array(), array('community' => $this->community)) :
      new CommunityFileForm();

    if ($request->isMethod('post'))
    {
      $params = $request->getParameter('community');
      $params['id'] = $this->id;
      $this->communityForm->bind($params);
      $this->communityConfigForm->bind($request->getParameter('community_config'));
      $this->communityFileForm->bind($request->getParameter('community_file'), $request->getFiles('community_file'));
      if ($this->communityForm->isValid() && $this->communityConfigForm->isValid() && $this->communityFileForm->isValid())
      {
        $this->communityForm->save();
        $this->communityConfigForm->save();
        $this->communityFileForm->save();

        $this->redirect('community/home?id='.$this->community->getId());
      }
    }
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    if ($this->id && !$this->isEditCommunity)
    {
      $this->forward('default', 'secure');
    }

    if ($request->isMethod('post'))
    {
      if($request->hasParameter('is_delete'))
      {
        $community = Doctrine::getTable('Community')->find($this->id);
        if ($community)
        {
          $community->delete();
        }
        $this->redirect('community/search');
      }
      else
      {
        $this->redirect('community/home?id=' . $this->id);
      }
    }
    $this->community = Doctrine::getTable('Community')->find($this->id);
  }

 /**
  * Executes joinlist action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoinlist($request)
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
  public function executeMemberList($request)
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
  public function executeJoin($request)
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
        Doctrine::getTable('CommunityMember')->join($this->getUser()->getMemberId(), $this->id, $this->community->getConfig('register_poricy'));
        self::sendJoinMail($this->getUser()->getMemberId(), $this->id);

        if ('close' !== $this->community->getConfig('register_poricy'))
        {
          $this->getUser()->setFlash('notice', 'You have just joined to this %community%.');
        }

        $this->redirect('community/home?id='.$this->id);
      }
    }

    return sfView::INPUT;
  }

 /**
  * Executes quit action
  *
  * @param sfRequest $request A request object
  */
  public function executeQuit($request)
  {
    if (!$this->isCommunityMember || $this->isAdmin)
    {
      return sfView::ERROR;
    }

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      Doctrine::getTable('CommunityMember')->quit($this->getUser()->getMemberId(), $this->id);
      $this->getUser()->setFlash('notice', 'You have just quitted this %community%.');
      $this->redirect('community/home?id='.$this->id);
    }
  }

 /**
  * Executes memberManage action
  *
  * @param sfRequest $request A request object
  */
  public function executeMemberManage($request)
  {
    $this->redirectUnless($this->isAdmin, '@error');

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->pager = Doctrine::getTable('Community')->getCommunityMemberListPager($this->id, $request->getParameter('page', 1));

    if (!$this->pager->getNbResults())
    {
      return sfView::ERROR;
    }

    $this->changeAdminRequestMember = $this->community->getChangeAdminRequestMember();
  }

 /**
  * Executes changeAdminRequest action
  *
  * @param sfRequest $request A request object
  */
  public function executeChangeAdminRequest($request)
  {
    $this->forward404Unless($this->isAdmin);

    $this->member = Doctrine::getTable('Member')->find($request->getParameter('member_id'));
    $this->forward404Unless($this->member);

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->communityMember = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($this->member->getId(), $this->id);

    $this->forward404If($this->communityMember->getPosition());

    $this->form = new opChangeCommunityAdminRequestForm();
    if ($request->hasParameter('admin_request'))
    {
      $this->form->bind($request->getParameter('admin_request'));
      if ($this->form->isValid())
      {
        Doctrine::getTable('CommunityMember')->requestChangeAdmin($this->member->getId(), $this->id);
        $this->redirect('community/memberManage?id='.$this->id);
      }
    }

    return sfView::INPUT;
  }

 /**
  * Executes dropMember action
  *
  * @param sfRequest $request A request object
  */
  public function executeDropMember($request)
  {
    $this->redirectUnless($this->isAdmin, '@error');
    $member = Doctrine::getTable('Member')->find($request->getParameter('member_id'));
    $this->forward404Unless($member);

    $isCommunityMember = Doctrine::getTable('CommunityMember')->isMember($member->getId(), $this->id);
    $this->redirectUnless($isCommunityMember, '@error');
    $isAdmin = Doctrine::getTable('CommunityMember')->isAdmin($member->getId(), $this->id);
    $this->redirectIf($isAdmin, '@error');

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      Doctrine::getTable('CommunityMember')->quit($member->getId(), $this->id);
      $this->redirect('community/memberManage?id='.$this->id);
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

    if ($communityMember->getPosition() !== 'pre')
    {
      $community = Doctrine::getTable('community')->find($communityId);
      $member = Doctrine::getTable('Member')->find($memberId);
      $params = array(
        'subject'    => sfContext::getInstance()->getI18N()->__('%1% has just joined your %community%', array('%1%' => $member->name)),
        'admin'      => $community->getAdminMember(),
        'community'  => $community,
        'new_member' => $member,
      );
      sfOpenPNEMailSend::sendTemplateMail('joinCommunity', $community->getAdminMember()->getEmailAddress(), opConfig::get('admin_mail_address'), $params);
    }
  }
}
