<?php

/**
 * mail actions.
 *
 * @package    OpenPNE
 * @subpackage mail
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class mailActions extends sfActions
{
  public function executeConfig(sfWebRequest $request)
  {
    $this->form = new opMailNotificationForm();
    $this->config = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mail_template.yml'));

    if ($this->request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('notification'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');

        $this->redirect('@mail_config');
      }
    }
  }
}
