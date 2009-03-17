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
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('community', 'categoryList');
  }

 /**
  * Executes categoryList action
  *
  * @param sfRequest $request A request object
  */
  public function executeCategoryList(sfWebRequest $request)
  {
    $this->categories = CommunityCategoryPeer::retrieveAllRoots();
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
  * @param sfRequest $request A request object
  */
  public function executeCategoryEdit(sfWebRequest $request)
  {
    $form = new CommunityCategoryForm(CommunityCategoryPeer::retrieveByPk($request->getParameter('id')));
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
  * @param sfRequest $request A request object
  */
  public function executeCategoryDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $category = CommunityCategoryPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($category);

    $category->delete();

    $this->getUser()->setFlash('notice', 'Deleted.');
    $this->redirect('community/categoryList');
  }
}
