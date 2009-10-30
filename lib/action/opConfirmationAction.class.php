<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opConfirmationAction
 *
 * @package    OpenPNE
 * @subpackage confirmation
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opConfirmationAction extends sfActions
{
  public function preExecute()
  {
    $this->category = $this->getRequestParameter('category');
    $this->config = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/confirmation.yml'));
  }

  public function executeList(sfWebRequest $request)
  {
    $this->checkCategory();

    $this->list = array();

    $params = array('category' => $this->category, 'member' => $this->getUser()->getMember());
    $event = new sfEvent($this, 'op_confirmation.list', $params);
    $this->dispatcher->notifyUntil($event);

    if ($event->isProcessed())
    {
      $list = (array)$event->getReturnValue();

      $filterEvent = new sfEvent($this, 'op_confirmation.list_filter', $params);
      $this->dispatcher->filter($filterEvent, $list);

      $this->list = (array)$filterEvent->getReturnValue();
    }

    $this->form = new sfForm();
  }

  public function executeDecision(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->checkCategory();

    $params = array('category' => $this->category, 'id' => $request->getParameter('id'), 'is_accepted' => $request->hasParameter('accept'), 'member' => $this->getUser()->getMember());
    $event = new sfEvent($this, 'op_confirmation.decision', $params);
    $this->dispatcher->notifyUntil($event);
    if ($event->isProcessed())
    {
      $message = (string)$event->getReturnValue();
      $this->getUser()->setFlash('notice', $message);
    }

    $this->redirect('@confirmation_list?category='.$this->category);
  }

  protected function checkCategory()
  {
    $this->forward404If($this->category && !isset($this->config[$this->category]));
  }
}
