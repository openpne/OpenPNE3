<?php

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
}
