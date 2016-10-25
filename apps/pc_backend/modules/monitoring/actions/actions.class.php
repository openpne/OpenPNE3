<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * monitoring actions.
 *
 * @package    OpenPNE
 * @subpackage admin
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class monitoringActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('monitoring', 'imageList');
  }

 /**
  * Executes imageList action
  *
  * @param sfRequest $request A request object
  */
  public function executeImageList(sfWebRequest $request)
  {
    $fileTable = Doctrine_Core::getTable('File');
    $this->size = $request->getParameter('size', 20);
    $this->pager = $fileTable->getImageFilePager($request->getParameter('page', 1), $this->size);
  }

 /**
  * Executes deleteImage action
  *
  * @param sfRequest $request A request object
  */
  public function executeDeleteImage(sfWebRequest $request)
  {
    $this->image = Doctrine::getTable('File')
      ->find($request->getParameter('id'));

    $this->form = new sfForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $this->image->delete();
      $this->getUser()->setFlash('notice', '画像の削除が完了しました');

      $this->redirect('monitoring/imageList');
    }
  }

 /**
  * Executes editImage action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditImage(sfWebRequest $request)
  {
    $this->form = new ImageForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      if ($this->form->bindAndSave(
        $request->getParameter($this->form->getName()),
        $request->getFiles($this->form->getName())
      ))
      {
        $this->getUser()->setFlash('notice', '画像の追加が完了しました');

        $this->redirect('monitoring/imageList');
      }
    }
  }

 /**
  * Executes fileList action
  *
  * @param sfRequest $request A request object
  */
  public function executeFileList(sfWebRequest $request)
  {
    $fileTable = Doctrine_Core::getTable('File');
    $this->size = $request->getParameter('size', 20);
    $this->pager = $fileTable->getFilePager($request->getParameter('page', 1), $this->size);
  }

 /**
  * Executes fileDelete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDeleteFile(sfWebRequest $request)
  {
    $this->file = Doctrine::getTable('File')
      ->find($request->getParameter('id'));

    $this->form = new sfForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $this->file->delete();
      $this->getUser()->setFlash('notice', 'ファイルの削除が完了しました');
      $this->redirect('monitoring/fileList');
    }
  }

 /**
  * Executes downloadFile action
  *
  * @param sfRequest $request A request object
  */
  public function executeFileDownload(sfWebRequest $request)
  {
    $file = Doctrine::getTable('File')->find($request->getParameter('id'));
    opToolkit::fileDownload(
      $file->getOriginalFilename(),
      $file->getFileBin()->getBin()
    );
  }

  /**
   * Executes activityList action
   *
   * @param opWebRequest $request A request object
   */
  public function executeActivityList(opWebRequest $request)
  {
    $query = Doctrine_Core::getTable('ActivityData')->createQuery('r')
      ->select('r.*, m.name')
      ->innerJoin('r.Member m')
      ->orderBy('r.id DESC');

    $form = new ActivityDataFormFilter();
    $form->setQuery($query);

    if ($request->isMethod(sfWebRequest::POST))
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        $query = $form->getQuery();
      }
    }

    $maxPerPage = 20;
    $pager = new sfDoctrinePager('ActivityData', $maxPerPage);
    $pager->setQuery($query);
    $pager->setPage((int)$request->getParameter('page', 1));
    $pager->init();

    $this->form = $form;
    $this->pager = $pager;
  }

  /**
   * Executes activityDelete action
   *
   * @param opWebRequest $request A request object
   */
  public function executeActivityDelete(opWebRequest $request)
  {
    $request->checkCSRFProtection();

    $activity = $this->getRoute()->getObject();
    $activity->delete();

    $this->getUser()->setFlash('notice', 'Deleted.');
    $this->redirect(array('sf_route' => 'monitoring_activity_list'));
  }
}
