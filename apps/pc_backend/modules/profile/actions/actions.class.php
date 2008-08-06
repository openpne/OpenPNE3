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
    $this->option_form = array();
    foreach ($this->profiles as $value) {
      $this->option_form[$value->getId()] = array();
      foreach ($value->getProfileOptions() as $option) {
        $this->option_form[$value->getId()][$option->getId()] = new ProfileOptionForm(ProfileOptionPeer::retrieveByPk($option->getId()));
      }
      $newProfileOption = new ProfileOption();
      $newProfileOption->setProfileId($value->getId());
      $this->option_form[$value->getId()][] = new ProfileOptionForm($newProfileOption);
    }
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

 /**
  * Executes editOption action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditOption($request)
  {
    $this->profileOption = ProfileOptionPeer::retrieveByPk($request->getParameter('id'));
    $this->form = new ProfileOptionForm($this->profileOption);

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('profile_option'));
      if ($this->form->isValid()) {
        $this->form->save();
      }
    }
    $this->redirect('profile/list');
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    $this->profile = ProfilePeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->profile);

    if ($request->isMethod('post')) {
      $this->profile->delete();
      $this->redirect('profile/list');
    }
  }

 /**
  * Executes deleteOption action
  *
  * @param sfRequest $request A request object
  */
  public function executeDeleteOption($request)
  {
    $this->profileOption = ProfileOptionPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->profileOption);

    if ($request->isMethod('post')) {
      $this->profileOption->delete();
    }
    $this->redirect('profile/list');
  }
}
