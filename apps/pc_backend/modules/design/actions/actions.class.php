<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * design actions.
 *
 * @package    OpenPNE
 * @subpackage design
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class designActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('design', 'homeLayout');
  }

 /**
  * Executes home layout action
  *
  * @param sfRequest $request A request object
  */
  public function executeHomeLayout(sfWebRequest $request)
  {
    $this->form = new PickHomeLayoutForm();

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('pick_home_layout'));
      $this->redirectIf($this->form->save(), 'design/homeLayout');
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes gadget action
  *
  * @param sfRequest $request A request object
  */
  public function executeGadget(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type', 'home');
    switch ($this->type)
    {
      case 'mobileHome':
        $this->gadgets = array(
          'mobileTop'      => GadgetPeer::retrieveMobileTopGadgets(),
          'mobileContents' => GadgetPeer::retrieveMobileContentsGadgets(),
          'mobileBottom'   => GadgetPeer::retrieveMobileBottomGadgets(),
        );
        break;
      case 'sideBanner':
        $this->gadgets = array(
          'sideBannerContents' => GadgetPeer::retrieveSideBannerContentsGadgets(),
        );
        break;
      default:
        $this->gadgets = array(
          'top'      => GadgetPeer::retrieveTopGadgets(),
          'sideMenu' => GadgetPeer::retrieveSideMenuGadgets(),
          'contents' => GadgetPeer::retrieveContentsGadgets(),
          'bottom'   => GadgetPeer::retrieveBottomGadgets(),
        );
    }

    $this->sortForm = new GadgetSortForm(array(), array('current_gadgets' => $this->gadgets));
    $this->addForm = new GadgetAddForm(array(), array('current_gadgets' => $this->gadgets));
    if ($request->isMethod(sfRequest::POST))
    {
      $this->sortForm->bind($request->getParameter('gadget'));
      $this->addForm->bind($request->getParameter('new'));
      if ($this->sortForm->isValid() && $this->addForm->isValid())
      {
        $this->sortForm->save();
        $this->addForm->save();
        $this->redirect('design/gadget?type='.$this->type);
      }
    }
  }

 /**
  * Executes home gadget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeHomeGadgetPlot(sfWebRequest $request)
  {
    $this->layoutPattern = 'layoutA';
    $this->topGadgets = (array)GadgetPeer::retrieveTopGadgets();
    $this->sideMenuGadgets = (array)GadgetPeer::retrieveSideMenuGadgets();
    $this->contentsGadgets = (array)GadgetPeer::retrieveContentsGadgets();
    $this->bottomGadgets = (array)GadgetPeer::retrieveBottomGadgets();
    $this->gadgetConfig = sfConfig::get('op_gadget_list');

    $layout = SnsConfigPeer::retrieveByName('home_layout');
    if ($layout)
    {
      $this->layoutPattern = $layout->getValue();
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes add gadget action
  *
  * @param sfRequest $request A request object
  */
  public function executeAddGadget(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type','top');
    $this->config = GadgetPeer::getGadgetConfigListByType($this->type);

    return sfView::SUCCESS;
  }

 /**
  * Executes home edit gadget action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditGadget(sfWebRequest $request)
  {
    $this->gadget = GadgetPeer::retrieveByPK($request->getParameter('id'));

    $type = $this->gadget->getType();
    $config = GadgetPeer::getGadgetConfigListByType($type);

    $this->forward404Unless($this->gadget && $config);
    $this->config = $config[$this->gadget->getName()];

    if (!empty($this->config['config']))
    {
      $this->form = new GadgetConfigForm($this->gadget);

      if ($request->isMethod(sfRequest::POST))
      {
        $this->form->bind($request->getParameter('gadget_config'));
        if ($this->form->isValid())
        {
          $this->form->save();
          $this->redirect('design/editGadget?id='.$this->gadget->getId());
        }
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes mobile home gadget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeMobileHomeGadgetPlot(sfWebRequest $request)
  {
    $this->mobileTopGadgets = (array)GadgetPeer::retrieveMobileTopGadgets();
    $this->mobileContentsGadgets = (array)GadgetPeer::retrieveMobileContentsGadgets();
    $this->mobileBottomGadgets = (array)GadgetPeer::retrieveMobileBottomGadgets();
    $this->gadgetConfig = sfConfig::get('op_mobile_gadget_list');

    return sfView::SUCCESS;
  }

 /**
  * Executes side banner home gadget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeSideBannerGadgetPlot(sfWebRequest $request)
  {
    $this->sideBannerContentsGadgets = (array)GadgetPeer::retrieveSideBannerContentsGadgets();
    $this->gadgetConfig = sfConfig::get('op_side_banner_gadget_list');

    return sfView::SUCCESS;
  }

 /**
  * Execute navigation action
  *
  * @param sfRequest $request A request object
  */
  public function executeNavigation(sfWebRequest $request)
  {
    $this->app = $request->getParameter('app', 'pc');
    $isMobile = (bool)('mobile' === $this->app);

    $this->list = array();

    $types = NavigationPeer::retrieveTypes($isMobile);

    foreach ($types as $type)
    {
      $navs = NavigationPeer::retrieveByType($type);
      foreach ($navs as $nav)
      {
        $this->list[$type][] = new NavigationForm($nav);
      }
      $this->list[$type][] = new NavigationForm();
    }
  }

 /**
  * Execute navigationEdit action
  *
  * @param sfRequest $request A request object
  */
  public function executeNavigationEdit(sfWebRequest $request)
  {
    $nav = $request->getParameter('nav');
    $app = $request->getParameter('app', 'pc');

    $model = NavigationPeer::retrieveByPk($nav['id']);
    $this->form = new NavigationForm($model);
    if ($request->isMethod('post'))
    {
       $this->form->bind($nav);
       if ($this->form->isValid())
       {
         $this->form->save();
       }
    }

    $this->redirect('design/navigation?app='.$app);
  }

 /**
  * Execute navigationDelete action
  *
  * @param sfRequest $request A request object
  */
  public function executeNavigationDelete(sfWebRequest $request)
  {
    $app = $request->getParameter('app', 'pc');

    if ($request->isMethod('post'))
    {
      $model = NavigationPeer::retrieveByPk($request->getParameter('id'));
      $this->forward404Unless($model);
      $model->delete();
    }

    $this->redirect('design/navigation?app='.$app);
  }

 /**
  * Execute navigationSort action
  *
  * @param sfRequest $request A request object
  */
  public function executeNavigationSort(sfWebRequest $request)
  {
    if (!$request->isXmlHttpRequest())
    {
      $this->forward404();
    }

    $parameters = $request->getParameterHolder();
    $keys = $parameters->getNames();
    foreach ($keys as $key)
    {
      if (strpos($key, 'type_') === 0)
      {
        $order = $parameters->get($key);
        for ($i = 0; $i < count($order); $i++)
        {
          $nav = NavigationPeer::retrieveByPk($order[$i]);
          if ($nav)
          {
            $nav->setSortOrder($i * 10);
            $nav->save();
          }
        }
        break;
      }
    }
    return sfView::NONE;
  }
}
