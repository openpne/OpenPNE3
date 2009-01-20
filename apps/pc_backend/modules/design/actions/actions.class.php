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
          'sideMenu'     => GadgetPeer::retrieveSideMenuGadgets(),
          'contents' => GadgetPeer::retrieveContentsGadgets(),
          'bottom' => GadgetPeer::retrieveBottomGadgets(),
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
    $validTypes = array('top', 'sideMenu', 'contents', 'bottom', 'mobileTop', 'mobileContents', 'mobileBottom', 'sideBannerContents');
    $mobileTypes = array('mobileTop', 'mobileContents', 'mobileBottom');
    $sideBannerTypes = array('sideBannerContents');

    $this->config = sfConfig::get('op_gadget_list', array());
    $this->type = 'top';
    if (in_array($request->getParameter('type'), $validTypes))
    {
      $this->type = $request->getParameter('type');
    }

    if (in_array($this->type, $mobileTypes))
    {
      $this->config = sfConfig::get('op_mobile_gadget_list', array());
    }
    elseif (in_array($this->type, $sideBannerTypes))
    {
      $this->config = sfConfig::get('op_side_banner_gadget_list', array());
    }

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
    $config = sfConfig::get('op_gadget_list', array());
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
}
