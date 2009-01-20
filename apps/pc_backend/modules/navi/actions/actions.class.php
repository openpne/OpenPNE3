<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * navi actions.
 *
 * @package    OpenPNE
 * @subpackage navi
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class naviActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    $this->app = $request->getParameter('app', 'pc');
    $isMobile = (bool)('mobile' === $this->app);

    $this->list = array();

    $types = NaviPeer::retrieveTypes($isMobile);

    foreach ($types as $type)
    {
      $navs = NaviPeer::retrieveByType($type);
      foreach ($navs as $nav)
      {
        $this->list[$type][] = new NaviForm($nav);
      }
      $this->list[$type][] = new NaviForm();
    }
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    $nav = $request->getParameter('navi');
    $app = $request->getParameter('app', 'pc');

    $model = NaviPeer::retrieveByPk($nav['id']);
    $this->form = new NaviForm($model);
    if ($request->isMethod('post'))
    {
       $this->form->bind($nav);
       if ($this->form->isValid())
       {
         $this->form->save();
       }
    }

    $this->redirect('navi/index?app='.$app);
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    $app = $request->getParameter('app', 'pc');

    if ($request->isMethod('post'))
    {
      $model = NaviPeer::retrieveByPk($request->getParameter('id'));
      $this->forward404Unless($model);
      $model->delete();
    }

    $this->redirect('navi/index?app='.$app);
  }

  /**
   * Executes sort action
   *
   * @param sfRequest $request A request object
   */
  public function executeSort($request)
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
          $nav = NaviPeer::retrieveByPk($order[$i]);
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
