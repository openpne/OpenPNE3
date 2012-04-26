<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * advanced actions.
 *
 * @package    OpenPNE
 * @subpackage advanced
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class advancedActions extends sfActions
{
 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig(sfWebRequest $request)
  {
    $this->category = $request->getParameter('category', 'advanced');
    $this->categoryAttributes = sfConfig::get('openpne_sns_category_attribute');

    $this->forward404If(!empty($this->categoryAttributes[$this->category]['Hidden']));

    $formName = 'op'.sfInflector::camelize($this->category).'SnsConfigForm';
    if (class_exists($formName, true))
    {
      $this->form = new $formName();
    }
    else
    {
      $this->form = new SnsConfigForm(array(), array('category' => $this->category));
    }

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('sns_config'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('advanced/config?category='.$this->category);
      }

      $this->getUser()->setFlash('error', 'Failed to save.', false);
    }
  }

  public function executeRichTextarea(sfWebRequest $request)
  {
    $this->sortForm = new BaseForm();
    $this->configForm = new opRichTextareaOpenPNEConfigForm();
    $this->buttonConfigForm = new opRichTextareaOpenPNEButtonConfigForm();
    $this->buttonConfig = opWidgetFormRichTextareaOpenPNE::getAllButtons();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->configForm->bind($request->getParameter('config'));
      $this->buttonConfigForm->bind($request->getParameter('button'));
      if ($this->configForm->isValid() && $this->buttonConfigForm->isValid())
      {
        $this->configForm->save();
        $this->buttonConfigForm->save();
      }
    }
  }

  public function executeChangeRichTextareaButtonOrder(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $request->checkCSRFProtection();

      $buttons = $request->getParameter('button');
      Doctrine::getTable('SnsConfig')->set('richtextarea_buttons_sort_order', serialize($buttons));
    }
    return sfView::NONE;
  }
}
