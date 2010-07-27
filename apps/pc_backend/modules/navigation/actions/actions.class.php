<?php

/**
 * this file is part of the openpne package.
 * (c) openpne project (http://www.openpne.jp/)
 *
 * for the full copyright and license information, please view the license
 * file and the notice file that were distributed with this source code.
 */

/**
 * navigation actions.
 *
 * @package    openpne
 * @subpackage navigation
 * @author     kousuke ebihara <ebihara@tejimaya.com>
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class navigationActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A redirect object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect('navigation/list');
  }

 /**
  * Executes list action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    $this->list = array();

    $types = Doctrine::getTable('Navigation')->getTypesByAppName($request->getParameter('app', 'pc'));

    foreach ($types as $type)
    {
      $navs = Doctrine::getTable('Navigation')->retrieveByType($type);
      foreach ($navs as $nav)
      {
        $this->list[$type][] = new NavigationForm($nav);
      }
      $nav = new Navigation();
      $nav->setType($type);
      $this->list[$type][] = new NavigationForm($nav);
    }
    
    if ($request->isMethod('post'))
    {
      $params = $request->getParameter('nav');
      $type = $params['type'];
      $count = count($this->list[$type]);
      if ($params['id'])
      {
        for ($i=0;$i<$count-1;$i++)
        {
          if ($params['id'] === $this->list[$type][$i]->getObject()->id)
          {
            $this->list[$type][$i]->bind($params);
            break;
          }
        }
      }
      else
      {
        $this->list[$type][$count-1]->bind($params);
      }
    }
  }

 /**
  * Executes edit action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeEdit(sfWebRequest $request)
  {
    $nav = $request->getParameter('nav');
    $this->forward404Unless(isset($nav['id']));
    $model = Doctrine::getTable('Navigation')->find($nav['id']);

    $this->form = new NavigationForm($model);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($nav);
      if ($this->form->isValid())
      {
        $types = Doctrine::getTable('Navigation')->getTypesByAppName($request->getParameter('app', 'pc'));
        $this->forward404Unless(in_array($nav['type'], $types));
        $this->form->save();

        $this->redirect('navigation/list?app='.$request->getParameter('app', 'pc'));
      }
    }

    $this->forward('navigation','list');
  }

 /**
  * Executes delete action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeDelete(sfWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $model = Doctrine::getTable('Navigation')->find($request->getParameter('id'));
      $this->forward404Unless($model);
      $types = Doctrine::getTable('Navigation')->getTypesByAppName($request->getParameter('app', 'pc'));
      $this->forward404Unless(in_array($model->getType(), $types));

      $model->delete();
    }

    $this->redirect('navigation/list?app='.$request->getParameter('app', 'pc'));
  }

 /**
  * Executes sort action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeSort(sfWebRequest $request)
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
          $nav = Doctrine::getTable('Navigation')->find($order[$i]);
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
