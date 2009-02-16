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
    $this->gadgetConfig = sfConfig::get('op_gadget_list');
    $layout = SnsConfigPeer::retrieveByName('home_layout');
    if ($layout)
    {
      $this->setLayout($layout->getValue());
    }

    if (!$layout || $layout->getValue() === 'layoutA')
    {
      $this->topGadgets = GadgetPeer::retrieveTopGadgets();
    }
    if (!$layout || $layout->getValue() === 'layoutA' || $layout->getValue() === 'layoutB')
    {
      $this->sideMenuGadgets = GadgetPeer::retrieveSideMenuGadgets();
    }
    $this->contentsGadgets = GadgetPeer::retrieveContentsGadgets();
    $this->bottomGadgets = GadgetPeer::retrieveBottomGadgets();

    return parent::executeHome($request);
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
      $params['name']['text'] = $request->getParameter('search_query');
    }

    $this->filters = new MemberFormFilter();
    $this->filters->bind($params);

    $this->pager = new sfPropelPager('Member', 20);
    $c = $this->filters->getCriteria();
    $c->addDescendingOrderByColumn(MemberPeer::ID);
    $this->pager->setCriteria($c);
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
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    if ($id != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
    }

    $result = parent::executeProfile($request);

    $this->communities = CommunityPeer::retrievesByMemberId($id, 9);

    return $result;
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
            $this->getUser()->setFlash('error', $errors['file']);
          }
        }
      }
      catch (opRuntimeException $e)
      {
        $this->getUser()->setFlash('error', $e->getMessage());
      }
    }
  }

 /**
  * Executes deleteImage action
  *
  * @param sfRequest $request A request object
  */
  public function executeDeleteImage($request)
  {
    $image = MemberImagePeer::retrieveByPk($request->getParameter('member_image_id'));
    $this->forward404Unless($image);
    $this->forward404Unless($image->getMemberId() == $this->getUser()->getMemberId());

    $image->delete();

    $this->redirect('member/configImage');
  }

 /**
  * Executes changeMainImage action
  *
  * @param sfRequest $request A request object
  */
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

 /**
  * Executes registerMobileToRegisterEnd action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegisterMobileToRegisterEnd(sfWebRequest $request)
  {
    $this->form = new registerMobileForm($this->getUser()->getMember());
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('member/registerMobileToRegisterEnd');
      }
    }

    return sfView::SUCCESS;
  }

  /**
   * Executes changeLanguage action
   *
   * @param sfRequest $request a request object
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
