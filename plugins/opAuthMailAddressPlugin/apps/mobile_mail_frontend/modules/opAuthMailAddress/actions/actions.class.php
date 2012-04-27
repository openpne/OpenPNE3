<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthMailAddress actions.
 *
 * @package    OpenPNE
 * @subpackage opAuthMailAddressPlugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opAuthMailAddressActions extends sfActions
{
 /**
  * Executes register
  *
  * @param sfWebRequest A request object
  */
  public function executeRegister(sfWebRequest $request)
  {
    if ($this->getRoute()->getMember())
    {
      $this->forward('opAuthMailAddress', 'login');
    }

    $adapter = new opAuthAdapterMailAddress('MailAddress');
    if ($adapter->getAuthConfig('invite_mode') < 2)
    {
      return sfView::NONE;
    }

    $message = $request->getMailMessage();

    $this->form = new opRequestRegisterURLForm(null, array('authMode' => 'MailAddress'));
    $this->form->bind(array('mail_address' => $message->from));
    if ($this->form->isValid())
    {
      $this->form->sendMail();
    }

    return sfView::NONE;
  }

 /**
  * Executes login
  *
  * @param sfWebRequest A request object
  */
  public function executeLogin(sfWebRequest $request)
  {
    if (!$this->getRoute()->getMember())
    {
      $this->forward('opAuthMailAddress', 'register');
    }
  }
}
