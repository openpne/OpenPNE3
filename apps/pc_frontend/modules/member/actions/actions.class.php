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
class memberActions extends opMemberAction
{
 /**
  * Executes home action
  *
  * @param opWebRequest $request A request object
  */
  public function executeHome(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtHome');

    $this->topGadgets = null;
    $this->sideMenuGadgets = null;

    $this->gadgetConfig = sfConfig::get('op_gadget_list');

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('gadget');
    $layout = Doctrine::getTable('SnsConfig')->get('home_layout', 'layoutA');
    $this->setLayout($layout);

    switch ($layout)
    {
      case 'layoutA' :
        $this->topGadgets = $gadgets['top'];
      case 'layoutB' :
        $this->sideMenuGadgets = $gadgets['sideMenu'];
    }

    $this->contentsGadgets = $gadgets['contents'];
    $this->bottomGadgets = $gadgets['bottom'];

    return parent::executeHome($request);
  }

 /**
  * Execute smtHome action
  *
  * @param opWebRequest $request A request object
  */
  public function executeSmtHome(opWebRequest $request)
  {
    $this->gadgetConfig = sfConfig::get('op_smartphone_gadget_list');

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphone');
    $this->contentsGadgets = $gadgets['smartphoneContents'];

    return sfView::SUCCESS;
  }

 /**
  * Executes login action
  *
  * @param opWevRequest $request A request object
  */
  public function executeLogin(opWebRequest $request)
  {
    if (opConfig::get('external_pc_login_url') && $request->isMethod(sfWebRequest::GET))
    {
      $this->redirect(opConfig::get('external_pc_login_url'));
    }

    if ($request->isSmartphone())
    {
      $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphoneLogin');
      $this->contentsGadgets = $gadgets['smartphoneLoginContents'];

      $this->setLayout('smtLayoutSns');
      $this->setTemplate('smtLogin');    
    }
    else
    {
      $this->gadgetConfig = sfConfig::get('op_login_gadget_list');
      $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('login');
      $layout = Doctrine::getTable('SnsConfig')->get('login_layout', 'layoutA');
      $this->setLayout($layout);

      switch($layout)
      {
        case 'layoutA' :
          $this->topGadgets = $gadgets['loginTop'];
        case 'layoutB' :
          $this->sideMenuGadgets = $gadgets['loginSideMenu'];
      }

      $this->contentsGadgets = $gadgets['loginContents'];
      $this->bottomGadgets = $gadgets['loginBottom'];
    }

    return parent::executeLogin($request);
  }


 /**
  * Executes profile action
  *
  * @param opWebRequest $request A request object
  */
  public function executeProfile(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtProfile');

    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    if ($id != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
    }

    $this->gadgetConfig = sfConfig::get('op_profile_gadget_list');

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('profile');
    $layout = Doctrine::getTable('SnsConfig')->get('profile_layout', 'layoutA');
    $this->setLayout($layout);

    switch ($layout)
    {
      case 'layoutA' :
        $this->topGadgets = $gadgets['profileTop'];
      case 'layoutB' :
        $this->sideMenuGadgets = $gadgets['profileSideMenu'];
    }
    $this->contentsGadgets = $gadgets['profileContents'];
    $this->bottomGadgets = $gadgets['profileBottom'];

    return parent::executeProfile($request);
  }

 /**
  * Executes smtProfile action
  *
  * @param opWebRequest $request A request object
  */
  public function executeSmtProfile(opWebRequest $request)
  {
    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphoneProfile');
    $this->contentsGadgets = $gadgets['smartphoneProfileContents'];

    $result = parent::executeProfile($request);

    opSmartphoneLayoutUtil::setLayoutParameters(array('member' => $this->member));

    return $result;
  }

 /**
  * Executes configImage action
  *
  * @param opWebRequest $request A request object
  */
  public function executeConfigImage(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtConfigImage');

    $options = array('member' => $this->getUser()->getMember());
    $this->form = new MemberImageForm(array(), $options);

    if ($request->isMethod(sfWebRequest::POST))
    {
      try
      {
        if (!$this->form->bindAndSave($request->getParameter('member_image'), $request->getFiles('member_image')))
        {
          $errors = $this->form->getErrorSchema()->getErrors();
          if (isset($errors['file']))
          {
            $error = $errors['file'];
            $i18n = $this->getContext()->getI18N();
            $this->getUser()->setFlash('error', $i18n->__($error->getMessageFormat(), $error->getArguments()));
          }
        }
      }
      catch (opRuntimeException $e)
      {
        $this->getUser()->setFlash('error', $e->getMessage());
      }
      $this->redirect('@member_config_image');
    }

  }


