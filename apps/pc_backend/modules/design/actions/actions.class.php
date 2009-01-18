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
  * Executes widget action
  *
  * @param sfRequest $request A request object
  */
  public function executeWidget(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type', 'home');
    switch ($this->type)
    {
      case 'mobileHome':
        $this->widgets = array(
          'mobileTop'      => HomeWidgetPeer::retrieveMobileTopWidgets(),
          'mobileContents' => HomeWidgetPeer::retrieveMobileContentsWidgets(),
          'mobileBottom'   => HomeWidgetPeer::retrieveMobileBottomWidgets(),
        );
        break;
      case 'sideBanner':
        $this->widgets = array(
          'sideBannerContents' => HomeWidgetPeer::retrieveSideBannerContentsWidgets(),
        );
        break;
      default:
        $this->widgets = array(
          'top'      => HomeWidgetPeer::retrieveTopWidgets(),
          'sideMenu'     => HomeWidgetPeer::retrieveSideMenuWidgets(),
          'contents' => HomeWidgetPeer::retrieveContentsWidgets(),
        );
    }

    $this->sortForm = new WidgetSortForm(array(), array('current_widgets' => $this->widgets));
    $this->addForm = new WidgetAddForm(array(), array('current_widgets' => $this->widgets));
    if ($request->isMethod(sfRequest::POST))
    {
      $this->sortForm->bind($request->getParameter('widget'));
      $this->addForm->bind($request->getParameter('new'));
      if ($this->sortForm->isValid() && $this->addForm->isValid())
      {
        $this->sortForm->save();
        $this->addForm->save();
        $this->redirect('design/widget?type='.$this->type);
      }
    }
  }

 /**
  * Executes home widget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeHomeWidgetPlot(sfWebRequest $request)
  {
    $this->layoutPattern = 'layoutA';
    $this->topWidgets = HomeWidgetPeer::retrieveTopWidgets();
    $this->sideMenuWidgets = HomeWidgetPeer::retrieveSideMenuWidgets();
    $this->contentsWidgets = HomeWidgetPeer::retrieveContentsWidgets();
    $this->widgetConfig = sfConfig::get('op_widget_list');

    $layout = SnsConfigPeer::retrieveByName('home_layout');
    if ($layout)
    {
      $this->layoutPattern = $layout->getValue();
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes home add widget action
  *
  * @param sfRequest $request A request object
  */
  public function executeHomeAddWidget(sfWebRequest $request)
  {
    $validTypes = array('top', 'sideMenu', 'contents', 'mobileTop', 'mobileContents', 'mobileBottom', 'sideBannerContents');
    $mobileTypes = array('mobileTop', 'mobileContents', 'mobileBottom');
    $sideBannerTypes = array('sideBannerContents');

    $this->config = sfConfig::get('op_widget_list', array());
    $this->type = 'top';
    if (in_array($request->getParameter('type'), $validTypes))
    {
      $this->type = $request->getParameter('type');
    }

    if (in_array($this->type, $mobileTypes))
    {
      $this->config = sfConfig::get('op_mobile_widget_list', array());
    }
    elseif (in_array($this->type, $sideBannerTypes))
    {
      $this->config = sfConfig::get('op_side_banner_widget_list', array());
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes home edit widget action
  *
  * @param sfRequest $request A request object
  */
  public function executeHomeEditWidget(sfWebRequest $request)
  {
    $this->widget = HomeWidgetPeer::retrieveByPK($request->getParameter('id'));
    $config = sfConfig::get('op_widget_list', array());
    $this->forward404Unless($this->widget && $config);

    $this->config = $config[$this->widget->getName()];

    if (!empty($this->config['config']))
    {
      $this->form = new HomeWidgetConfigForm($this->widget);

      if ($request->isMethod(sfRequest::POST))
      {
        $this->form->bind($request->getParameter('home_widget_config'));
        if ($this->form->isValid())
        {
          $this->form->save();
          $this->redirect('design/homeEditWidget?id='.$this->widget->getId());
        }
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes mobile home widget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeMobileHomeWidgetPlot(sfWebRequest $request)
  {
    $this->mobileTopWidgets = (array)HomeWidgetPeer::retrieveMobileTopWidgets();
    $this->mobileContentsWidgets = (array)HomeWidgetPeer::retrieveMobileContentsWidgets();
    $this->mobileBottomWidgets = (array)HomeWidgetPeer::retrieveMobileBottomWidgets();
    $this->widgetConfig = sfConfig::get('op_mobile_widget_list');

    return sfView::SUCCESS;
  }

 /**
  * Executes side banner home widget plot action
  *
  * @param sfRequest $request A request object
  */
  public function executeSideBannerWidgetPlot(sfWebRequest $request)
  {
    $this->sideBannerContentsWidgets = (array)HomeWidgetPeer::retrieveSideBannerContentsWidgets();
    $this->widgetConfig = sfConfig::get('op_side_banner_widget_list');

    return sfView::SUCCESS;
  }
}
