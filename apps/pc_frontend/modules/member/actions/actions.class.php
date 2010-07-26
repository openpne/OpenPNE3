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
class memberActions extends sfOpenPNEMemberAction
{
 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
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
  * Executes login action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin($request)
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

    return parent::executeLogin($request);
  }

 /**
  * Executes profile action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfile($request)
  {
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
  * Executes configImage action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigImage($request)
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
      $this->redirect('member/configImage');
    }
  }

 /**
  * Executes registerMobileToRegisterEnd action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegisterMobileToRegisterEnd(sfWebRequest $request)
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

  public function executeRegisterMobileToRegisterEndFinish(sfWebRequest $request)
  {
  }

  /**
   * Executes changeLanguage action
   *
   * @param sfWebRequest $request a request object
   */
  public function executeChangeLanguage(sfWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $form = new opLanguageSelecterForm();
      $form->bind($request->getParameter('language'));
      if ($form->isValid())
      {
        $form->setCulture();
        $this->redirect($form->getValue('next_uri'));
      }
    }
    $this->redirect('@homepage');
  }
}