 /**
  * Executes smtCofigImage action
  *
  * @param opWebRequest $request A request object
  */

  public function executeSmtConfigImage(opWebRequest $request)
  {
    $options = array('member' => $this->getUser()->getMember());
    $this->form = new MemberImageForm(array(), $options);

    if ($request->isMethod(sfWebRequest::POST))
    {
      try
      {
        if (!$this->form->bindAndSave($request->getParameter('member_image'), $request->getFiles('member_image')))
        {
          $errors = $this->form->getErrorSchema()->getErrors();
          if (isset($errors['file']))
          {
            $error = $errors['file'];
            $i18n = $this->getContext()->getI18N();
            $this->getUser()->setFlash('error', $i18n->__($error->getMessageFormat(), $error->getArguments()));
          }
        }
      }
      catch (opRuntimeException $e)
      {
        $this->getUser()->setFlash('error', $e->getMessage());
      }
      $this->redirect('@member_config_image');
    }

    return parent::executeConfigImage($request);
  }

 /**
  * Executes configJsonApi action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigJsonApi(opWebRequest $request)
  {
    $this->forward404Unless(opConfig::get('enable_jsonapi'));

    $member = $this->getUser()->getMember();

    if (isset($request['reset_api_key']) && '1' === $request['reset_api_key'])
    {
      $request->checkCSRFProtection();
      $member->generateApiKey();
    }

    $this->apiKey = $member->getApiKey();

    return sfView::SUCCESS;
  }

 /**
  * Executes registerMobileToRegisterEnd action
  *
  * @param opWebRequest $request A request object
  */
  public function executeRegisterMobileToRegisterEnd(opWebRequest $request)
  {
    opActivateBehavior::disable();
    $this->form = new registerMobileForm($this->getUser()->getMember());
    opActivateBehavior::enable();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('member/registerMobileToRegisterEndFinish');
      }
    }

    return sfView::SUCCESS;
  }

  public function executeRegisterMobileToRegisterEndFinish(opWebRequest $request)
  {
  }

  /**
   * Executes changeLanguage action
   *
   * @param opWebRequest $request a request object
   */
  public function executeChangeLanguage(opWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $form = new opLanguageSelecterForm();
      if ($form->bindAndSetCulture($request->getParameter('language')))
      {
        $this->redirect($form->getValue('next_uri'));
      }
    }
    $this->redirect('@homepage');
  }


 /**
  * Executes editConfig action
  *
  * @param opWebRequest $request a request object
  */
  public function executeConfig(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtConfig');

    return parent::executeConfig($request);
  }

 /**
  * Executes editSmtConfig action
  *
  * @param opWebRequest $request a request object
  */
  public function executeSmtConfig(opWebRequest $request)
  {
    return parent::executeConfig($request);
  }

 /**
  * Executes search action
  *
  * @param opWebRequest $request a request object
  */
  public function executeSearch(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtSearch');

    return parent::executeSearch($request);
  }

 /**
  * Executes smtSearch action
  *
  * @param opWebRequest $request a request object
  */
  public function executeSmtSearch(opWebRequest $request)
  {
    return sfView::SUCCESS;
  }

 /**
  * Executes invite action
  *
  * @param opWebRequest $request a request object
  */
  public function executeInvite(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtInvite');

    return parent::executeInvite($request);
  }


 /**
  * Executes editSmtConfig action
  *
  * @param opWebRequest $request a request object
  */
  public function executeSmtInvite(opWebRequest $request)
  {
    return parent::executeInvite($request);
  }


 /**
  * Executes editProfile action
  *
  * @param opWebRequest $request a request object
  */
  public function executeEditProfile(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtEditProfile');

    return parent::executeEditProfile($request);
  }

 /**
  * Executes smtEditProfile action
  *
  * @param opWebRequest $request a request object
  */
  public function executeSmtEditProfile(opWebRequest $request)
  {
    return parent::executeEditProfile($request);
  }
}
