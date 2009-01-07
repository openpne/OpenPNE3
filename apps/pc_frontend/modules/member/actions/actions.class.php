<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
