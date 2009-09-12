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
      ->getImageFilePager($params);
  }


 /**
  * Executes fileDelete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete(sfWebRequest $request)
  {
    $this->image = Doctrine::getTable('File')
      ->find($request->getParameter('id'));
    $this->forward404Unless($this->image);

    if ($request->isMethod(sfWebRequest::POST)) {
      $this->image->delete();
      $this->getUser()->setFlash
      (
        'notice',
        sfContext::getInstance()->getI18N()
          ->__('画像の削除が完了しました')
      );
      $this->redirect('monitoringFunction/list');
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
        $this->form->bindAndSave
        (
          $request->getParameter('image'),
          $request->getFiles('image')
        );
        $this->getUser()->setFlash
        (
          'notice',
          sfContext::getInstance()->getI18N()
            ->__('画像の追加が完了しました')
        );
        $this->redirect('monitoringFunction/list');
    }
  }

 /**
  * Executes fileList action
  *
  * @param sfRequest $request A request object
  */
  public function executeFileList(sfWebRequest $request)
  {
    $params = $request->getParameter('page', 1);
    $this->pager = Doctrine::getTable('File')
      ->getFilePager($params);
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

    if ($request->isMethod(sfWebRequest::POST)) {
      $this->file->delete();
      $this->getUser()->setFlash
      (
        'notice',
        sfContext::getInstance()->getI18N()
          ->__('ファイルの削除が完了しました')
      );
      $this->redirect('monitoringFunction/fileList');
    }
  }

 /**
  * Executes downloadFile action
  *
  * @param sfRequest $request A request object
  */
  public function executeDownloadFile(sfWebRequest $request)
  {
    $this->file = Doctrine::getTable('File')
      ->find($request->getParameter('id'));
    $this->fileBin = Doctrine::getTable('FileBin')
      ->find($request->getParameter('id'));
    $original_filename = $this->file->getOriginalFilename();
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
      $original_filename = mb_convert_encoding($original_filename, 'SJIS', 'UTF-8');
    }
    $original_filename = str_replace(array("\r", "\n"), '', $original_filename);

    header('Content-Disposition: attachment; filename="' . $original_filename . '"');
    header('Content-Length: '. strlen($this->fileBin->getBin()));
    header('Content-Type: application/octet-stream');
    echo $this->fileBin->getBin();
    exit;
  }
}
