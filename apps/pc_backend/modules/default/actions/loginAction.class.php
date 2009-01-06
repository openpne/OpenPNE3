<?php

/**
 * login action.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class loginAction extends sfAction
{
 /**
  * Executes this action
  *
  * @param sfRequest $request A request object
  */
  public function execute($request)
  {
    $this->getUser()->setAuthenticated(false);

    $this->form = new opAdminLoginForm();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('admin_user'));
      if ($this->form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
        $this->redirect('default/top');
      }
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }
}
