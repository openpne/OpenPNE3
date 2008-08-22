<?php

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
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

    $this->form = $this->getUser()->getAuthForm();

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('auth'));
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
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig($request)
  {
    $this->forms = array();
    $categories = array('security');

    foreach ($categories as $category) {
      $this->forms[$category] = new MemberConfigForm();
      $this->forms[$category]->setConfigWidgets($category, $this->getUser()->getMemberId());
    }

    if ($request->isMethod('post')) {
      $targetForm = $this->forms[$request->getParameter('category')];
      $targetForm->bind($request->getParameter('member_config'));
      if ($targetForm->isValid()) {
        $targetForm->save($this->getUser()->getMemberId());
        $this->redirect('member/config');
      }
    }

    return sfView::SUCCESS;
  }
}
