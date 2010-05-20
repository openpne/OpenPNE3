<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class memberActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('member', 'list');
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    $params = $request->getParameter('member', array());

    $this->form = new opMemberProfileSearchForm(array(), array('use_id' => true, 'is_check_public_flag' => false));
    $this->form->getWidgetSchema()->setLabel('name', 'Nickname');
    $this->form->bind($params);

    $this->pager = new sfDoctrinePager('Member', 20);
    if ($params)
    {
      $this->pager->setQuery($this->form->getQuery());
    }
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->profiles = Doctrine::getTable('Profile')->retrievesAll();

    return sfView::SUCCESS;
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    if ($id == 1)
    {
      return sfView::ERROR;
    }

    $this->member = Doctrine::getTable('Member')->find($id);
    $this->forward404Unless($this->member);

    $this->form = new sfForm();
    if ($request->isMethod('post'))
    {
      $this->member->delete();
      $this->getUser()->setFlash('notice', sfContext::getInstance()->getI18N()->__('The member has been unsubscribed'));
      $this->redirect('member/list');
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes invite action
  *
  * @param sfRequest $request A request object
  */
  public function executeInvite(sfWebRequest $request)
  {
    $this->plugins = opInstalledPluginManager::getAdminInviteAuthPlugins();
    if (empty($this->plugins))
    {
      return sfView::ERROR;
    }

    $options = array(
      'authModes' => $this->plugins,
      'is_link' => false,
    );
    $this->form = new AdminInviteForm(null, $options);

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('member/invite');
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes blacklist action
  *
  * @param sfRequest $request A request object
  */
  public function executeBlacklist(sfWebRequest $request)
  {
    $uid = $request->getParameter('uid');

    $this->pager = new sfDoctrinePager('Blacklist', 20);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->form = new BlacklistForm();
    if ($uid)
    {
      $this->form->setDefault('uid', $uid);
    }
    if ($request->isMethod(sfWebRequest::POST))
    {
      $result = $this->form->bindAndSave($request->getParameter('blacklist'));
      $this->redirectIf($result, 'member/blacklist');
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes blacklistDelete action
  *
  * @param sfRequest $request A request object
  */
  public function executeBlacklistDelete(sfWebRequest $request)
  {
    $this->blacklist = Doctrine::getTable('Blacklist')->find($request->getParameter('id'));
    $this->forward404Unless($this->blacklist);

    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $field = sfForm::getCSRFFieldName();
      $this->form->bind(array($field => $request->getParameter($field)));
      if ($this->form->isValid())
      {
        $this->blacklist->delete();
        $this->redirect('member/blacklist');
      }
    }

    return sfView::SUCCESS;
  }

  /**
   * Executes reissuePassword action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeReissuePassword(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->member = Doctrine::getTable('Member')->find($id);
    $this->forward404Unless($this->member);

    $this->form = new ReissuePasswordForm($this->member);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save();
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes reject action
  *
  * @param sfRequest $request A request object
  */
  public function executeReject(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->member = Doctrine::getTable('Member')->find($id);
    $this->forward404Unless($this->member);

    $this->form = new RejectMemberForm($this->member);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('is_login_rejected'));
      if ($this->form->isValid())
      {
        $this->form->save();
        if ($this->member->getIsLoginRejected())
        {
          $message = sfContext::getInstance()->getI18N()->__('The member has been banned');
        }
        else
        {
          $message = sfContext::getInstance()->getI18N()->__('The ban has been removed');
        }
        $this->getUser()->setFlash('notice', $message);

        $this->redirect('member/list');
      }
    }

    return sfView::SUCCESS;
  }
}
