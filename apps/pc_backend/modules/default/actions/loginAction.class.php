<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
        $this->getUser()->login($this->form->getValue('adminUser')->getId());
        $this->redirect('default/top');
      }
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }
}
