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
 * navi actions.
 *
 * @package    OpenPNE
 * @subpackage navi
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
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
    $this->list = array();

    $types = NaviPeer::retrieveTypes();

    foreach ($types as $type) {
      $navis = NaviPeer::retrieveByType($type);
      foreach ($navis as $navi) {
        $this->list[$type][] = new NaviForm($navi);
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
    $navi = $request->getParameter('navi');

    $model = NaviPeer::retrieveByPk($navi['id']);
    $this->form = new NaviForm($model);
    if ($request->isMethod('post')) {
       $this->form->bind($navi);
       if ($this->form->isValid()) {
         $this->form->save();
       }
    }

    $this->redirect('navi/index');
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    if ($request->isMethod('post')) {
      $model = NaviPeer::retrieveByPk($request->getParameter('id'));
      $this->forward404Unless($model);
      $model->delete();
    }

    $this->redirect('navi/index');
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
          $navi = NaviPeer::retrieveByPk($order[$i]);
          if ($navi)
          {
            $navi->setSortOrder($i * 10);
            $navi->save();
          }
        }
        break;
      }
    }
    return sfView::NONE;
  }
}
