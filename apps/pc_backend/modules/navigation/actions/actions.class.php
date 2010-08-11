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
    $this->deleteForm = new BaseForm();
    $this->sortForm = new BaseForm();

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

    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('nav');
      $this->forward404Unless(isset($params['type']));
      $type = $params['type'];
      $this->forward404Unless(isset($this->list[$type]));
      $count = count($this->list[$type]);
      if (!isset($params['id']))
      {
        $params['id'] = 0;
      }
      if ($params['id'])
      {
        for ($i = 0; $i < $count - 1; $i++)
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
        $this->list[$type][$count - 1]->bind($params);
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
    $app = $request->getParameter('app', 'pc');

    $this->form = new NavigationForm($model);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($nav);
      if ($this->form->isValid())
      {
        $types = Doctrine::getTable('Navigation')->getTypesByAppName($app);
        $this->forward404Unless(in_array($nav['type'], $types));
        $this->form->save();

        if ('pc' === $app)
        {
          $this->removeNavCaches();
        }

        $this->redirect('navigation/list?app='.$app);
      }
    }

    $this->forward('navigation', 'list');
  }

 /**
  * Executes delete action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeDelete(sfWebRequest $request)
  {
    $app = $request->getParameter('app', 'pc');

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $model = Doctrine::getTable('Navigation')->find($request->getParameter('id'));
      $this->forward404Unless($model);
      $types = Doctrine::getTable('Navigation')->getTypesByAppName($app);
      $this->forward404Unless(in_array($model->getType(), $types));

      $model->delete();

      if ('pc' === $app)
      {
        $this->removeNavCaches();
      }
    }

    $this->redirect('navigation/list?app='.$app);
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

    $request->checkCSRFProtection();

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

  /**
   *
   */
  private function removeNavCaches()
  {
    $currentApp = sfContext::getInstance()->getConfiguration()->getApplication();
    $cacheApp = 'pc_frontend';

    if (!sfContext::hasInstance($cacheApp))
    {
      sfContext::createInstance(
        ProjectConfiguration::getApplicationConfiguration(
          $cacheApp,
          $this->getContext()->getConfiguration()->getEnvironment(),
          $this->getContext()->getConfiguration()->isDebug()
        )
      );
    }

    sfContext::switchTo($cacheApp);
    if ($cache = sfContext::getInstance($cacheApp)->getViewCacheManager())
    {
      $cache->remove('@sf_cache_partial?module=default&action=_globalNav&sf_cache_key=*');
      $cache->remove('@sf_cache_partial?module=default&action=_localNav&sf_cache_key=*');
    }
    sfContext::switchTo($currentApp);
  }
}
