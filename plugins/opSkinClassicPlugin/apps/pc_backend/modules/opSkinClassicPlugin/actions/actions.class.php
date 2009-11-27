<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSkinClassicPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage opSkinClassicPlugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opSkinClassicPluginActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->presetForm = new opSkinClassicPresetForm();
    $this->colorForm = new opSkinClassicColorForm();
    $this->loginForm = new opSkinClassicLoginForm();
  }

  public function executeSkinList(sfWebRequest $request)
  {
    $this->forms = array();
    $images = array_merge(opSkinClassicConfig::getImages(), opSkinClassicConfig::getThemeImages());

    foreach ($images as $v)
    {
      $this->forms[$v] = new opSkinClassicImageForm(array(), array('target' => $v));
    }
  }

  public function executeSkin(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfWebRequest::POST));

    $target = $request->getParameter('target');
    $image = $request->getParameter('image');
    $imageFiles = $request->getFiles('image');
    if (!isset($image[$target]))
    {
      $image[$target] = array();
    }
    if (!isset($imageFiles[$target]))
    {
      $imageFiles[$target] = array();
    }

    $form = new opSkinClassicImageForm(array(), array('target' => $target));
    $form->bind($image[$target], $imageFiles[$target]);
    if ($form->isValid())
    {
      $form->save();
      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirect('opSkinClassicPlugin/skinList');
    }

    $this->getUser()->setFlash('error', $form->getErrorSchema()->getMessage());
    $this->redirect('opSkinClassicPlugin/skinList');
  }

  public function executePreset(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfWebRequest::POST));

    $this->presetForm = new opSkinClassicPresetForm();
    $this->presetForm->bind($request->getParameter('preset'));
    if ($this->presetForm->isValid())
    {
      $this->presetForm->save();
      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirect('opSkinClassicPlugin/index');
    }

    $this->colorForm = new opSkinClassicColorForm();
    $this->loginForm = new opSkinClassicLoginForm();
    $this->setTemplate('index');
  }

  public function executeColor(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfWebRequest::POST));

    $this->colorForm = new opSkinClassicColorForm();
    $this->colorForm->bind($request->getParameter('color'));
    if ($this->colorForm->isValid())
    {
      $this->colorForm->save();
      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirect('opSkinClassicPlugin/index');
    }

    $this->presetForm = new opSkinClassicPresetForm();
    $this->loginForm = new opSkinClassicLoginForm();
    $this->setTemplate('index');
  }

  public function executeLogin(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfWebRequest::POST));

    $this->loginForm = new opSkinClassicLoginForm();
    $this->loginForm->bind($request->getParameter('login'));
    if ($this->loginForm->isValid())
    {
      $this->loginForm->save();
      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirect('opSkinClassicPlugin/index');
    }

    $this->presetForm = new opSkinClassicPresetForm();
    $this->colorForm = new opSkinClassicColorForm();
    $this->setTemplate('index');
  }
}
