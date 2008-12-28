<?php

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
    $this->widgetConfig = sfConfig::get('op_widget_list');
    $layout = SnsConfigPeer::retrieveByName('home_layout');
    if ($layout)
    {
      $this->setLayout($layout->getValue());
    }

    if (!$layout || $layout->getValue() === 'layoutA')
    {
      $this->topWidgets = HomeWidgetPeer::retrieveTopWidgets();
    }
    if (!$layout || $layout->getValue() === 'layoutA' || $layout->getValue() === 'layoutB')
    {
      $this->sideMenuWidgets = HomeWidgetPeer::retrieveSideMenuWidgets();
    }
    $this->contentsWidgets = HomeWidgetPeer::retrieveContentsWidgets();

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
    $this->pager->setCriteria($this->filters->getCriteria());
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
    if ($request->hasParameter('id') && $request->getParameter('id') != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_navi_type', 'friend');
    }

    $result = parent::executeProfile($request);
    $this->communities = CommunityPeer::retrievesByMemberId($this->getUser()->getMemberId(), 9);
    return $result;
  }

 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig($request)
  {
    $this->categories = sfConfig::get('openpne_member_category');
    $this->categoryName = $request->getParameter('category', 'general');
    $this->forward404Unless(array_key_exists($this->categoryName, $this->categories), 'Undefined category');

    $formClass = 'MemberConfig'.ucfirst($this->categoryName).'Form';
    $this->form = new $formClass($this->getUser()->getMember());

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
    $this->form = new InviteForm();
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

    return sfView::INPUT;
  }

 /**
  * Executes configImage action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigImage($request)
  {
    if ($request->isXmlHttpRequest())
    {
      $this->setLayout('plain');
    }

    $options = array('member' => $this->getUser()->getMember());
    $this->form = new MemberImageForm(array(), $options);

    if ($request->isMethod('post'))
    {
      $this->form->bindAndSave($request->getParameter('member_image'), $request->getFiles('member_image'));
      $this->redirect('member/configImage');
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
}
