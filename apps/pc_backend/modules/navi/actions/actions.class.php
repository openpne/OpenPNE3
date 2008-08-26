<?php

/**
 * navi actions.
 *
 * @package    OpenPNE
 * @subpackage navi
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class naviActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    $this->list = array();

    $types = NaviPeer::retrieveTypes();

    foreach ($types as $type) {
      $navis = NaviPeer::retrieveByType($type);
      foreach ($navis as $navi) {
        $this->list[$type][] = new NaviForm($navi);
      }
      $this->list[$type][] = new NaviForm();
    }
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    $navi = $request->getParameter('navi');

    $model = NaviPeer::retrieveByPk($navi['id']);
    $this->form = new NaviForm($model);
    if ($request->isMethod('post')) {
       $this->form->bind($navi);
       if ($this->form->isValid()) {
         $this->form->save();
       }
    }

    $this->redirect('navi/index');
  }

 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    if ($request->isMethod('post')) {
      $model = NaviPeer::retrieveByPk($request->getParameter('id'));
      $this->forward404Unless($model);
      $model->delete();
    }

    $this->redirect('navi/index');
  }
}
