<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Kosuke Ebihara <ebihara@tejimaya.com>
 */
class communityActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('community', 'list');
  }

  /**
   * Executes list action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeList(sfWebRequest $request)
  {
    $this->form = new CommunityFormFilter(array(), array('use_id' => true));
    $this->form->bind($request->getParameter('community'), array());

    $this->pager = new sfDoctrinePager('Community', 20);
    if ($request->hasParameter('community'))
    {
      $this->pager->setQuery($this->form->getQuery());
    }
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    return sfView::SUCCESS;
  }

  /**
   * Executes delete action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->community = Doctrine::getTable('Community')->find($request->getParameter('id'));
    $this->forward404Unless($this->community);

    if ($request->isMethod(sfRequest::POST))
    {
      $this->community->delete();
      $this->getUser()->setFlash('notice', 'Deleted.');
      $this->redirect('community/list');
    }
    return sfView::SUCCESS;
  }

  /**
   * Executes defaultCommunityList
   *
   * @param sfWebRequest $request A request object
   */
  public function executeDefaultCommunityList(sfWebRequest $request)
  {
    $this->form = new DefaultCommunityForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('community'));
      if ($this->form->isValid())
      {
        $this->redirect('community/addDefaultCommunity?id='.$this->form->getValue('id'));
      }
    }
    
    $this->communities = Doctrine::getTable('Community')->getDefaultCommunities();
  }

  /**
   * Executes add default community
   *
   * @param sfWebRequest A request object
   */
  public function executeAddDefaultCommunity(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('id'));
    $this->community = Doctrine::getTable('Community')->find($request->getParameter('id'));
    $this->forward404Unless($this->community);
    $this->forward404If((bool)$this->community->getConfig('is_default'));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->community->setConfig('is_default', 1);
      $this->getUser()->setFlash('notice', 'Saved.');

      $this->redirect('community/defaultCommunityList');
    }
    return sfView::INPUT;
  }

  /**
   * Executes removeDefaultCommunity
   *
   * @param sfWebRequest $request A request object
   */
  public function executeRemoveDefaultCommunity(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('id'));
    $this->community = Doctrine::getTable('Community')->find($request->getParameter('id'));
    $this->forward404Unless($this->community);
    $this->forward404Unless((bool)$this->community->getConfig('is_default'));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->community->setConfig('is_default', 0);
      $this->getUser()->setFlash('notice', 'Deleted.');

      $this->redirect('community/defaultCommunityList');
    }
    return sfView::INPUT;
  }

  /**
   * Execute add all member to community action
   *
   * @param sfWebRequest $request
   */
  public function executeAddAllMember(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('id'));
    $this->community = Doctrine::getTable('Community')->find($request->getParameter('id'));
    $this->forward404Unless($this->community);

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $ids = Doctrine::getTable('CommunityMember')->getMemberIdsByCommunityId($this->community->getId());
      $query = Doctrine::getTable('Member')->createQuery()->select('id');
      if (count($ids))
      {
        $query->whereNotIn('id', $ids);
      }
      foreach ($query->execute()->getPrimaryKeys() as $id)
      {
        Doctrine::getTable('CommunityMember')->join($id, $this->community->getId());
      }
      $this->getUser()->setFlash('notice', 'All member joined the community.');
      $this->redirect('community/list');
    }

    return sfView::INPUT;
  }

  /**
   * Executes categoryList action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCategoryList(sfWebRequest $request)
  {
    $this->categories = Doctrine::getTable('CommunityCategory')->retrieveAllRoots();
    $this->rootForm = new CommunityCategoryForm();
    $this->deleteForm = new sfForm();
    $this->categoryForms = array();
    foreach ($this->categories as $category)
    {
      $this->categoryForms[$category->getId()] = new CommunityCategoryForm(null, array('category' => $category));
    }

    $params = $request->getParameter('community_category');
    if ($request->isMethod(sfRequest::POST))
    {
      $targetForm = $this->rootForm;
      if (isset($params['tree_key']) && isset($this->categoryForms[$params['tree_key']]))
      {
        $targetForm = $this->categoryForms[$params['tree_key']];
      }
      if ($targetForm->bindAndSave($params))
      {
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('community/categoryList');
      }
    }
  }

  /**
   * Executes categoryList action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCategoryEdit(sfWebRequest $request)
  {
    $form = new CommunityCategoryForm(Doctrine::getTable('CommunityCategory')->find($request->getParameter('id')));
    if ($request->isMethod(sfRequest::POST))
    {
      if ($form->bindAndSave($request->getParameter('community_category')))
      {
        $this->getUser()->setFlash('notice', 'Saved.');
      }
      else
      {
        $this->getUser()->setFlash('error', $form->getErrorSchema()->getMessage());
      }
    }
    $this->redirect('community/categoryList');
  }

  /**
   * Executes categoryDelete action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCategoryDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $category = Doctrine::getTable('CommunityCategory')->find($request->getParameter('id'));
    $this->forward404Unless($category);

    if ($category->isRoot())
    {
      $category->deleteAllChildren();
    }
    $category->delete();

    $this->getUser()->setFlash('notice', 'Deleted.');
    $this->redirect('community/categoryList');
  }
}
