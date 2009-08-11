<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * connection actions.
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class connectionActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('connection', 'list');
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    $this->consumers = Doctrine::getTable('OAuthConsumerInformation')->findAll();
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit(sfWebRequest $request)
  {
    $this->consumer = Doctrine::getTable('OAuthConsumerInformation')->find($request->getParameter('id'));
    $this->forward404Unless($this->consumer);

    $this->form = new OAuthConsumerInformationForm($this->consumer);
    if ($request->isMethod(sfWebRequest::PUT))
    {
      if ($this->form->bindAndSave($request->getParameter('o_auth_consumer_information'), $request->getFiles('o_auth_consumer_information')))
      {
        $this->redirect('connection/show?id='.$this->form->getObject()->getId());
      }
    }
  }

 /**
  * Executes register action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegister(sfWebRequest $request)
  {
    $this->form = new OAuthConsumerInformationForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      if ($this->form->bindAndSave($request->getParameter('o_auth_consumer_information'), $request->getFiles('o_auth_consumer_information')))
      {
        $this->redirect('connection/show?id='.$this->form->getObject()->getId());
      }
    }
  }

 /**
  * Executes show action
  *
  * @param sfRequest $request A request object
  */
  public function executeShow(sfWebRequest $request)
  {
    $this->consumer = Doctrine::getTable('OAuthConsumerInformation')->find($request->getParameter('id'));
    $this->forward404Unless($this->consumer);
  }
}
