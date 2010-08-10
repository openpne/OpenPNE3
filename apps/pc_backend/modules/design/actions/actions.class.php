<?php

/**
 * this file is part of the openpne package.
 * (c) openpne project (http://www.openpne.jp/)
 *
 * for the full copyright and license information, please view the license
 * file and the notice file that were distributed with this source code.
 */

/**
 * design actions.
 *
 * @package    openpne
 * @subpackage design
 * @author     kousuke ebihara <ebihara@tejimaya.com>
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
    $gadgetConfigs = Doctrine::getTable('Gadget')->getConfig();
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
    $this->configs = Doctrine::getTable('Gadget')->getConfig();
    $layouts = Doctrine::getTable('Gadget')->getGadgetLayoutConfig();
    $this->type = $request->getParameter('type', 'gadget');

    $this->forward404Unless(isset($this->configs[$this->type]));

    $this->subtitle = $this->configs[$this->type]['name'];
    $this->plotAction = $this->configs[$this->type]['plot_action'];

    $this->gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName($this->type);

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
        Doctrine::getTable('Gadget')->clearGadgetsCache();
        $this->redirect('design/gadget?type='.$this->type);
      }
    }
  }

 /**
  * Executes gadget plot action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeGadgetPlot(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type', 'gadget');
    $configs = Doctrine::getTable('Gadget')->getConfig();
    $this->forward404Unless(isset($configs[$this->type]));

    $this->layoutPattern = $configs[$this->type]['layout']['default'];
    $this->gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName($this->type);
    $this->gadgetConfig = Doctrine::getTable('Gadget')->getGadgetConfig($this->type);
    $layoutName = $this->type;
    if ('gadget' === $layoutName)
    {
      $layoutName = 'home';
    }
    $layoutName .= '_layout';
    $layout = Doctrine::getTable('SnsConfig')->get($layoutName);
    if ($layout)
    {
      $this->layoutPattern = $layout;
    }
  }

 /**
  * Executes add gadget action
  *
  * @param sfRequest $request A request object
  */
  public function executeAddGadget(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type', 'top');
    $this->config = Doctrine::getTable('Gadget')->getGadgetConfigListByType($this->type);

    return sfView::SUCCESS;
  }

 /**
  * Executes home edit gadget action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditGadget(sfWebRequest $request)
  {
    $this->gadget = Doctrine::getTable('Gadget')->find($request->getParameter('id'));

    $type = $this->gadget->getType();
    $config = Doctrine::getTable('Gadget')->getGadgetConfigListByType($type);

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
  * Execute banner action
  *
  * @param sfRequest $request A request object
  */
  public function executeBanner(sfWebRequest $request)
  {
    $id = $request->getParameter('id', 0);
    if (!$id)
    {
      $this->banner = Doctrine::getTable('Banner')->retrieveTop();
    }
    else
    {
      $this->banner = Doctrine::getTable('Banner')->find($id);
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

    $this->bannerImageList = Doctrine::getTable('BannerImage')->createQuery()->execute();
    $this->bannerList = Doctrine::getTable('Banner')->createQuery()->execute();
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
    $banner = Doctrine::getTable('BannerImage')->find($request->getParameter('id', 0));
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
    }
  }

 /**
  * Execute bannerdelete action
  *
  * @param sfRequest $request A request object
  */
  public function executeBannerdelete(sfWebRequest $request)
  {
    $banner = Doctrine::getTable('BannerImage')->find($request->getParameter('id', 0));
    if (!$banner)
    {
      return sfView::ERROR;
    }

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $banner->delete();
      $this->redirect('design/banner');
    }
  }

  public function executeCustomCss(sfWebRequest $request)
  {
    $this->form = new opCustomCssForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('css'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');

        $this->redirect('design/customCss');
      }
    }
  }

 /**
  * Executes add gadget action
  *
  * @param sfRequest $request A request object
  */
  public function executeHtml(sfWebRequest $request)
  {
    $this->type = $request->getParameter('type', opDesignHtmlForm::DEFAULT_TYPE);
    $this->forward404Unless(in_array($this->type, opDesignHtmlForm::allowedTypeList()));

    $this->typeCaptions = array(
      'footer_before'    => 'Insecure Page Footer',
      'footer_after'     => 'Secure Page Footer',
      'pc_html_head'     => 'HTML Insertion Area (in HTML head)',
      'pc_html_top2'     => 'HTML Insertion Area A',
      'pc_html_top'      => 'HTML Insertion Area B',
      'pc_html_bottom2'  => 'HTML Insertion Area C',
      'pc_html_bottom'   => 'HTML Insertion Area D',
      'mobile_html_head' => 'HTML Insertion Area (in HTML head)',
      'mobile_header'    => 'HTML Insertion Area (in page header)',
      'mobile_footer'    => 'HTML Insertion Area (in page footer)',
    );

    $snsConfigSettings = sfConfig::get('openpne_sns_config');
    $default = isset($snsConfigSettings[$this->type]['Default']) ? $snsConfigSettings[$this->type]['Default'] : null;

    $this->form = new opDesignHtmlForm(array('html' => Doctrine::getTable('SnsConfig')->get($this->type, $default)), array('type' => $this->type));
    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('design_html'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('design/html?type='.$this->type);
      }
    }
  }

  public function executeMobileColorConfig(sfWebRequest $request)
  {
    $this->presetList = (array)include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mobile_preset_color.yml'));

    $this->form = new opMobileColorConfigForm();
    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('color'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('design/mobileColorConfig');
      }
    }
  }
}
