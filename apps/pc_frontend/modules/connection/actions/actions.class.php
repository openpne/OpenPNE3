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
    $this->pager = Doctrine::getTable('OAuthConsumerInformation')
      ->getListPager($this->getUser()->getMemberId());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new OAuthConsumerInformationForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new OAuthConsumerInformationForm();
    $this->form->getObject()->setMemberId($this->getUser()->getMemberId());
    if ($this->form->bindAndSave($request->getParameter('o_auth_consumer_information'), $request->getFiles('o_auth_consumer_information')))
    {
      $this->redirect('connection/show?id='.$this->form->getObject()->getId());
    }

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->consumer = $this->getRoute()->getObject();

    return parent::executeEdit($request);
  }
}
