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
    $this->isCommunityMember = CommunityMemberPeer::isMember($memberId, $this->id);
    $this->isCommunityPreMember = CommunityMemberPeer::isPreMember($memberId, $this->id);
    $this->isAdmin = CommunityMemberPeer::isAdmin($memberId, $this->id);
    $this->isEditCommunity = $this->isAdmin;
  }

 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->community = CommunityPeer::retrieveByPk($this->id);
    $this->forward404Unless($this->community, 'Undefined community.');
    $this->community_admin = CommunityMemberPeer::getCommunityAdmin($this->id);
    $this->community_admin = MemberPeer::retrieveByPk($this->community_admin->getMemberId());

    if (!$this->membersSize)
    {
      $this->membersSize = 9;
    }
    $c = new Criteria();
    $c->addAscendingOrderByColumn(Propel::getDB()->random(time()));
    $this->members = $this->community->getMembers($this->membersSize, $c);
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

    $this->community = CommunityPeer::retrieveByPk($this->id);
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
      if ($request->hasParameter('is_delete'))
      {
        $request->checkCSRFProtection();
        $community = CommunityPeer::retrieveByPk($this->id);
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
    $this->community = CommunityPeer::retrieveByPk($this->id);
  }


 /**
  * Executes joinlist action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoinlist($request)
  {
    $memberId = $request->getParameter('id', $this->getUser()->getMemberId());

    $this->member = MemberPeer::retrieveByPK($memberId);
    $this->forward404Unless($this->member);

    if (!$this->size)
    {
      $this->size = 20;
    }

    $this->pager = CommunityPeer::getJoinCommunityListPager($memberId, $request->getParameter('page', 1), $this->size);

    if (!$this->pager->getNbResults())
    {
      return sfView::ERROR;
    }

    $this->crownIds = CommunityMemberPeer::getCommunityIdsOfAdminByMemberId($memberId);

    return sfView::SUCCESS;
  }

 /**
  * Executes memberList action
  *
  * @param sfRequest $request A request object
  */
  public function executeMemberList($request)
  {
    $this->community = CommunityPeer::retrieveByPk($this->id);
    $this->forward404Unless($this->community);

    if (!$this->size)
    {
      $this->size = 20;
    }
    $this->pager = CommunityPeer::getCommunityMemberListPager($this->id, $request->getParameter('page', 1), $this->size);

    if (!$this->pager->getNbResults()) {
      return sfView::ERROR;
    }
    
    $this->crownIds = array(CommunityMemberPeer::getCommunityAdmin($this->id)->getMemberId());
    
    return sfView::SUCCESS;
  }

 /**
  * Executes join action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoin($request)
  {
    $this->community = CommunityPeer::retrieveByPk($this->id);
    $this->forward404Unless($this->community);

    if ($this->isCommunityMember || $this->isCommunityPreMember)
    {
      return sfView::ERROR;
    }

    $this->form = new opCommunityJoiningForm();
    if ('close' !== $this->community->getConfig('register_poricy'))
    {
      unset($this->form['message']);
    }

    if ($request->hasParameter('community_join'))
    {
      $this->form->bind($request->getParameter('community_join'));
      if ($this->form->isValid())
      {
        CommunityMemberPeer::join($this->getUser()->getMemberId(), $this->id, $this->community->getConfig('register_poricy'));

        if ('close' !== $this->community->getConfig('register_poricy'))
        {
          $this->getUser()->setFlash('notice', 'You have just joined to this community.');
        }

        $this->redirect('community/home?id='.$this->id);
      }
    }

    return sfView::INPUT;
  }

 /**
  * Executes joinAccept action
  *
  * @param sfRequest $request A request object
  */
  public function executeJoinAccept($request)
  {
    $request->checkCSRFProtection();
    $this->redirectUnless($this->isAdmin, '@error');

    $communityMember = CommunityMemberPeer::retrieveByMemberIdAndCommunityId($request->getParameter('member_id'), $this->id);
    $this->forward404Unless($communityMember);

    if ($communityMember->getPosition() == 'pre')
    {
      $communityMember->setPosition('');
      $communityMember->save();
    }

    $this->redirect('@member_index');
  }

  /**
   * Executes joinReject action
   *
   * @param sfRequest $request A request object
   */
  public function executeJoinReject($request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($this->isAdmin);

    $communityMember = CommunityMemberPeer::retrieveByMemberIdAndCommunityId($request->getParameter('member_id'), $this->id);
    $this->forward404Unless($communityMember);

    if ($communityMember->getPosition() == 'pre')
    {
      $communityMember->delete();
    }
    $this->redirect('community/home?id='.$this->id);   
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

    $this->community = CommunityPeer::retrieveByPk($this->id);
    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      CommunityMemberPeer::quit($this->getUser()->getMemberId(), $this->id);
      $this->getUser()->setFlash('notice', 'You have just quitted this community.');
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

    $this->community = CommunityPeer::retrieveByPk($this->id);
    $this->pager = CommunityPeer::getCommunityMemberListPager($this->id, $request->getParameter('page', 1));

    if (!$this->pager->getNbResults())
    {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes dropMember action
  *
  * @param sfRequest $request A request object
  */
  public function executeDropMember($request)
  {
    $this->redirectUnless($this->isAdmin, '@error');
    $member = MemberPeer::retrieveByPk($request->getParameter('member_id'));
    $this->forward404Unless($member);

    $isCommunityMember = CommunityMemberPeer::isMember($member->getId(), $this->id);
    $this->redirectUnless($isCommunityMember, '@error');
    $isAdmin = CommunityMemberPeer::isAdmin($member->getId(), $this->id);
    $this->redirectIf($isAdmin, '@error');

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      CommunityMemberPeer::quit($member->getId(), $this->id);
      $this->redirect('community/memberManage?id='.$this->id);
    }

    $this->member    = $member;
    $this->community = CommunityPeer::retrieveByPk($this->id);
    return sfView::INPUT;
  }
}
