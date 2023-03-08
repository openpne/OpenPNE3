<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sns actions.
 *
 * @package    OpenPNE
 * @subpackage sns
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class snsActions extends sfActions
{
 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig(sfWebRequest $request)
  {
    $this->category = $request->getParameter('category', 'general');
    $this->categoryAttributes = sfConfig::get('openpne_sns_category_attribute');

    $this->forward404If(empty($this->categoryAttributes[$this->category]) || !empty($this->categoryAttributes[$this->category]['Hidden']));

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
        $this->redirect('sns/config?category='.$this->category);
      }

      $this->getUser()->setFlash('error', 'Failed to save.', false);
    }
  }

 /**
  * Executes term action
  *
  * @param sfRequest $request A request object
  */
  public function executeTerm(sfWebRequest $request)
  {
    $this->form = new opSnsTermForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('term'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->removeNavCaches();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('sns/term');
      }
    }
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
  }

  public function executeCache(sfWebRequest $request)
  {
    $this->form = new sfForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      opToolkit::clearCache();

      $this->getUser()->setFlash('notice', 'Caches are now cleared.');
      $this->redirect('sns/cache');
    }
  }

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
