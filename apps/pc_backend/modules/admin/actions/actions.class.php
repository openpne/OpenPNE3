<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * admin actions.
 *
 * @package    OpenPNE
 * @subpackage admin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class adminActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('admin', 'manageUser');
  }

 /**
  * Executes manageUser action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeManageUser(sfWebRequest $request)
  {
    $this->users = Doctrine::getTable('AdminUser')->retrievesAll();
  }

 /**
  * Executes adminUser action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeAddUser(sfWebRequest $request)
  {
    $this->form = new AdminUserForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('admin_user');
      $this->redirectIf($this->form->bindAndSave($params), 'admin/manageUser');
    }
  }

 /**
  * Executes deleteUser action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeDeleteUser(sfWebRequest $request)
  {
    $this->user = Doctrine::getTable('AdminUser')->find($request->getParameter('id'));
    $this->forward404Unless($this->user);
    $this->forward404If($this->user->getId() == $this->getUser()->getId());
    $this->forward404If($this->user->getId() == 1);

    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $field = $this->form->getCSRFFieldName();
      $this->form->bind(array($field => $request->getParameter($field)));
      if ($this->form->isValid())
      {
        $this->user->delete();
        $this->redirect('admin/manageUser');
      }
    }
  }

 /**
  * Executes editPassword action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeEditPassword(sfWebRequest $request)
  {
    $user = Doctrine::getTable('AdminUser')->find($this->getUser()->getId());
    $this->form = new AdminUserEditPasswordForm($user);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('admin_user');
      $this->redirectIf($this->form->bindAndSave($params), 'admin/manageUser');
    }
  }

 /**
  * Executes changeLanguage action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeChangeLanguage(sfWebRequest $request)
  {
    $this->form = new opLanguageSelecterForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('language'));
      if ($this->form->isValid())
      {
        $this->form->setCulture();
        $this->getUser()->setFlash('notice', 'Changed configuration for your culture.');
        $this->redirect('admin/changeLanguage');
      }
    }
  }
}
