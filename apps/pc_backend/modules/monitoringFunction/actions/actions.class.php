<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * monitoringFunction actions.
 *
 * @package    OpenPNE
 * @subpackage admin
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class monitoringFunctionActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('monitoringFunction', 'list');
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    $params = $request->getParameter('page', 1);
    $this->pager = Doctrine::getTable('File')
      ->getImageFiles($params);
  }

 /**
  * Executes adminUser action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete(sfWebRequest $request)
  {
    $this->image = Doctrine::getTable('File')->find($request->getParameter('id'));
    $this->forward404Unless($this->image);

    if ($request->isMethod(sfWebRequest::POST)) {
      $this->image->delete();
      $this->getUser()->setFlash
      (
        'notice',
        sfContext::getInstance()->getI18N()->__('画像の削除が完了しました')
      );
      $this->redirect('monitoringFunction/list');
    }
  }
 /**
  * Executes deleteUser action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditImage(sfWebRequest $request)
  {
    $this->form = new ImageForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
        $this->form->bindAndSave(
          $request->getParameter('image'),
          $request->getFiles('image')
        );
        $this->getUser()->setFlash
        (
          'notice',
          sfContext::getInstance()->getI18N()->__('画像の追加が完了しました')
        );
        $this->redirect('monitoringFunction/list');
    }
  }
}
