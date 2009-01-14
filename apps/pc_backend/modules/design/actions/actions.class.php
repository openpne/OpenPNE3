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
  * Executes home widget action
  *
  * @param sfRequest $request A request object
  */
  public function executeHomeWidget(sfWebRequest $request)
  {
    $this->topWidgets = HomeWidgetPeer::retrieveTopWidgets();
    $this->sideMenuWidgets = HomeWidgetPeer::retrieveSideMenuWidgets();
    $this->contentsWidgets = HomeWidgetPeer::retrieveContentsWidgets();
    $this->widgetConfig = sfConfig::get('op_widget_list');

    $this->sortForm = new HomeWidgetSortForm();
    $this->addForm = new HomeWidgetAddForm();
    if ($request->isMethod(sfRequest::POST))
    {
      $this->sortForm->bind($request->getParameter('widget'));
      $this->addForm->bind($request->getParameter('new'));
      if ($this->sortForm->isValid() && $this->addForm->isValid())
      {
        $this->sortForm->save();
        $this->addForm->save();
        $this->redirect('design/homeWidget');
      }
    }
    return sfView::SUCCESS;
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
    $validTypes = array('top', 'sideMenu', 'contents', 'mobileTop', 'mobileContents', 'mobileBottom');
    $mobileTypes = array('mobileTop', 'mobileContents', 'mobileBottom');

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
  * Executes mobile home widget action
  *
  * @param sfRequest $request A request object
  */
  public function executeMobileHomeWidget(sfWebRequest $request)
  {
    $this->mobileTopWidgets = (array)HomeWidgetPeer::retrieveMobileTopWidgets();
    $this->mobileContentsWidgets = (array)HomeWidgetPeer::retrieveMobileContentsWidgets();
    $this->mobileBottomWidgets = (array)HomeWidgetPeer::retrieveMobileBottomWidgets();
    $this->widgetConfig = sfConfig::get('op_mobile_widget_list');

    $this->sortForm = new HomeWidgetSortForm(array(), array('is_mobile' => true));
    $this->addForm = new HomeWidgetAddForm();
    if ($request->isMethod(sfRequest::POST))
    {
      $this->sortForm->bind($request->getParameter('widget'));
      $this->addForm->bind($request->getParameter('new'));
      if ($this->sortForm->isValid() && $this->addForm->isValid())
      {
        $this->sortForm->save();
        $this->addForm->save();
        $this->redirect('design/mobileHomeWidget');
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
}
