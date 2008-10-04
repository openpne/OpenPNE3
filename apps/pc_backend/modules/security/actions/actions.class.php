<?php

/**
 * security actions.
 *
 * @package    OpenPNE
 * @subpackage security
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class securityActions extends sfActions
{
 /**
  * Executes login action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin($request)
  {
    $this->getUser()->setAuthenticated(false);

    $this->form = new AdminUserForm();

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('admin_user'));
      if ($this->form->isValid()) {
        $this->getUser()->setAuthenticated(true);
        $this->redirect('security/top');
      }

      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes top action
  *
  * @param sfRequest $request A request object
  */
  public function executeTop($request)
  {
  }

}
