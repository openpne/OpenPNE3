<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOAuthConsumerAction
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opOAuthConsumerAction extends sfActions
{
  protected function getForm($consumer = null)
  {
    if ($this->form)
    {
      return $this->form;
    }

    $this->form = new OAuthConsumerInformationForm($consumer);

    return $this->form;
  }

  public function executeList(sfWebRequest $request)
  {
    $this->consumers = Doctrine::getTable('OAuthConsumerInformation')->findAll();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = $this->getForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = $this->getForm();

    if ($this->form->bindAndSave($request->getParameter('o_auth_consumer_information'), $request->getFiles('o_auth_consumer_information')))
    {
      $this->redirect('@connection_show?id='.$this->form->getObject()->getId());
    }

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->consumer = $this->getRoute()->getObject();
    $this->form = $this->getForm($this->consumer);

    if ($request->isMethod(sfWebRequest::PUT))
    {
      if ($this->form->bindAndSave($request->getParameter('o_auth_consumer_information'), $request->getFiles('o_auth_consumer_information')))
      {
        $this->redirect('connection/show?id='.$this->form->getObject()->getId());
      }
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->consumer = $this->getRoute()->getObject();
    $this->form = new OAuthConsumerInformationForm($this->consumer);
    if ($this->form->bindAndSave($request->getParameter('o_auth_consumer_information'), $request->getFiles('o_auth_consumer_information')))
    {
      $this->redirect('@connection_show?id='.$this->form->getObject()->getId());
    }

    $this->setTemplate('edit');
  }

  public function executeRemoveToken(sfWebRequest $request)
  {
    $this->consumer = Doctrine::getTable('OAuthConsumerInformation')->find($request->getParameter('id'));
    $this->forward404Unless($this->consumer);
    $this->forward404Unless($this->consumer->getOAuthAdminAccessToken());

    $this->form = new sfForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $field = $this->form->getCSRFFieldName();
      $this->form->bind(array($field => $request->getParameter($field)));
      if ($this->form->isValid())
      {
        $this->consumer->getOAuthAdminAccessToken()->delete();
        $this->redirect('connection/list');
      }
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->consumer = $this->getRoute()->getObject();
  }

  public function executeDeleteConfirm(sfWebRequest $request)
  {
    $this->consumer = $this->getRoute()->getObject();
    $this->form = new sfForm();
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->consumer = $this->getRoute()->getObject();

    $request->checkCSRFProtection();

    $this->getUser()->setFlash('notice', 'The application was deleted successfully.');

    $this->consumer->delete();
    $this->redirect('connection/list');
  }
}
