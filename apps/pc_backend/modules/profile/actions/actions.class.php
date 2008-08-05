<?php

/**
 * profile actions.
 *
 * @package    OpenPNE
 * @subpackage profile
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class profileActions extends sfActions
{
 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->profiles = ProfilePeer::doSelect(new Criteria());
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    $this->profile = ProfilePeer::retrieveByPk($request->getParameter('id'));
    $this->form = new ProfileForm($this->profile);

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('profile'));
      if ($this->form->isValid()) {
        $this->form->save();
        $this->redirect('profile/list');
      }
    }
  }
}
