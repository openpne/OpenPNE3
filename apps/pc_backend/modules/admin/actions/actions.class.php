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
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('admin', 'manageUser');
  }

 /**
  * Executes manageUser action
  *
  * @param sfRequest $request A request object
  */
  public function executeManageUser(sfWebRequest $request)
  {
    $this->users = AdminUserPeer::retrievesAll();
  }

 /**
  * Executes adminUser action
  *
  * @param sfRequest $request A request object
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
  * @param sfRequest $request A request object
  */
  public function executeDeleteUser(sfWebRequest $request)
  {
    $this->user = AdminUserPeer::retrieveByPk($request->getParameter('id'));
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
  * @param sfRequest $request A request object
  */
  public function executeEditPassword(sfWebRequest $request)
  {
    $user = AdminUserPeer::retrieveByPk($this->getUser()->getId());
    $this->form = new AdminUserEditPasswordForm($user);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $params = $request->getParameter('admin_user');
      $this->redirectIf($this->form->bindAndSave($params), 'admin/manageUser');
    }
  }
}
