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
class connectionActions extends opOAuthConsumerAction
{
  public function executeList(sfWebRequest $request)
  {
    $this->consumers = Doctrine::getTable('OAuthConsumerInformation')
      ->findByMemberId($this->getUser()->getMemberId());
  }

  public function executeRegister(sfWebRequest $request)
  {
    $this->form = new OAuthConsumerInformationForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->getObject()->setMemberId($this->getUser()->getMemberId());
      if ($this->form->bindAndSave($request->getParameter('o_auth_consumer_information'), $request->getFiles('o_auth_consumer_information')))
      {
        $this->redirect('connection/show?id='.$this->form->getObject()->getId());
      }
    }
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->consumer = Doctrine::getTable('OAuthConsumerInformation')->find($request->getParameter('id'));
    $this->forward404Unless($this->consumer);
    $this->forward404Unless($this->consumer->getMemberId() === $this->getUser()->getMemberId());

    return parent::executeEdit($request);
  }
}
