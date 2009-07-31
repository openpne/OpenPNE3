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
    $this->forward('design', 'layout');
  }

 /**
  * Executes home layout action
  *
  * @param sfRequest $request A request object
  */
  public function executeLayout(sfWebRequest $request)
  {
    $option = array();

    $this->configs = array();
    $gadgetConfigs = sfConfig::get('op_gadget_config', array());
    foreach ($gadgetConfigs as $key => $config)
    {
      if (isset($config['layout']['choices']))
      {
        $this->configs[$key] = $config;
      }
    }

    $type = $request->getParameter('type', 'gadget');
    $this->forward404Unless(isset($this->configs[$type]));
    $this->subtitle = $this->configs[$type]['name'];

    $option['layout_name'] = $type;
    
    $this->form = new PickHomeLayoutForm(array(), $option);

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('pick_home_layout'));
      $this->redirectIf($this->form->save(), 'design/layout?type='.$type);
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
    $this->configs = sfConfig::get('op_gadget_config', array());
    $layouts = sfConfig::get('op_gadget_layout_config', array());
    $this->type = $request->getParameter('type', 'gadget');
    
    $this->forward404Unless(isset($this->configs[$this->type]));
    
    $this->subtitle = $this->configs[$this->type]['name'];
    $this->plotAction = $this->configs[$this->type]['plot_action'];

    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName($this->type);
    

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
    $configs = sfConfig::get('op_gadget_config');
    $this->layoutPattern = $configs['gadget']['layout']['default'];
    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName('gadget');
    $this->gadgetConfig = sfConfig::get('op_gadget_list');

    $layout = SnsConfigPeer::retrieveByName('home_layout');
    if ($layout)
    {
      $this->layoutPattern = $layout;
    }

    return sfView::SUCCESS;
  }

  /**
  * Executes profile gadget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfileGadgetPlot(sfWebRequest $request)
  {
    $configs = sfConfig::get('op_gadget_config');
    $this->layoutPattern = $configs['profile']['layout']['default'];
    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName('profile');
    $this->gadgetConfig = sfConfig::get('op_profile_gadget_list');

    $layout = SnsConfigPeer::retrieveByName('profile_layout');
    if ($layout)
    {
      $this->layoutPattern = $layout;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes login gadget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeLoginGadgetPlot(sfWebRequest $request)
  {
    $configs = sfConfig::get('op_gadget_config');
    $this->layoutPattern = $configs['login']['layout']['default'];
    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName('login');
    $this->gadgetConfig = sfConfig::get('op_login_gadget_list');

    $layout = SnsConfigPeer::retrieveByName('login_layout');
    if ($layout)
    {
      $this->layoutPattern = $layout->getValue();
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
    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName('mobile');
    $this->gadgetConfig = sfConfig::get('op_mobile_gadget_list');

    return sfView::SUCCESS;
  }

 /**
  * Executes mobile profile gadget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeMobileProfileGadgetPlot(sfWebRequest $request)
  {
    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName('mobileProfile');
    $this->gadgetConfig = sfConfig::get('op_mobile_profile_gadget_list');

    return sfView::SUCCESS;
  }

  /**
   * Executes mobile login gadget plot action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeMobileLoginGadgetPlot(sfWebRequest $request)
  {
    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName('mobileLogin');
    $this->gadgetConfig = sfConfig::get('op_mobile_login_gadget_list');
    
    return sfView::SUCCESS;
  }

 /**
  * Executes side banner home gadget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeSideBannerGadgetPlot(sfWebRequest $request)
  {
    $this->gadgets = GadgetPeer::retrieveGadgetsByTypesName('sideBanner');
    $this->gadgetConfig = sfConfig::get('op_side_banner_gadget_list');

    return sfView::SUCCESS;
  }

 /**
  * Executes add gadget action
  *
  * @param sfRequest $request A request object
  */
  public function executeAddGadget(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type', 'top');
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
      $this->form = new GadgetConfigForm($this->gadget, array('type' => $type));

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

 /**
  * Execute banner action
  *
  * @param sfRequest $request A request object
  */
  public function executeBanner(sfWebRequest $request)
  {
    $id = $request->getParameter('id', 0);
    if (!$id)
    {
      $this->banner = BannerPeer::retrieveTop();
    }
    else
    {
      $this->banner = BannerPeer::retrieveByPk($id);
    }
    if (!$this->banner)
    {
      return sfView::ERROR;
    }

    $this->form = new BannerForm($this->banner);
    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('banner'));
      if ($this->form->isValid())
      {
        $this->form->save();
      }
    }

    $this->bannerImageList = BannerImagePeer::retrievesAll();
    $this->bannerList = BannerPeer::retrievesAll();
  }

 /**
  * Execute banneradd action
  *
  * @param sfRequest $request A request object
  */
  public function executeBanneradd(sfWebRequest $request)
  {
    $this->form = new BannerImageForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('banner_image');
      $files = $request->getFiles('banner_image');
      $this->form->bind($params, $files);
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('design/banner');
      }
    }
  }

 /**
  * Execute banneredit action
  *
  * @param sfRequest $request A request object
  */
  public function executeBanneredit(sfWebRequest $request)
  {
    $banner = BannerImagePeer::retrieveByPk($request->getParameter('id', 0));
    if (!$banner)
    {
      return sfView::ERROR;
    }
    $this->form = new BannerImageForm($banner);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('banner_image');
      $files = $request->getFiles('banner_image');
      $this->form->bind($params, $files);
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('design/banner');
      }
      if (!isset($params['file']))
      {
        $banner->setName($params['name']);
        $banner->setUrl($params['url']);
        $banner->save();
        $this->redirect('design/banner');
      }
    }
  }

 /**
  * Execute bannerdelete action
  *
  * @param sfRequest $request A request object
  */
  public function executeBannerdelete(sfWebRequest $request)
  {
    $banner = BannerImagePeer::retrieveByPk($request->getParameter('id', 0));
    if (!$banner)
    {
      return sfView::ERROR;
    }

    if ($request->isMethod(sfWebRequest::POST))
    {
      $banner->delete();
      $this->redirect('design/banner');
    }
  }
}
